<?php

namespace App\Console\Commands;

use App\Models\Dispatch;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

#[Signature('dispatch:sync-en-route')]
#[Description('Automatically update dispatch statuses (Assigned -> En Route when active, and En Route -> Completed when arriving near destination)')]
class SyncDispatchesEnRoute extends Command
{
    /**
     * Proximity threshold in meters to consider destination reached.
     */
    private const PROXIMITY_THRESHOLD_METERS = 150.0;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();

        $dispatches = Dispatch::query()
            ->whereIn('status', ['Assigned', 'En Route'])
            ->whereDate('departure_time', $today)
            ->whereHas('vehicle', function ($query): void {
                $query->whereNotNull('device_sn')
                    ->where('device_sn', '!=', '');
            })
            ->with('vehicle')
            ->get();

        if ($dispatches->isEmpty()) {
            $this->info('No active or assigned dispatches found for today with tracking devices.');

            return 0;
        }

        $baseUrl = config('services.carmen_fleet.base_url');
        $token = config('services.carmen_fleet.token');
        $endpoint = config('services.carmen_fleet.endpoint');

        $response = Http::withToken($token)
            ->timeout(10)
            ->get($baseUrl.$endpoint);

        if ($response->failed()) {
            $this->error('Failed to fetch live vehicle locations from GPS API.');
            Log::warning('SyncDispatchesEnRoute failed to query Carmen Fleet API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return 1;
        }

        $payload = $response->json();
        $vehiclesList = $payload['vehicles'] ?? [];

        $liveVehicles = [];
        foreach ($vehiclesList as $vehicleData) {
            $deviceSn = $vehicleData['device_sn'] ?? null;
            if ($deviceSn) {
                $liveVehicles[$deviceSn] = $vehicleData;
            }
        }

        $now = Carbon::now();
        $enRouteCount = 0;
        $completedCount = 0;

        foreach ($dispatches as $dispatch) {
            $deviceSn = $dispatch->vehicle->device_sn;

            if (isset($liveVehicles[$deviceSn])) {
                $gpsData = $liveVehicles[$deviceSn];
                $speed = $gpsData['speed'] ?? 0;
                $rpm = $gpsData['rpm'] ?? 0;
                $currentLat = $gpsData['latitude'] ?? null;
                $currentLng = $gpsData['longitude'] ?? null;

                if ($dispatch->status === 'Assigned') {
                    if ($rpm > 0 && $speed > 0) {
                        $dispatch->update([
                            'status' => 'En Route',
                            'en_route_time' => $now,
                        ]);

                        $this->info("Updated dispatch ID {$dispatch->id} (Plate: {$dispatch->vehicle->plate_no}) to En Route (Speed: {$speed} km/h, RPM: {$rpm})");
                        $enRouteCount++;
                    }
                } elseif ($dispatch->status === 'En Route') {
                    if ($dispatch->to_lat !== null && $dispatch->to_lng !== null && $currentLat !== null && $currentLng !== null) {
                        $distance = $this->calculateDistance(
                            (float) $currentLat,
                            (float) $currentLng,
                            (float) $dispatch->to_lat,
                            (float) $dispatch->to_lng
                        );

                        if ($distance <= self::PROXIMITY_THRESHOLD_METERS) {
                            $dispatch->update([
                                'status' => 'Completed',
                                'complete_time' => $now,
                            ]);

                            $this->info("Updated dispatch ID {$dispatch->id} (Plate: {$dispatch->vehicle->plate_no}) to Completed. Reached destination (Distance: ".round($distance, 1).'m)');
                            $completedCount++;
                        }
                    }
                }
            }
        }

        $this->info("Finished syncing. Updated {$enRouteCount} dispatches to En Route and {$completedCount} dispatches to Completed.");

        return 0;
    }

    /**
     * Calculate geodesic distance between two points in meters (Haversine formula).
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}

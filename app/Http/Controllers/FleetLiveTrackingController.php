<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FleetLiveTrackingController extends Controller
{
    /**
     * Proxy the vendor's live vehicle positions to the frontend.
     *
     * The vendor token lives only in config/services.php (via .env) and is
     * never sent to the browser. Response is cached briefly so several
     * open dashboard tabs don't each hammer the vendor API on every poll.
     */
    public function positions()
    {
        $data = Cache::remember('fleet:live-positions', config('services.carmen_fleet.cache_ttl'), function () {
            $response = Http::withToken(config('services.carmen_fleet.token'))
                ->timeout(10)
                ->get(config('services.carmen_fleet.base_url').config('services.carmen_fleet.endpoint'));

            if ($response->failed()) {
                Log::warning('Fleet GPS API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json();
        });

        if ($data === null) {
            // Don't cache the failure itself, so the next poll retries immediately.
            Cache::forget('fleet:live-positions');

            return response()->json([
                'error' => 'Unable to reach the fleet GPS API right now.',
            ], 502);
        }

        return response()->json($data);
    }
}

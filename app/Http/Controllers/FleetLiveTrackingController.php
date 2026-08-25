<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FleetLiveTrackingController extends Controller
{
    /**
     * Proxy the vendor's live vehicle positions to the frontend.
     *
     * The vendor token lives only in config/services.php (via .env) and is
     * never sent to the browser. Response is cached briefly so several
     * open dashboard tabs don't each hammer the vendor API on every poll.
     */
    public function positions(): JsonResponse
    {
        $token = config('services.carmen_fleet.token');
        $baseUrl = config('services.carmen_fleet.base_url', 'https://apis.carmen.asia/api');
        $endpoint = config('services.carmen_fleet.endpoint', '/vehicles/locations');
        $cacheTtl = max(1, (int) config('services.carmen_fleet.cache_ttl', 6));

        if (empty($token)) {
            Log::warning('Fleet GPS API token (CARMEN_FLEET_API_TOKEN) is not configured.');

            return response()->json([
                'error' => 'Fleet GPS service is not configured. Missing CARMEN_FLEET_API_TOKEN.',
                'vehicles' => [],
            ], 503);
        }

        $url = rtrim((string) $baseUrl, '/').'/'.ltrim((string) $endpoint, '/');

        try {
            $data = Cache::remember('fleet:live-positions', $cacheTtl, function () use ($token, $url) {
                $response = Http::withToken($token)
                    ->timeout(10)
                    ->connectTimeout(5)
                    ->get($url);

                if ($response->failed()) {
                    Log::warning('Fleet GPS API request returned failure status', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return null;
                }

                return $response->json();
            });
        } catch (Throwable $e) {
            Log::error('Fleet GPS API request failed with exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $data = null;
        }

        if ($data === null) {
            // Don't cache the failure itself, so the next poll retries immediately.
            Cache::forget('fleet:live-positions');

            return response()->json([
                'error' => 'Unable to reach the fleet GPS API right now. Please verify server connectivity or API token.',
                'vehicles' => [],
            ], 502);
        }

        return response()->json($data);
    }
}

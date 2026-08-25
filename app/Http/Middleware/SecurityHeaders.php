<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * External sources used by this application:
     *
     * - Alpine.js / Livewire / Filament → require 'unsafe-inline' + 'unsafe-eval' for scripts
     * - Leaflet JS & CSS              → unpkg.com, cdnjs.cloudflare.com
     * - Leaflet tile servers           → *.tile.openstreetmap.org, *.basemaps.cartocdn.com
     * - Leaflet routing                → routing.openstreetmap.de
     * - Bunny Fonts (Vite plugin)      → fonts.bunny.net
     * - Google Fonts (fallback)        → fonts.googleapis.com, fonts.gstatic.com
     * - Vite dev server (local only)   → 127.0.0.1:5173 / localhost:5173
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // -----------------------------------------------------------------
        // HSTS – only when Laravel sees HTTPS
        // -----------------------------------------------------------------
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload',
            );
        }

        // -----------------------------------------------------------------
        // CSP – build directives
        // -----------------------------------------------------------------
        $isLocal = app()->isLocal();

        // Vite dev server origins (HMR websocket + asset serving)
        $viteDev = $isLocal
            ? ' http://127.0.0.1:5173 http://localhost:5173 ws://127.0.0.1:5173 ws://localhost:5173'
            : '';

        $csp = implode(' ', array_filter([
            // Fallback for any resource type not explicitly listed
            "default-src 'self';",

            // Scripts: Alpine.js & Livewire need inline + eval; Leaflet loaded from CDNs
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com{$viteDev};",

            // Stylesheets: Filament injects inline styles; Leaflet CSS from CDNs; Bunny & Google fonts
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://unpkg.com https://cdnjs.cloudflare.com{$viteDev};",

            // Fetch / XHR / WebSocket: Livewire polling, Leaflet routing, tile prefetch, Vite HMR
            "connect-src 'self' blob: https://nominatim.openstreetmap.org https://routing.openstreetmap.de https://unpkg.com https://*.tile.openstreetmap.org https://*.basemaps.cartocdn.com{$viteDev};",

            // Fonts: Google, Bunny, data-URIs for inline icon fonts
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net;",

            // Images: Leaflet markers from CDNs, map tiles, data-URIs, blobs for canvas export
            "img-src 'self' data: blob: https://*.tile.openstreetmap.org https://*.basemaps.cartocdn.com https://cdnjs.cloudflare.com https://unpkg.com;",

            // Web Workers: Filament / JS blobs
            "worker-src 'self' blob:;",

            // Prevent clickjacking
            "frame-ancestors 'self';",

            // Lock <base> tag
            "base-uri 'self';",

            // Only upgrade HTTP→HTTPS when actually serving over HTTPS or in production
            ($request->isSecure() || app()->isProduction()) ? 'upgrade-insecure-requests;' : null,
        ]));

        $response->headers->set('Content-Security-Policy', $csp);

        // -----------------------------------------------------------------
        // Other security headers
        // -----------------------------------------------------------------
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'same-origin');
        $response->headers->set('Permissions-Policy',
            'geolocation=(self), camera=(), microphone=(), fullscreen=(self), payment=()',
        );

        return $response;
    }
}

<x-filament-panels::page>
    <div class="min-h-full overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <header class="flex flex-col sm:flex-row sm:h-16 items-start sm:items-center justify-between border-b border-gray-200 p-4 sm:px-6 sm:py-0 gap-4 sm:gap-0 dark:border-gray-800">
            <div>
                <h1 class="text-lg font-semibold tracking-tight text-gray-950 dark:text-white">Fleet — live tracking</h1>
                <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span id="fleet_name">MIESCOR Logistic Fleet</span>
                    <span aria-hidden="true">·</span>
                    <span id="sync-status">Syncing…</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2" id="counts"></div>
        </header>

        <div class="flex flex-col md:flex-row h-auto md:h-[calc(100vh-16rem)]">
            <aside class="flex w-full md:w-72 lg:w-85 flex-col border-t md:border-t-0 md:border-r border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950 h-[400px] md:h-full order-2 md:order-1">
                <div class="px-4 pt-4 pb-2">
                    <input type="search" id="vehicle-search" placeholder="Search plate..." class="w-full rounded-lg border-gray-100 bg-white px-3 py-2 text-sm shadow-sm focus:border-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-gray-100 dark:focus:ring-gray-100">
                </div>
                <div class="flex flex-wrap gap-2 px-4 pb-4 pt-0" id="filters"></div>
                <div class="max-h-180 flex-1 overflow-y-auto px-3 pb-3" id="vehicle-list"></div>
                <div class="border-t border-gray-200 px-4 py-3 text-[11px] text-gray-500 dark:border-gray-800 dark:text-gray-500">
                    Live positions — polling the fleet GPS feed every 8s. Showing 10 at a time — scroll for more.
                </div>
            </aside>

            <div class="relative flex-none md:flex-1 w-full order-1 md:order-2 min-h-[400px] md:min-h-0 h-[400px] md:h-full">
                <div
                    id="map"
                    class="h-full w-full bg-gray-50 dark:bg-gray-950 z-0"
                    wire:ignore
                    x-data="{ _destroy: null, _initialized: false }"
                    x-init="
                        if (!_initialized) {
                            _initialized = true;
                            const waitForInit = () => {
                                if (typeof window.initFleetTracking === 'function') {
                                    _destroy = window.initFleetTracking($el, '{{ route('fleet.live-positions') }}', {
                                        tileUrl: {{ \Illuminate\Support\Js::from(config('filament-pinpoint.leaflet.tile_url')) }},
                                        tileUrlDark: {{ \Illuminate\Support\Js::from(config('filament-pinpoint.leaflet.tile_url_dark')) }},
                                        tileAttribution: {{ \Illuminate\Support\Js::from(config('filament-pinpoint.leaflet.tile_attribution')) }}
                                    });
                                } else {
                                    setTimeout(waitForInit, 100);
                                }
                            };
                            waitForInit();
                        }
                    "
                    x-on:livewire:navigating.window="_destroy?.()"
                ></div>
                <div class="hidden absolute bottom-5 left-5 z-[500] lg:flex flex-wrap items-center justify-start gap-3 rounded-2xl border border-gray-200 bg-white/95 backdrop-blur-sm px-4 py-3 text-xs text-gray-700 shadow-md dark:border-gray-800 dark:bg-gray-900/95 dark:text-gray-300">
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full bg-green-500"></span>Moving</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full bg-gray-500"></span>Stationary</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full bg-red-600"></span>Offline</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .vehicle-marker {
            width: 30px;
            height: 30px;
            border-radius: 9999px;
            background: #ffffff;
            border: 2px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.4s ease, color 0.4s ease, box-shadow 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.35);
        }
        .vehicle-marker svg { width: 18px; height: 18px; }
        .vehicle-marker.moving { color: #16a34a; }
        .vehicle-marker.stationary { color: #6b7280; }
        .vehicle-marker.offline { color: #dc2626; opacity: 0.6; }
        .vehicle-marker.is-selected { box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.35), 0 1px 3px rgba(0, 0, 0, 0.35); }

        .dark .vehicle-marker { background: #111827; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.6); }
        .dark .vehicle-marker.is-selected { box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.35), 0 1px 3px rgba(0, 0, 0, 0.6); }

        .dark .leaflet-popup-content-wrapper,
        .dark .leaflet-popup-tip { background: #111827; color: #e5e7eb; }
        .dark .leaflet-popup-close-button { color: #9ca3af !important; }
        .dark .leaflet-bar a { background-color: #111827; color: #e5e7eb; border-color: #374151; }
        .dark .leaflet-bar a:hover { background-color: #1f2937; }
        .dark .leaflet-control-attribution { background: rgba(17, 24, 39, 0.8) !important; color: #9ca3af !important; }
        .dark .leaflet-control-attribution a { color: #9ca3af !important; }
    </style>
</x-filament-panels::page>

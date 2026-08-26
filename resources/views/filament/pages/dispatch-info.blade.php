<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        {{-- Route Information --}}
        <x-filament::section>
            <x-slot name="heading">
                Route Information
            </x-slot>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-ticket" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>Ticket No.:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getTicketNo() }}</span>
                </div>
                
                <div class="flex justify-between items-start">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-map-pin" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>From:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getFromLocation() }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-map-pin" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>To:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getToLocation() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-clock" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>Departure:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getDepartureTime() }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-information-circle" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>Status:</span>
                    </div>
                    <x-filament::badge :color="$this->getStatusColor()">
                        {{ $this->getStatus() }}
                    </x-filament::badge>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-play" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>En Route:</span>
                    </div>
                    <x-filament::badge color="info">
                        {{ $this->getEnrouteTime() }}
                    </x-filament::badge>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-check-circle" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>Completed:</span>
                    </div>
                    <x-filament::badge color="success">
                        {{ $this->getCompletedTime() }}
                    </x-filament::badge>
                </div>
            </div>
        </x-filament::section>

        {{-- Trip Information --}}
        <x-filament::section>
            <x-slot name="heading">
                Trip Information
            </x-slot>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-user" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>Driver:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getDriverName() }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-phone" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>Contact Number:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getContactNumber() }}</span>
                </div>
                
                <hr class="my-6 w-full border-gray-200 dark:border-gray-700" />

                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-truck" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" />
                        <span>Plate Number:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getPlateNumber() }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 shrink-0">
                        <span>Model:</span>
                    </div>
                    <span class="font-normal text-right ml-4">{{ $this->getVehicleModel() }}</span>
                </div>

                {{-- Cancellation --}}
                @if($this->getCancellationRecord())
                    <div class="mt-4">
                        <div class="flex items-center text-red-600 font-normal mb-2">
                            <x-filament::icon icon="heroicon-o-x-circle" class="h-5 w-5 mr-2" />
                            <span>Cancellation Reason:</span>
                        </div>
                        <div class="p-3 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-lg text-sm text-right">
                            {{ $this->getCancellationRecord() }}
                        </div>
                    </div>
                @endif
            </div>
        </x-filament::section>
    </div>


    {{-- Map UI --}}
    <x-filament::section class="sticky top-5 ">
        <x-slot name="heading">
            Route Map
        </x-slot>

        <div id="map" wire:ignore class="w-full h-[500px] rounded-xl"></div>
    </x-filament::section>

    {{-- Toll Points --}}
    @php $tollPoints = $this->getTollPoints(); @endphp
    @if(count($tollPoints) > 0)
        <x-filament::section class="mt-6">
            <x-slot name="heading">
                Toll Points
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-2 px-3 text-gray-500">#</th>
                            <th class="text-left py-2 px-3 text-gray-500">Name</th>
                            <th class="text-left py-2 px-3 text-gray-500">Highway</th>
                            <th class="text-right py-2 px-3 text-gray-500">Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tollPoints as $index => $point)
                            <tr class="toll-point-row border-b border-gray-100 dark:border-gray-800"
                                data-lat="{{ $point['coordinates'][0] ?? '' }}"
                                data-lng="{{ $point['coordinates'][1] ?? '' }}">
                                <td class="py-2 px-3">{{ $index + 1 }}</td>
                                <td class="py-2 px-3 font-normal">{{ $point['name'] ?? '' }}</td>
                                <td class="py-2 px-3">{{ $point['highway'] ?? '' }}</td>
                                <td class="py-2 px-3 text-right font-normal">₱ {{ number_format($point['fee'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.css" />
<style>
    /* Light Mode (Default) */
    .leaflet-routing-container {
        background-color: white;
        color: #374151;
    }

    .leaflet-routing-alt {
        background-color: white;
        color: #374151;
    }

    .leaflet-routing-alt table {
        background-color: white;
        color: #374151;
    }

    .leaflet-routing-geocoders {
        background-color: #f9fafb;
    }

    .leaflet-routing-geocoder input {
        background-color: white;
        color: #374151;
        border-color: #d1d5db;
    }

    /* Dark Mode Styles */
    .dark .leaflet-routing-container,
    html.dark .leaflet-routing-container {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
        border-color: #374151 !important;
    }

    .dark .leaflet-routing-alt,
    html.dark .leaflet-routing-alt {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
    }

    .dark .leaflet-routing-alt table,
    html.dark .leaflet-routing-alt table {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
    }

    .dark .leaflet-routing-alt tr,
    html.dark .leaflet-routing-alt tr {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
    }

    .dark .leaflet-routing-alt td,
    html.dark .leaflet-routing-alt td {
        color: #e5e7eb !important;
    }

    .dark .leaflet-routing-geocoders,
    html.dark .leaflet-routing-geocoders {
        background-color: #111827 !important;
    }

    .dark .leaflet-routing-geocoder input,
    html.dark .leaflet-routing-geocoder input {
        background-color: #374151 !important;
        color: #e5e7eb !important;
        border-color: #4b5563 !important;
    }

    .dark .leaflet-routing-geocoder input::placeholder,
    html.dark .leaflet-routing-geocoder input::placeholder {
        color: #9ca3af !important;
    }

    /* Alternative route styling for dark mode */
    .dark .leaflet-routing-alt-minimized,
    html.dark .leaflet-routing-alt-minimized {
        background-color: #374151 !important;
        color: #e5e7eb !important;
    }

    /* Routing instructions */
    .dark .leaflet-routing-instruction,
    html.dark .leaflet-routing-instruction {
        color: #e5e7eb !important;
    }

    /* Routing error messages */
    .dark .leaflet-routing-error,
    html.dark .leaflet-routing-error {
        background-color: #991b1b !important;
        color: #fecaca !important;
    }

    /* Collapse button */
    .dark .leaflet-routing-collapse-btn,
    html.dark .leaflet-routing-collapse-btn {
        background-color: #374151 !important;
        color: #e5e7eb !important;
    }

    .dark .leaflet-routing-collapse-btn:hover,
    html.dark .leaflet-routing-collapse-btn:hover {
        background-color: #4b5563 !important;
    }

    /* Icon styling for dark mode */
    .dark .leaflet-routing-icon,
    html.dark .leaflet-routing-icon {
        filter: invert(1);
    }

    /* Popup styling for dark mode */
    .dark .leaflet-popup-content-wrapper,
    html.dark .leaflet-popup-content-wrapper {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
    }

    .dark .leaflet-popup-tip,
    html.dark .leaflet-popup-tip {
        background-color: #1f2937 !important;
    }

    /* Control buttons dark mode */
    .dark .leaflet-bar,
    html.dark .leaflet-bar {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }

    .dark .leaflet-bar a,
    html.dark .leaflet-bar a {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
        border-color: #374151 !important;
    }

    .dark .leaflet-bar a:hover,
    html.dark .leaflet-bar a:hover {
        background-color: #374151 !important;
    }

    /* Ensure toll marker popups also get dark mode */
    .dark .leaflet-popup-content,
    html.dark .leaflet-popup-content {
        color: #e5e7eb !important;
    }

    /* Toll Point Marker Styles */
    .toll-marker {
        background-color: #3b82f6;
        color: white;
        border-radius: 50%;
        text-align: center;
        font-weight: bold;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px solid white;
        box-shadow: 0 0 5px rgba(0,0,0,0.3);
    }

    .toll-point-row {
        cursor: pointer;
    }

    .toll-point-row:hover {
        background-color: #f3f4f6;
    }

    /* Leaflet image CSS reset for Tailwind */
    .leaflet-container img,
    .leaflet-container .leaflet-tile,
    .leaflet-container .leaflet-marker-icon,
    .leaflet-container .leaflet-marker-shadow,
    .leaflet-tile-container img {
        max-width: none !important;
        max-height: none !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        filter: none !important;
        vertical-align: baseline !important;
    }

    /* Ensure the routing machine table doesn't get distorted */
    .leaflet-routing-alt table img {
        max-width: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    /**
     * Fleet Management - Route Visualization System
     * ---------------------------------------------
     */

    // Store map instance globally so we can access it later
    window.fleetMap = null;
    window.currentTileLayer = null;

    /**
     * Dynamically load dependencies in strict order to prevent Livewire SPA race conditions.
     */
    function loadMapDependencies() {
        return new Promise((resolve) => {
            // If already fully loaded, resolve immediately
            if (typeof L !== 'undefined' && typeof L.Routing !== 'undefined') {
                return resolve();
            }

            const loadScript = (src) => {
                return new Promise((res) => {
                    if (document.querySelector(`script[src="${src}"]`)) {
                        return res();
                    }
                    const script = document.createElement('script');
                    script.src = src;
                    script.onload = res;
                    script.onerror = res; // Resolve anyway so chain continues (fallback logic handles errors)
                    document.head.appendChild(script);
                });
            };

            // Load Leaflet first, then Routing Machine
            loadScript('https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js')
                .then(() => loadScript('https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js'))
                .then(() => resolve());
        });
    }

    // Wait for everything to be ready before fetching scripts and initializing
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            loadMapDependencies().then(initMap);
        });
    } else {
        // DOM is already ready
        setTimeout(() => {
            loadMapDependencies().then(initMap);
        }, 100);
    }

    /**
     * Detect dark mode
     */
    function isDarkMode() {
        return document.documentElement.classList.contains('dark') ||
               document.body.classList.contains('dark-mode') ||
               (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }

    /**
     * Apply appropriate tile layer based on theme
     */
    function applyTileLayer(map) {
        const darkMode = isDarkMode();

        // Remove existing tile layer if present
        if (window.currentTileLayer) {
            map.removeLayer(window.currentTileLayer);
        }

        let errorCount = 0;

        const tileUrl = darkMode 
            ? "{{ config('filament-pinpoint.leaflet.tile_url_dark') ?: config('filament-pinpoint.leaflet.tile_url', 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png') }}"
            : "{{ config('filament-pinpoint.leaflet.tile_url', 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png') }}";
        
        const attribution = "{!! addslashes(config('filament-pinpoint.leaflet.tile_attribution', '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, &copy; <a href=\"https://carto.com/attributions\">CARTO</a>')) !!}";

        window.currentTileLayer = L.tileLayer(tileUrl, {
            attribution: attribution,
            maxZoom: 19,
            subdomains: 'abcd',
            // Disable detectRetina to prevent 404s on providers that don't support it
            detectRetina: false, 
        }).addTo(map);

        window.currentTileLayer.on('tileerror', function() {
            errorCount++;
            if (errorCount > 4) {
                console.log('Main tile provider failing, switching to OpenStreetMap fallback');
                map.removeLayer(window.currentTileLayer);
                window.currentTileLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);
            }
        });

        return window.currentTileLayer;
    }

    /**
     * Watch for theme changes
     */
    function watchThemeChanges(map) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    console.log('Theme changed, updating map tiles');
                    applyTileLayer(map);
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });

        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                console.log('System theme changed, updating map tiles');
                applyTileLayer(map);
            });
        }
    }

    // Livewire-specific event listeners
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.removed', ({ el }) => {
            if (el.classList && (el.classList.contains('fi-modal') || el.hasAttribute('x-data'))) {
                setTimeout(() => {
                    if (window.fleetMap) {
                        window.fleetMap.invalidateSize();
                        console.log('Map refreshed after Livewire modal close');
                    }
                }, 150);
            }
        });

        Livewire.hook('commit', ({ component, commit, respond }) => {
            setTimeout(() => {
                if (window.fleetMap) {
                    window.fleetMap.invalidateSize();
                    console.log('Map refreshed after Livewire update');
                }
            }, 150);
        });
    });

    // Alpine.js event listener
    document.addEventListener('alpine:initialized', () => {
        window.addEventListener('close-modal', () => {
            setTimeout(() => {
                if (window.fleetMap) {
                    window.fleetMap.invalidateSize();
                    console.log('Map refreshed after Alpine modal close');
                }
            }, 150);
        });
    });

    // Fallback: MutationObserver for any DOM changes
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new MutationObserver(function(mutations) {
            let shouldRefresh = false;

            mutations.forEach(function(mutation) {
                if (mutation.removedNodes.length > 0) {
                    mutation.removedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && node.classList) {
                            if (node.classList.contains('fi-modal') ||
                                node.classList.contains('fi-modal-window') ||
                                node.hasAttribute('x-show')) {
                                shouldRefresh = true;
                            }
                        }
                    });
                }
            });

            if (shouldRefresh) {
                setTimeout(function() {
                    if (window.fleetMap) {
                        window.fleetMap.invalidateSize();
                        console.log('Map refreshed after DOM mutation');
                    }
                }, 150);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });

    window.addEventListener('close-modal', () => {
        setTimeout(() => {
            if (window.fleetMap) {
                window.fleetMap.invalidateSize();
                console.log('Map refreshed via close-modal event');
            }
        }, 150);
    });

    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && window.fleetMap) {
            setTimeout(function() {
                window.fleetMap.invalidateSize();
                console.log('Map refreshed after visibility change');
            }, 150);
        }
    });

    function initMap() {
        const mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.error('Map container not found!');
            return;
        }

        // Avoid re-initializing if map already exists
        if (window.fleetMap) {
            return;
        }

        // Force the container to be visible and sized
        mapContainer.style.display = 'block';
        mapContainer.style.height = '500px';
        mapContainer.style.width = '100%';

        setTimeout(function() {
            try {
                const map = L.map('map', {
                    preferCanvas: true,
                    renderer: L.canvas()
                }).setView([14.5995, 120.9842], 13);

                window.fleetMap = map;

                applyTileLayer(map);
                watchThemeChanges(map);

                map.whenReady(function() {
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 100);
                });

                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden && window.fleetMap) {
                        setTimeout(function() {
                            window.fleetMap.invalidateSize();
                        }, 100);
                    }
                });

                // Custom icons for start and end points
                const startIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                const endIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                // Custom toll gate icon
                function createTollIcon(price) {
                    return L.divIcon({
                        className: 'toll-marker',
                        html: '₱',
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    });
                }

                // Get locations from PHP variables
                const fromLocation = "{{ $this->getFromLocation() }}";
                const toLocation = "{{ $this->getToLocation() }}";
                const fromLocationRaw = "{{ $this->getFromLocationRaw() }}";
                const toLocationRaw = "{{ $this->getToLocationRaw() }}";

                // Check if we have server-side coordinates
                let serverFromCoords = @json($this->getFromCoordinates());
                let serverToCoords = @json($this->getToCoordinates());

                // Get toll points
                const tollPoints = @json($this->getTollPoints());

                // Create a group for toll markers
                const tollMarkers = L.layerGroup().addTo(map);

                function safeSetTextContent(elementId, text) {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.textContent = text;
                    }
                }

                function addTollPointsToMap() {
                    if (!tollPoints || !Array.isArray(tollPoints)) return;

                    tollMarkers.clearLayers();

                    tollPoints.forEach(function(point) {
                        if (point.coordinates && point.coordinates.length === 2) {
                            try {
                                const tollIcon = createTollIcon(point.fee);
                                const marker = L.marker([point.coordinates[0], point.coordinates[1]], {
                                    icon: tollIcon,
                                    zIndexOffset: 1000
                                }).addTo(tollMarkers);

                                marker.bindPopup(`
                                    <div class="font-normal">${point.name}</div>
                                    <div>${point.highway}</div>
                                    <div class="font-bold mt-1">Fee: ₱${point.fee.toFixed(2)}</div>
                                `);
                            } catch (error) {
                                console.error('Error adding toll marker:', error);
                            }
                        }
                    });
                }

                // Event listeners for toll point rows
                setTimeout(function() {
                    const tollRows = document.querySelectorAll('.toll-point-row');
                    if (tollRows && tollRows.length > 0) {
                        tollRows.forEach(function(row) {
                            row.addEventListener('click', function() {
                                const lat = parseFloat(this.getAttribute('data-lat'));
                                const lng = parseFloat(this.getAttribute('data-lng'));

                                if (!isNaN(lat) && !isNaN(lng)) {
                                    map.flyTo([lat, lng], 15);

                                    tollMarkers.eachLayer(function(layer) {
                                        const markerLatLng = layer.getLatLng();
                                        if (markerLatLng.lat === lat && markerLatLng.lng === lng) {
                                            layer.openPopup();
                                        }
                                    });
                                }
                            });
                        });
                    }
                }, 1000);

                function updateRouteDisplay() {
                    // Clear existing route
                    if (window.routeControl) {
                        try {
                            map.removeControl(window.routeControl);
                            window.routeControl = null;
                        } catch (e) {
                            console.warn('Error removing route control:', e);
                        }
                    }

                    // Clear existing markers (except toll markers)
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.Marker && !tollMarkers.hasLayer(layer)) {
                            map.removeLayer(layer);
                        }
                    });



                    if (serverFromCoords && serverToCoords) {
                        try {
                            // Add start and end markers
                            L.marker(serverFromCoords, {
                                icon: startIcon
                            }).addTo(map).bindPopup('From: ' + fromLocation);

                            L.marker(serverToCoords, {
                                icon: endIcon
                            }).addTo(map).bindPopup('To: ' + toLocation);

                            if (typeof L.Routing === 'undefined') {
                                console.error('Leaflet Routing Machine failed to load.');
                                // Fallback: Draw a straight yellow line
                                const fallbackPolyline = L.polyline([serverFromCoords, serverToCoords], {
                                    color: '#eab308', 
                                    weight: 6, 
                                    dashArray: '10, 10'
                                }).addTo(map);
                                map.fitBounds(fallbackPolyline.getBounds(), { padding: [20, 20], maxZoom: 16 });
                                return; // Stop executing L.Routing code
                            }

                            // Create routing control
                            window.routeControl = L.Routing.control({
                                waypoints: [
                                    L.latLng(serverFromCoords[0], serverFromCoords[1]),
                                    L.latLng(serverToCoords[0], serverToCoords[1])
                                ],
                                routeWhileDragging: false,
                                showAlternatives: true,
                                lineOptions: {
                                    styles: [
                                        {color: '#eab308', opacity: 0.9, weight: 6}
                                    ]
                                },
                                altLineOptions: {
                                    styles: [
                                        {color: 'black', opacity: 0.15, weight: 9},
                                        {color: 'white', opacity: 0.8, weight: 6},
                                        {color: 'blue', opacity: 0.5, weight: 2}
                                    ]
                                },
                                createMarker: function() { return null; },
                                router: new L.Routing.OSRMv1({
                                    serviceUrl: '{{ config('services.routing.osrm_url', 'https://routing.openstreetmap.de/routed-car/route/v1') }}',
                                    profile: 'driving',
                                    suppressDemoServerWarning: true,
                                    timeout: 30 * 1000
                                }),
                                fitSelectedRoutes: false
                            });

                            if (map.getContainer()) {
                                window.routeControl.addTo(map);

                                setTimeout(function() {
                                    if (map && map.getContainer()) {
                                        map.invalidateSize();
                                    }
                                }, 200);
                            }

                            if (window.routeControl) {
                                window.routeControl.on('routesfound', function(e) {
                                    try {
                                        const routes = e.routes;
                                        if (routes && routes.length > 0) {
                                            const route = routes[0];

                                            const distanceInKm = (route.summary.totalDistance / 1000).toFixed(2);
                                            safeSetTextContent('route-distance', distanceInKm + ' km');

                                            const durationInMin = Math.round(route.summary.totalTime / 60);
                                            let durationText = '';
                                            if (durationInMin >= 60) {
                                                const hours = Math.floor(durationInMin / 60);
                                                const mins = durationInMin % 60;
                                                durationText = hours + ' h ' + mins + ' min';
                                            } else {
                                                durationText = durationInMin + ' min';
                                            }
                                            safeSetTextContent('route-duration', durationText);

                                            if (route.coordinates && route.coordinates.length > 0) {
                                                const routeBounds = L.latLngBounds(route.coordinates);
                                                map.fitBounds(routeBounds, { padding: [20, 20], maxZoom: 16 });
                                            }
                                        }
                                    } catch (error) {
                                        console.error('Error in routesfound:', error);
                                        safeSetTextContent('route-distance', 'N/A');
                                        safeSetTextContent('route-duration', 'N/A');
                                    }
                                });

                                window.routeControl.on('routingerror', function(e) {
                                    console.error('Routing error:', e);
                                    
                                    // Fallback: Draw a straight yellow line
                                    const fallbackPolyline = L.polyline([serverFromCoords, serverToCoords], {
                                        color: '#eab308', 
                                        weight: 6, 
                                        dashArray: '10, 10'
                                    }).addTo(map);
                                    
                                    map.fitBounds(fallbackPolyline.getBounds(), { padding: [20, 20], maxZoom: 16 });
                                    
                                    safeSetTextContent('route-distance', 'N/A');
                                    safeSetTextContent('route-duration', 'N/A');
                                });
                            }

                            // Add toll points
                            addTollPointsToMap();

                        } catch (error) {
                            console.error('Error creating route:', error);
                        }
                    } else {
                        // Missing coordinates
                        const errorEl = document.getElementById('route-error');
                        if (errorEl) {
                            errorEl.classList.remove('hidden');
                            const errorMsgEl = document.getElementById('route-error-message');
                            if (errorMsgEl) {
                                errorMsgEl.textContent = 'Could not create route: Missing coordinates';
                            }
                        }
                        safeSetTextContent('route-distance', 'N/A');
                        safeSetTextContent('route-duration', 'N/A');

                        if (serverFromCoords) {
                            map.setView(serverFromCoords, 13);
                            L.marker(serverFromCoords, { icon: startIcon })
                                .addTo(map).bindPopup('From: ' + fromLocation);
                        } else if (serverToCoords) {
                            map.setView(serverToCoords, 13);
                            L.marker(serverToCoords, { icon: endIcon })
                                .addTo(map).bindPopup('To: ' + toLocation);
                        }
                    }
                }

                // Geocode locations
                function geocodeLocation(locationName, serverCoords) {
                    if (serverCoords) {
                        return Promise.resolve(serverCoords);
                    }

                    return fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationName)}&limit=1`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                            }
                            throw new Error(`Could not geocode location: ${locationName}`);
                        });
                }

                // Initialize route
                Promise.all([
                    geocodeLocation(fromLocationRaw, serverFromCoords),
                    geocodeLocation(toLocationRaw, serverToCoords)
                ]).then(([fromCoords, toCoords]) => {
                    serverFromCoords = fromCoords;
                    serverToCoords = toCoords;
                    updateRouteDisplay();
                }).catch(error => {
                    console.error('Error geocoding:', error);
                    const errorEl = document.getElementById('route-error');
                    if (errorEl) {
                        errorEl.classList.remove('hidden');
                        const errorMsgEl = document.getElementById('route-error-message');
                        if (errorMsgEl) {
                            errorMsgEl.textContent = 'Could not create route: ' + error.message;
                        }
                    }
                    safeSetTextContent('route-distance', 'N/A');
                    safeSetTextContent('route-duration', 'N/A');
                });

            } catch (error) {
                console.error('Error initializing map:', error);
                const errorEl = document.getElementById('route-error');
                if (errorEl) {
                    errorEl.classList.remove('hidden');
                    const errorMsgEl = document.getElementById('route-error-message');
                    if (errorMsgEl) {
                        errorMsgEl.textContent = 'Failed to initialize map: ' + error.message;
                    }
                }
            }
        }, 300);
    }

    // Clean up map on Livewire navigation
    document.addEventListener('livewire:navigating', function() {
        if (window.fleetMap) {
            window.fleetMap.remove();
            window.fleetMap = null;
            window.currentTileLayer = null;
            if (window.routeControl) {
                window.routeControl = null;
            }
        }
    });
</script>
@endpush

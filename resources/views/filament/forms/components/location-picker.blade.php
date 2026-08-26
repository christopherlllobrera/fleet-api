@once
    <link
        id="location-picker-leaflet-css"
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"
    />
@endonce

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $statePath         = $getStatePath();
        $defaultLat        = $getDefaultLat();
        $defaultLng        = $getDefaultLng();
        $defaultZoom       = $getDefaultZoom();
        $height            = max(240, (int) ($getHeight() ?: 400));
        $isDraggable       = $isDraggable();
        $isSearchable      = $isSearchable();
        $latField          = $getLatField();
        $lngField          = $getLngField();
        $radiusField       = $getRadiusField();
        $addressField      = $getAddressField();
        $shortAddressField = $getShortAddressField();
        $streetField       = $getStreetField();
        $streetNumberField = $getStreetNumberField();
        $provinceField     = $getProvinceField();
        $villageField      = $getVillageField();
        $cityField         = $getCityField();
        $districtField     = $getDistrictField();
        $postalCodeField   = $getPostalCodeField();
        $countryField      = $getCountryField();
        $tileUrl           = $getTileUrl();
        $tileUrlDark       = $getTileUrlDark();
        $tileAttribution   = $getTileAttribution();
        $nominatimUrl      = $getNominatimUrl();
        $countryCodes      = $getCountryCodes();

        $state          = $getState();
        $currentLat     = $state['lat'] ?? $defaultLat;
        $currentLng     = $state['lng'] ?? $defaultLng;
        $currentRadius  = $state['radius'] ?? $getDefaultRadius();
        $currentAddress = $state['address'] ?? '';
    @endphp

    <div
        x-data="filamentLocationPicker({
            statePath: @js($statePath),
            currentLat: @js($currentLat),
            currentLng: @js($currentLng),
            currentRadius: @js($currentRadius),
            currentAddress: @js($currentAddress),
            defaultLat: @js($defaultLat),
            defaultLng: @js($defaultLng),
            defaultZoom: @js($defaultZoom),
            isDraggable: @js($isDraggable),
            isSearchable: @js($isSearchable),
            latField: @js($latField),
            lngField: @js($lngField),
            radiusField: @js($radiusField),
            addressField: @js($addressField),
            shortAddressField: @js($shortAddressField),
            streetField: @js($streetField),
            streetNumberField: @js($streetNumberField),
            provinceField: @js($provinceField),
            cityField: @js($cityField),
            districtField: @js($districtField),
            postalCodeField: @js($postalCodeField),
            countryField: @js($countryField),
            villageField: @js($villageField),
            tileUrl: @js($tileUrl),
            tileUrlDark: @js($tileUrlDark),
            tileAttribution: @js($tileAttribution),
            nominatimUrl: @js($nominatimUrl),
            countryCodes: @js($countryCodes)
        })"
        class="fi-fo-location-picker space-y-2"
    >
        {{-- Search Input & Autocomplete --}}
        @if ($isSearchable)
            <div class="relative w-full mb-3" @click.outside="showDropdown = false">
                <div class="relative flex items-center">
                    {{-- Search Icon --}}
                    <div class="pointer-events-none absolute left-3.5 flex items-center text-gray-400 dark:text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>

                    {{-- Search Field --}}
                    <input
                        type="text"
                        x-ref="searchInput"
                        x-model="address"
                        @input="onSearchInput($event.target.value)"
                        @focus="searchResults.length > 0 && (showDropdown = true)"
                        @keydown.escape="showDropdown = false"
                        placeholder="Search for a location..."
                        class="location-search-input"
                    />

                    {{-- Search Loading Indicator --}}
                    <div x-show="isSearching" class="pointer-events-none absolute right-3.5 flex items-center text-gray-400">
                        <svg class="h-4 w-4 animate-spin text-primary-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Search Results Dropdown (3-5 rows) --}}
                <div
                    x-show="showDropdown && searchResults.length > 0"
                    x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="location-search-dropdown"
                >
                    <template x-for="(result, index) in searchResults" :key="index">
                        <button
                            type="button"
                            @click="selectSearchResult(result)"
                            class="location-search-item"
                        >
                            <svg class="h-3.5 w-3.5 flex-shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <span class="location-search-item-text" x-text="result.display_name"></span>
                        </button>
                    </template>
                </div>
            </div>
        @endif

        {{-- Map Container --}}
        <div
            wire:ignore
            class="location-map-wrapper relative rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden shadow-sm"
            style="height: {{ $height }}px; min-height: {{ $height }}px; max-height: {{ $height }}px;"
        >
            <div
                x-ref="map"
                class="location-picker-map bg-gray-100 dark:bg-gray-800"
                style="height: 100%; min-height: {{ $height }}px; max-height: 100%; width: 100%; display: block;"
            >
                {{-- Loading Spinner Placeholder --}}
                <div x-show="!isMapLoaded" class="flex h-full w-full items-center justify-center bg-gray-50 dark:bg-gray-800/80">
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <svg class="h-5 w-5 animate-spin text-primary-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Loading map...</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Helper Text --}}
        @if ($isDraggable)
            <p style="font-size: 12px; margin-top: 8px; display: flex; align-items: center; gap: 6px;" class="text-gray-500 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
                <span>Click on the map or drag the marker to set location.</span>
            </p>
        @endif
        @if ($radiusField)
            <p style="font-size: 12px; margin-top: 4px; display: flex; align-items: center; gap: 6px;" class="text-gray-500 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Drag the circle edge to adjust radius.</span>
            </p>
        @endif

        {{-- "Use My Location" Button --}}
        <div style="margin-top: 8px;">
            <button
                type="button"
                @click="getCurrentLocation()"
                :disabled="isLocating"
                class="location-btn"
                title="Use my location"
            >
                <template x-if="!isLocating">
                    <svg class="h-4 w-4 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </template>
                <template x-if="isLocating">
                    <svg class="h-4 w-4 animate-spin text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </template>
                <span x-text="isLocating ? 'Locating...' : 'Use my location'"></span>
            </button>
        </div>
    </div>

    <script>
        if (typeof window.filamentLocationPickerRegistered === 'undefined') {
            window.filamentLocationPickerRegistered = true;

            const initLocationPickerAlpine = () => {
                if (!window.Alpine) return;

                window.Alpine.data('filamentLocationPicker', (config) => ({
                    map: null,
                    marker: null,
                    circle: null,
                    radiusHandle: null,
                    tileLayer: null,
                    themeObserver: null,
                    resizeObserver: null,
                    intersectionObserver: null,

                    lat: parseFloat(config.currentLat) || config.defaultLat,
                    lng: parseFloat(config.currentLng) || config.defaultLng,
                    radius: parseInt(config.currentRadius) || 0,
                    address: config.currentAddress || '',
                    defaultLat: config.defaultLat,
                    defaultLng: config.defaultLng,
                    defaultZoom: config.defaultZoom,
                    isDraggable: config.isDraggable,
                    isSearchable: config.isSearchable,
                    statePath: config.statePath,
                    latField: config.latField,
                    lngField: config.lngField,
                    radiusField: config.radiusField,
                    addressField: config.addressField,
                    shortAddressField: config.shortAddressField,
                    streetField: config.streetField,
                    streetNumberField: config.streetNumberField,
                    provinceField: config.provinceField,
                    cityField: config.cityField,
                    districtField: config.districtField,
                    postalCodeField: config.postalCodeField,
                    countryField: config.countryField,
                    villageField: config.villageField,
                    tileUrl: config.tileUrl,
                    tileUrlDark: config.tileUrlDark,
                    tileAttribution: config.tileAttribution,
                    nominatimUrl: config.nominatimUrl,
                    countryCodes: config.countryCodes || 'ph',

                    isMapLoaded: false,
                    isLocating: false,
                    isSearching: false,
                    searchResults: [],
                    showDropdown: false,
                    searchTimeout: null,
                    containerId: 'loc-map-' + Math.random().toString(36).substring(2, 9),

                    getFieldPath(fieldName) {
                        if (!fieldName) return null;
                        const lastDotIndex = this.statePath.lastIndexOf('.');
                        const basePath = lastDotIndex > -1 ? this.statePath.substring(0, lastDotIndex + 1) : 'data.';
                        return basePath + fieldName;
                    },

                    init() {
                        this.loadExistingCoordinates();
                        this.loadLeaflet();
                    },

                    destroy() {
                        this.resizeObserver?.disconnect();
                        this.intersectionObserver?.disconnect();
                        this.themeObserver?.disconnect();
                        if (this.map) {
                            this.map.remove();
                            this.map = null;
                        }
                    },

                    loadExistingCoordinates() {
                        const latPath = this.getFieldPath(this.latField);
                        const lngPath = this.getFieldPath(this.lngField);
                        const radiusPath = this.getFieldPath(this.radiusField);
                        const addressPath = this.getFieldPath(this.addressField);

                        if (latPath && lngPath) {
                            const existingLat = this.$wire?.get(latPath);
                            const existingLng = this.$wire?.get(lngPath);
                            if (existingLat && existingLng && !isNaN(parseFloat(existingLat)) && !isNaN(parseFloat(existingLng))) {
                                this.lat = parseFloat(existingLat);
                                this.lng = parseFloat(existingLng);
                            }
                        }
                        if (radiusPath) {
                            const existingRadius = this.$wire?.get(radiusPath);
                            if (existingRadius && !isNaN(parseInt(existingRadius))) {
                                this.radius = parseInt(existingRadius);
                            }
                        }
                        if (addressPath) {
                            const existingAddress = this.$wire?.get(addressPath);
                            if (existingAddress) {
                                this.address = existingAddress;
                            }
                        }
                    },

                    loadLeaflet() {
                        if (window.L) {
                            this.$nextTick(() => this.initMap());
                            return;
                        }

                        // Use Promise.all to explicitly wait for BOTH CSS and JS to finish downloading
                        if (!window.leafletPromise) {
                            window.leafletPromise = Promise.all([
                                new Promise((resolve) => {
                                    if (document.getElementById('leaflet-css')) return resolve();
                                    const link = document.createElement('link');
                                    link.id = 'leaflet-css';
                                    link.rel = 'stylesheet';
                                    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                                    link.onload = resolve; // Wait until CSS is fully applied
                                    link.onerror = resolve;
                                    document.head.appendChild(link);
                                }),
                                new Promise((resolve) => {
                                    if (document.getElementById('leaflet-js')) return resolve();
                                    const script = document.createElement('script');
                                    script.id = 'leaflet-js';
                                    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                                    script.onload = resolve; // Wait until JS is fully parsed
                                    script.onerror = resolve;
                                    document.head.appendChild(script);
                                })
                            ]);
                        }

                        window.leafletPromise.then(() => {
                            // Add a tiny layout frame delay for safety before booting map
                            setTimeout(() => this.$nextTick(() => this.initMap()), 50);
                        });
                    },

                    isDarkMode() {
                        return document.documentElement.classList.contains('dark');
                    },

                    getTileUrl() {
                        // REMOVED {r} to prevent 404 tile errors
                        const defaultLight = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png';
                        const defaultDark = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png';

                        if (this.isDarkMode()) {
                            return (this.tileUrlDark ? this.tileUrlDark.replace('{r}', '') : defaultDark);
                        }
                        return (this.tileUrl ? this.tileUrl.replace('{r}', '') : defaultLight);
                    },

                    createMarkerIcon() {
                        const svgPin = `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 42" width="32" height="42">
                                <defs>
                                    <filter id="loc-pin-shadow" x="-20%" y="-10%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#000000" flood-opacity="0.3"/>
                                    </filter>
                                </defs>
                                <path d="M16 0C7.16 0 0 7.16 0 16c0 10.8 14.4 24.6 15 25.2.3.3.7.5 1 .5s.7-.2 1-.5C17.6 40.6 32 26.8 32 16 32 7.16 24.84 0 16 0z" fill="#ef4444" filter="url(#loc-pin-shadow)"/>
                                <circle cx="16" cy="16" r="6.5" fill="#ffffff"/>
                                <circle cx="16" cy="16" r="3.5" fill="#ef4444"/>
                            </svg>
                        `;

                        return L.divIcon({
                            className: 'location-custom-marker',
                            html: svgPin,
                            iconSize: [32, 42],
                            iconAnchor: [16, 42],
                            popupAnchor: [0, -42],
                        });
                    },

                    initMap() {
                        const mapElement = this.$refs.map;
                        if (!mapElement) return;
                        const containerElement = mapElement.closest('.location-map-wrapper');
                        if (!containerElement) return;

                        // Do not initialize Leaflet while parent modal/tab/conditional block is hidden (0 width or height)
                        if (mapElement.clientWidth === 0 || mapElement.clientHeight === 0) {
                            requestAnimationFrame(() => {
                                if (this.$refs.map) this.initMap();
                            });
                            return;
                        }

                        if (mapElement._leaflet_id) {
                            try {
                                this.map?.remove();
                            } catch (err) {}
                            this.map = null;
                            if (mapElement._leaflet_id) {
                                delete mapElement._leaflet_id;
                                mapElement.innerHTML = '';
                            }
                        }

                        const fallbackLat = 14.5898967;
                        const fallbackLng = 121.0639172;

                        if (!this.lat || isNaN(this.lat) || (Math.abs(this.lat) < 0.0001 && Math.abs(this.lng) < 0.0001)) {
                            this.lat = parseFloat(this.defaultLat) || fallbackLat;
                        }
                        if (!this.lng || isNaN(this.lng) || (Math.abs(this.lat) < 0.0001 && Math.abs(this.lng) < 0.0001)) {
                            this.lng = parseFloat(this.defaultLng) || fallbackLng;
                        }

                        this.map = L.map(mapElement, {
                            center: [this.lat, this.lng],
                            zoom: this.defaultZoom || 15,
                            zoomControl: true,
                            attributionControl: true,
                        });

                        const tileOpts = {
                            attribution: this.tileAttribution || '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://carto.com/attributions">CARTO</a>',
                            maxZoom: 19,
                            subdomains: 'abcd',
                            detectRetina: false,
                        };

                        this.tileLayer = L.tileLayer(this.getTileUrl(), tileOpts).addTo(this.map);

                        // Fallback tile provider in case CartoDB subdomains are throttled
                        let tileErrorCount = 0;
                        this.tileLayer.on('tileerror', () => {
                            tileErrorCount++;
                            if (tileErrorCount > 4 && this.tileLayer) {
                                this.map.removeLayer(this.tileLayer);
                                this.tileLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                                    maxZoom: 19,
                                }).addTo(this.map);
                            }
                        });

                        // Watch for dark/light mode switches
                        this.themeObserver = new MutationObserver(() => {
                            const newUrl = this.getTileUrl();
                            if (this.tileLayer && this.tileLayer._url !== newUrl) {
                                this.map.removeLayer(this.tileLayer);
                                this.tileLayer = L.tileLayer(newUrl, tileOpts).addTo(this.map);
                            }
                        });
                        this.themeObserver.observe(document.documentElement, {
                            attributes: true,
                            attributeFilter: ['class'],
                        });

                        // Marker setup
                        this.marker = L.marker([this.lat, this.lng], {
                            draggable: this.isDraggable,
                            icon: this.createMarkerIcon(),
                        }).addTo(this.map);

                        if (this.isDraggable) {
                            this.marker.on('dragend', (e) => {
                                const pos = e.target.getLatLng();
                                this.updatePosition(pos.lat, pos.lng);
                            });
                        }

                        this.map.on('click', (e) => {
                            this.marker.setLatLng(e.latlng);
                            this.updatePosition(e.latlng.lat, e.latlng.lng);
                        });

                        if (this.radiusField) {
                            this.initRadiusCircle();
                        }

                        this.isMapLoaded = true;

                        // Setup auto-invalidation triggers for Wizard/Tabs/Modal lifecycle
                        const invalidate = () => {
                            if (this.map && mapElement.isConnected) {
                                requestAnimationFrame(() => {
                                    if (mapElement.clientWidth > 0 && mapElement.clientHeight > 0) {
                                        this.map.invalidateSize({ pan: false });
                                    }
                                });
                            }
                        };

                        this.resizeObserver = new ResizeObserver(() => invalidate());
                        this.resizeObserver.observe(containerElement);

                        // Aggressively catch all transition frames as Filament animates the UI
                        [10, 50, 150, 300, 500, 800].forEach(delay => setTimeout(invalidate, delay));

                        window.addEventListener('resize', invalidate);
                        document.addEventListener('filament-wizard::step-changed', () => setTimeout(invalidate, 150));
                        document.addEventListener('tab-changed', () => setTimeout(invalidate, 150));
                        document.addEventListener('open-modal', () => setTimeout(invalidate, 250));

                        if (window.IntersectionObserver) {
                            this.intersectionObserver = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    if (entry.isIntersecting) {
                                        invalidate();
                                    }
                                });
                            });
                            this.intersectionObserver.observe(mapElement);
                        }

                        if (window.Livewire) {
                            Livewire.hook('morph.updated', () => {
                                setTimeout(invalidate, 50);
                            });
                        }
                    },

                    initRadiusCircle() {
                        const r = this.radius || 500;
                        this.circle = L.circle([this.lat, this.lng], {
                            radius: r,
                            color: '#3b82f6',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.18,
                            weight: 2,
                        }).addTo(this.map);

                        this.radiusHandle = L.marker(this.getCircleEdgeLatLng(), {
                            draggable: true,
                            icon: L.divIcon({
                                className: 'location-radius-handle',
                                html: '<div></div>',
                                iconSize: [16, 16],
                                iconAnchor: [8, 8],
                            }),
                        }).addTo(this.map);

                        this.radiusHandle.on('drag', (e) => {
                            const center = L.latLng(this.lat, this.lng);
                            const newRadius = Math.round(center.distanceTo(e.latlng));
                            this.radius = newRadius;
                            this.circle.setRadius(newRadius);
                            this.updateRadiusState();
                        });

                        this.radiusHandle.on('dragend', () => {
                            this.radiusHandle.setLatLng(this.getCircleEdgeLatLng());
                        });
                    },

                    getCircleEdgeLatLng() {
                        const r = this.radius || 500;
                        const earthRadius = 6371000;
                        const lat = this.lat * Math.PI / 180;
                        const dLng = (r / earthRadius) / Math.cos(lat);
                        return L.latLng(this.lat, this.lng + (dLng * 180 / Math.PI));
                    },

                    onSearchInput(query) {
                        this.showDropdown = false;
                        clearTimeout(this.searchTimeout);

                        if (!query || query.trim().length < 3) {
                            this.searchResults = [];
                            return;
                        }

                        this.searchTimeout = setTimeout(() => {
                            this.fetchSearchResults(query.trim());
                        }, 400);
                    },

                    async fetchSearchResults(query) {
                        this.isSearching = true;
                        try {
                            const params = new URLSearchParams({
                                q: query,
                                format: 'json',
                                limit: '6',
                                addressdetails: '1',
                            });

                            if (this.countryCodes) {
                                params.append('countrycodes', this.countryCodes);
                            }

                            const res = await fetch(`${this.nominatimUrl}/search?${params.toString()}`, {
                                headers: { 'Accept-Language': document.documentElement.lang || 'en' },
                            });
                            const data = await res.json();
                            this.searchResults = Array.isArray(data) ? data : [];
                            this.showDropdown = this.searchResults.length > 0;
                        } catch (e) {
                            console.error('[LocationPicker] Nominatim search error:', e);
                        } finally {
                            this.isSearching = false;
                        }
                    },

                    selectSearchResult(result) {
                        const lat = parseFloat(result.lat);
                        const lng = parseFloat(result.lon);

                        this.marker.setLatLng([lat, lng]);
                        this.map.setView([lat, lng], 17);
                        this.address = result.display_name;
                        this.showDropdown = false;
                        this.searchResults = [];

                        this.updatePosition(lat, lng, false);
                    },

                    updatePosition(lat, lng, shouldReverseGeocode = true) {
                        this.lat = parseFloat(lat.toFixed(7));
                        this.lng = parseFloat(lng.toFixed(7));

                        if (this.circle) {
                            this.circle.setLatLng([this.lat, this.lng]);
                            this.radiusHandle?.setLatLng(this.getCircleEdgeLatLng());
                        }

                        const latPath = this.getFieldPath(this.latField);
                        const lngPath = this.getFieldPath(this.lngField);
                        const addressPath = this.getFieldPath(this.addressField);

                        if (latPath) this.$wire?.set(latPath, this.lat);
                        if (lngPath) this.$wire?.set(lngPath, this.lng);
                        if (addressPath && !shouldReverseGeocode) {
                            this.$wire?.set(addressPath, this.address);
                        }

                        if (shouldReverseGeocode) {
                            this.reverseGeocode(lat, lng);
                        }
                    },

                    updateRadiusState() {
                        const radiusPath = this.getFieldPath(this.radiusField);
                        if (radiusPath) this.$wire?.set(radiusPath, this.radius);
                    },

                    reverseGeocode(lat, lng) {
                        if (this.reverseGeocodeTimeout) {
                            clearTimeout(this.reverseGeocodeTimeout);
                        }

                        if (this.reverseGeocodeAbortController) {
                            this.reverseGeocodeAbortController.abort();
                        }

                        this.reverseGeocodeAbortController = new AbortController();
                        const signal = this.reverseGeocodeAbortController.signal;

                        this.reverseGeocodeTimeout = setTimeout(async () => {
                            try {
                                const params = new URLSearchParams({
                                    lat: lat,
                                    lon: lng,
                                    format: 'json',
                                    addressdetails: '1',
                                    zoom: '18',
                                });

                                const res = await fetch(`${this.nominatimUrl}/reverse?${params.toString()}`, {
                                    signal: signal,
                                    headers: { 'Accept-Language': document.documentElement.lang || 'en' },
                                });
                                const data = await res.json();

                                if (!data || data.error) return;

                                const addr = data.address || {};
                                const displayName = data.display_name || '';

                                this.address = displayName;

                                const addressPath = this.getFieldPath(this.addressField);
                                if (addressPath) this.$wire?.set(addressPath, displayName);

                                const street       = addr.road || addr.pedestrian || addr.footway || addr.path || '';
                                const streetNumber = addr.house_number || '';
                                const province     = addr.state || addr.province || '';
                                const city         = addr.city || addr.regency || addr.county || addr.municipality || addr.town || '';
                                const district     = addr.city_district || addr.district || addr.suburb || addr.borough || '';
                                const village      = addr.village || addr.neighbourhood || addr.quarter || addr.residential || '';
                                const postalCode   = addr.postcode || '';
                                const country      = addr.country || '';

                                let shortAddress = '';
                                if (street && streetNumber) shortAddress = `${street} ${streetNumber}`;
                                else if (street) shortAddress = street;

                                const fieldMap = {
                                    shortAddressField: shortAddress || null,
                                    streetField:       street || null,
                                    streetNumberField: streetNumber || null,
                                    provinceField:     province || null,
                                    cityField:         city || null,
                                    districtField:     district || null,
                                    villageField:      village || null,
                                    postalCodeField:   postalCode || null,
                                    countryField:      country || null,
                                };

                                for (const [field, value] of Object.entries(fieldMap)) {
                                    const path = this.getFieldPath(this[field]);
                                    if (path) this.$wire?.set(path, value);
                                }
                            } catch (e) {
                                if (e.name === 'AbortError') return;
                                console.error('[LocationPicker] Nominatim reverse geocode error:', e);
                            }
                        }, 300);
                    },

                    getCurrentLocation() {
                        if (!navigator.geolocation) {
                            alert('Geolocation is not supported by your browser.');
                            return;
                        }

                        this.isLocating = true;

                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.isLocating = false;
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;

                                this.marker.setLatLng([lat, lng]);
                                this.map.setView([lat, lng], 17);

                                if (this.circle) {
                                    this.circle.setLatLng([lat, lng]);
                                    this.radiusHandle?.setLatLng(this.getCircleEdgeLatLng());
                                }

                                this.updatePosition(lat, lng, true);
                            },
                            (error) => {
                                this.isLocating = false;
                                console.error('[LocationPicker] Geolocation error:', error);
                                let msg = 'Unable to retrieve location.';
                                if (error.code === 1) msg = 'Location permission was denied. Please allow location access in your browser settings.';
                                else if (error.code === 2) msg = 'Location is unavailable.';
                                else if (error.code === 3) msg = 'Location request timed out.';
                                alert(msg);
                            },
                            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                        );
                    },
                }));
            };

            if (window.Alpine) {
                initLocationPickerAlpine();
            } else {
                document.addEventListener('alpine:init', initLocationPickerAlpine, { once: true });
            }
        }
    </script>

    <style>
        .fi-fo-location-picker .leaflet-container img,
        .fi-fo-location-picker .leaflet-container .leaflet-tile,
        .fi-fo-location-picker .leaflet-container .leaflet-marker-icon,
        .fi-fo-location-picker .leaflet-container .leaflet-marker-shadow,
        .fi-fo-location-picker .leaflet-tile-container img {
            max-width: none !important;
            max-height: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            filter: none !important;
            vertical-align: baseline !important;
        }

        .fi-fo-location-picker .location-map-wrapper {
            position: relative;
            height: var(--location-map-height, 400px);
            min-height: 0 !important;
            max-height: var(--location-map-height, 400px);
            overflow: hidden;
        }

        .fi-fo-location-picker .location-map-wrapper > .leaflet-container {
            box-sizing: border-box !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 0 !important;
            max-height: 100% !important;
            font-family: inherit;
        }

        .fi-fo-location-picker .location-custom-marker {
            background: transparent !important;
            border: none !important;
        }

        /* Radius drag handle */
        .location-radius-handle div {
            width: 16px;
            height: 16px;
            background: #ffffff;
            border: 3px solid #3b82f6;
            border-radius: 50%;
            cursor: ew-resize;
            box-shadow: 0 1px 4px rgba(0,0,0,0.35);
        }

        /* Search Input */
        .fi-fo-location-picker .location-search-input {
            display: block;
            width: 100%;
            padding: 8px 36px 8px 36px;
            font-size: 0.875rem;
            line-height: 1.25rem;
            border-radius: 0.5rem;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            color: #111827;
            outline: none;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .fi-fo-location-picker .location-search-input::placeholder {
            color: #9ca3af;
        }
        .fi-fo-location-picker .location-search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        .dark .fi-fo-location-picker .location-search-input {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f9fafb !important;
        }
        .dark .fi-fo-location-picker .location-search-input::placeholder {
            color: #6b7280 !important;
        }
        .dark .fi-fo-location-picker .location-search-input:focus {
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.25) !important;
        }

        /* Search Dropdown - Compact 3-5 Rows */
        .fi-fo-location-picker .location-search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin-top: 4px;
            border-radius: 0.5rem;
            max-height: 185px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
        }
        .dark .fi-fo-location-picker .location-search-dropdown {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }

        .fi-fo-location-picker .location-search-item {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 7px 10px;
            text-align: left;
            font-size: 0.8125rem;
            line-height: 1.25rem;
            color: #374151;
            background: transparent;
            border: none;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.1s ease;
            box-sizing: border-box;
        }
        .fi-fo-location-picker .location-search-item:last-child {
            border-bottom: none;
        }
        .fi-fo-location-picker .location-search-item:hover {
            background-color: #f9fafb;
            color: #111827;
        }
        .dark .fi-fo-location-picker .location-search-item {
            color: #d1d5db !important;
            border-bottom-color: #374151 !important;
        }
        .dark .fi-fo-location-picker .location-search-item:hover {
            background-color: #2d3748 !important;
            color: #ffffff !important;
        }

        .fi-fo-location-picker .location-search-item-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        /* Use My Location Button */
        .fi-fo-location-picker .location-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #374151;
            cursor: pointer;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.15s ease;
        }
        .fi-fo-location-picker .location-btn:hover:not(:disabled) {
            background-color: #f9fafb;
            border-color: #9ca3af;
            color: #111827;
        }
        .fi-fo-location-picker .location-btn:active:not(:disabled) {
            transform: translateY(1px);
        }
        .fi-fo-location-picker .location-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .dark .fi-fo-location-picker .location-btn {
            background-color: #1f2937;
            border-color: #374151;
            color: #e5e7eb;
        }
        .dark .fi-fo-location-picker .location-btn:hover:not(:disabled) {
            background-color: #2d3748;
            border-color: #4b5563;
            color: #ffffff;
        }
    </style>
</x-dynamic-component>

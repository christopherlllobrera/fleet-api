<x-filament-panels::page>
    <div class="min-h-full overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <header class="flex h-16 items-center justify-between border-b border-gray-200 px-6 dark:border-gray-800">
            <div>
                <h1 class="text-lg font-semibold tracking-tight text-gray-950 dark:text-white">Fleet — live tracking</h1>
                <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span id="fleet-name">MLI Fleet</span>
                    <span aria-hidden="true">·</span>
                    <span id="sync-status">Syncing…</span>
                </div>
            </div>
            <div class="flex gap-2" id="counts"></div>
        </header>

        <div class="flex h-[calc(100vh-16rem)]">
            <aside class="flex w-85 flex-col border-r border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950">
                <div class="flex flex-wrap gap-2 px-4 py-4" id="filters"></div>
                <div class="max-h-180 flex-1 overflow-y-auto px-3 pb-3" id="vehicle-list"></div>
                <div class="border-t border-gray-200 px-4 py-3 text-[11px] text-gray-500 dark:border-gray-800 dark:text-gray-500">
                    Live positions — polling the fleet GPS feed every 8s. Showing 10 at a time — scroll for more.
                </div>
            </aside>

            <div class="relative flex-1">
                <div id="map" class="h-full w-full bg-gray-50 dark:bg-gray-950"></div>
                <div class="absolute bottom-5 left-5 z-500 flex gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-xs text-gray-700 shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>Moving</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-gray-500"></span>Stationary</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-red-600"></span>GPS stale</div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

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
        .vehicle-marker.stale { color: #dc2626; opacity: 0.6; }
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

    <script>
        const FLEET_ENDPOINT = '{{ route('fleet.live-positions') }}';
        const POLL_MS = 8000;
        const LIGHT_TILE = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
        const DARK_TILE = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

        function isDark() {
            return document.documentElement.classList.contains('dark');
        }

        const map = L.map('map', { zoomControl: true }).setView([14.58, 121.03], 11);
        let tileLayer = L.tileLayer(isDark() ? DARK_TILE : LIGHT_TILE, {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            maxZoom: 19
        }).addTo(map);

        // Filament's dark-mode toggle just flips a class on <html> without a
        // page reload — watch for that and swap the basemap to match.
        new MutationObserver(() => {
            const wantUrl = isDark() ? DARK_TILE : LIGHT_TILE;
            if (tileLayer._url !== wantUrl) {
                map.removeLayer(tileLayer);
                tileLayer = L.tileLayer(wantUrl, {
                    attribution: '&copy; OpenStreetMap &copy; CARTO',
                    maxZoom: 19
                }).addTo(map);
            }
        }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        const markers = {};
        const prevPositions = {}; // asset_id -> {lat, lng}
        const headings = {};      // asset_id -> degrees
        let vehicles = [];
        let selectedVehicleId = null;

        function classify(v) {
            if (v.gps_stale) return 'stale';
            if (v.speed > 0) return 'moving';
            return 'stationary';
        }

        function bearingDeg(lat1, lng1, lat2, lng2) {
            const toRad = d => d * Math.PI / 180;
            const toDeg = r => r * 180 / Math.PI;
            const dLon = toRad(lng2 - lng1);
            const y = Math.sin(dLon) * Math.cos(toRad(lat2));
            const x = Math.cos(toRad(lat1)) * Math.sin(toRad(lat2))
                - Math.sin(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.cos(dLon);
            return (toDeg(Math.atan2(y, x)) + 360) % 360;
        }

        function normalize(raw) {
            return raw.map(v => ({
                id: v.asset_id,
                asset_id: v.asset_id,
                plate: v.plate,
                fleet_name: v.fleet_name,
                device_sn: v.device_sn,
                lat: v.latitude,
                lng: v.longitude,
                speed: v.speed,
                rpm: v.rpm,
                last_update: v.last_update,
                vehicle_type: v.vehicle_type,
                car_maker: v.car_maker,
                car_model: v.car_model,
                gps_stale: v.gps_stale,
            }));
        }

        function relTime(iso) {
            const diffMs = Date.now() - new Date(iso).getTime();
            const mins = Math.round(diffMs / 60000);
            if (mins < 1) return 'just now';
            if (mins < 60) return mins + 'm ago';
            const hrs = Math.round(mins / 60);
            if (hrs < 24) return hrs + 'h ago';
            return Math.round(hrs / 24) + 'd ago';
        }

        function popupHtml(v) {
            return `<div class="min-w-[180px] font-sans">
                <div class="mb-1 text-sm font-semibold text-gray-950 dark:text-white">${v.plate}</div>
                <div class="mb-2 text-xs text-gray-500 dark:text-gray-400">${v.car_maker} ${v.car_model} · ${v.vehicle_type}</div>
                <div class="flex justify-between border-t border-gray-200 py-1 text-xs dark:border-gray-700"><span class="text-gray-500 dark:text-gray-400">Status</span><span class="font-medium text-gray-900 dark:text-gray-100">${v.state}</span></div>
                <div class="flex justify-between border-t border-gray-200 py-1 text-xs dark:border-gray-700"><span class="text-gray-500 dark:text-gray-400">Speed</span><span class="font-medium text-gray-900 dark:text-gray-100">${v.speed} km/h</span></div>
                <div class="flex justify-between border-t border-gray-200 py-1 text-xs dark:border-gray-700"><span class="text-gray-500 dark:text-gray-400">Updated</span><span class="font-medium text-gray-900 dark:text-gray-100">${relTime(v.last_update)}</span></div>
                <div class="mt-2 font-mono text-[11px] text-gray-500 dark:text-gray-400">${v.device_sn}</div>
            </div>`;
        }

        function carSvg() {
            return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
            </svg>`;
        }

        function facingTransform(heading) {
            // Side-profile icon: a compass rotation would flip it upside
            // down at some angles, so instead mirror it left/right based on
            // whether travel has an eastward or westward component. North/
            // south travel keeps the default (right-facing) orientation.
            return Math.sin(heading * Math.PI / 180) < 0 ? 'scaleX(-1)' : 'scaleX(1)';
        }

        function markerIcon(v) {
            const selected = v.id === selectedVehicleId ? ' is-selected' : '';
            return L.divIcon({
                className: '',
                html: `<div class="vehicle-marker ${v.state}${selected}" style="transform: ${facingTransform(v.heading)}">${carSvg()}</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15],
            });
        }

        function createMarker(v) {
            const m = L.marker([v.lat, v.lng], { icon: markerIcon(v) }).addTo(map);
            m.bindPopup(popupHtml(v));
            m.on('click', () => selectVehicle(v.id));
            markers[v.id] = m;
        }

        function updateMarker(v) {
            const m = markers[v.id];
            m.setLatLng([v.lat, v.lng]);
            m.setPopupContent(popupHtml(v));
            const el = m.getElement();
            const inner = el && el.querySelector('.vehicle-marker');
            if (inner) {
                const selected = v.id === selectedVehicleId ? ' is-selected' : '';
                inner.className = `vehicle-marker ${v.state}${selected}`;
                inner.style.transform = facingTransform(v.heading);
            }
        }

        function refreshMarkerHighlight(id) {
            const m = markers[id];
            const el = m && m.getElement();
            const inner = el && el.querySelector('.vehicle-marker');
            if (inner) inner.classList.toggle('is-selected', id === selectedVehicleId);
        }

        function selectVehicle(id, opts = {}) {
            const previous = selectedVehicleId;
            selectedVehicleId = id;

            refreshMarkerHighlight(previous);
            refreshMarkerHighlight(id);
            renderList();

            const row = document.getElementById(`vehicle-row-${id}`);
            if (row) row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            const m = markers[id];
            if (m) {
                if (opts.flyTo) map.flyTo(m.getLatLng(), 16, { duration: 0.6 });
                m.openPopup();
            }
        }

        const FILTERS = ['all', 'moving', 'stationary', 'stale'];
        let activeFilter = 'all';

        function renderFilters() {
            const el = document.getElementById('filters');
            el.innerHTML = '';
            FILTERS.forEach(f => {
                const btn = document.createElement('button');
                btn.className = 'rounded-full border border-transparent px-3 py-1 text-xs font-medium transition ' + (f === activeFilter
                    ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                    : 'bg-transparent text-gray-900 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-800');
                btn.textContent = f === 'all' ? 'All' : f.charAt(0).toUpperCase() + f.slice(1);
                btn.onclick = () => { activeFilter = f; renderFilters(); renderList(); applyFilterToMap(); };
                el.appendChild(btn);
            });
        }

        function applyFilterToMap() {
            vehicles.forEach(v => {
                const visible = activeFilter === 'all' || v.state === activeFilter;
                const el = markers[v.id] && markers[v.id].getElement();
                if (el) el.style.display = visible ? '' : 'none';
            });
        }

        function renderCounts() {
            const counts = { moving: 0, stationary: 0, stale: 0 };
            vehicles.forEach(v => counts[v.state]++);
            document.getElementById('counts').innerHTML = `
                <div class="flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100"><span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>${counts.moving} moving</div>
                <div class="flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100"><span class="h-2.5 w-2.5 rounded-full bg-gray-500"></span>${counts.stationary} stationary</div>
                <div class="flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100"><span class="h-2.5 w-2.5 rounded-full bg-red-600"></span>${counts.stale} stale</div>
            `;
        }

        function renderList() {
            const el = document.getElementById('vehicle-list');
            el.innerHTML = '';
            vehicles
                .filter(v => activeFilter === 'all' || v.state === activeFilter)
                .forEach(v => {
                    const selected = v.id === selectedVehicleId;
                    const row = document.createElement('div');
                    row.id = `vehicle-row-${v.id}`;
                    row.className = [
                        'mb-2 flex cursor-pointer items-center justify-between gap-3 rounded-xl border p-3 shadow-sm transition hover:shadow-md',
                        selected
                            ? 'border-gray-900 bg-gray-50 ring-2 ring-gray-900/10 dark:border-white dark:bg-gray-800 dark:ring-white/10'
                            : 'border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900',
                    ].join(' ');
                    row.innerHTML = `
                        <div class="min-w-0">
                            <div class="truncate text-sm font-medium text-gray-950 dark:text-white">${v.plate}</div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">${v.car_maker} ${v.car_model} · ${relTime(v.last_update)}</div>
                        </div>
                        <div class="shrink-0 rounded-full border px-2.5 py-1 text-[11px] font-medium capitalize ${v.state === 'moving' ? 'border-green-500 text-green-600 dark:border-green-400 dark:text-green-400' : v.state === 'stale' ? 'border-red-500 text-red-600 dark:border-red-400 dark:text-red-400' : 'border-gray-300 bg-gray-50 text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300'}">${v.state}</div>
                    `;
                    row.onclick = () => selectVehicle(v.id, { flyTo: true });
                    el.appendChild(row);
                });
        }

        function setSyncStatus(ok) {
            const el = document.getElementById('sync-status');
            if (!el) return;
            el.textContent = ok ? 'Live' : 'Reconnecting…';
            el.className = ok ? 'font-medium text-green-600 dark:text-green-400' : 'font-medium text-red-600 dark:text-red-400';
        }

        async function fetchPositions() {
            const res = await fetch(FLEET_ENDPOINT, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) {
                if (res.status === 401) {
                    throw new Error('API token expired. Please refresh the page to reauthenticate.');
                }
                if (res.status === 502) {
                    throw new Error('Bad Gateway - API key may be invalid or the external service is unavailable.');
                }
                throw new Error('Fleet endpoint returned ' + res.status);
            }
            const payload = await res.json();
            return normalize(payload.vehicles || []);
        }

        function applyUpdate(fresh) {
            fresh.forEach(v => {
                v.state = classify(v);

                const prev = prevPositions[v.id];
                if (v.state === 'moving' && prev && (prev.lat !== v.lat || prev.lng !== v.lng)) {
                    headings[v.id] = bearingDeg(prev.lat, prev.lng, v.lat, v.lng);
                }
                v.heading = headings[v.id] || 0;
                prevPositions[v.id] = { lat: v.lat, lng: v.lng };

                if (markers[v.id]) {
                    updateMarker(v);
                } else {
                    createMarker(v);
                }
            });

            vehicles = fresh;

            if (fresh.length && fresh[0].fleet_name) {
                document.getElementById('fleet-name').textContent = fresh[0].fleet_name;
            }

            renderList();
            renderCounts();
            applyFilterToMap();
        }

        async function refresh() {
            try {
                applyUpdate(await fetchPositions());
                setSyncStatus(true);
            } catch (err) {
                console.error('Fleet live tracking sync failed:', err);
                if (err.message.includes('Failed to fetch') || err.message.includes('NetworkError')) {
                    console.error('No response from API - check network connection');
                }
                setSyncStatus(false);
            }
        }

        renderFilters();
        refresh();
        const pollTimer = setInterval(refresh, POLL_MS);
        window.addEventListener('beforeunload', () => clearInterval(pollTimer));
    </script>
</x-filament-panels::page>

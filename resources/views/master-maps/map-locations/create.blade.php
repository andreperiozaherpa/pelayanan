@extends('layouts.app')

@section('title', 'Tambah Titik Lokasi')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Tambah Titik Lokasi (POI)</h1>
        <p class="text-xs text-slate-500 font-medium tracking-tight">Daftarkan lokasi penting baru beserta data spasial dan regulasi wilayahnya.</p>
    </div>

    <div class="premium-card p-6 md:p-8">
        <form action="{{ route('map-locations.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @csrf
            
            {{-- Form Fields --}}
            <div class="space-y-5">
                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Nama Lokasi</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Masjid Agung Baitul Mukminin, Kantor Bupati Tubaba" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                    @error('name') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Kategori Lokasi</label>
                        <select name="category_id" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all text-slate-700 dark:text-slate-300">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-color="{{ $cat->color }}" data-icon="{{ $cat->icon }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Wilayah Administrasi</label>
                        <select name="region_id"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all text-slate-700 dark:text-slate-300">
                            <option value="">— Pilih Wilayah —</option>
                            @foreach($regions as $reg)
                                <option value="{{ $reg->id }}">{{ $reg->name }} ({{ $reg->level }})</option>
                            @endforeach
                        </select>
                        @error('region_id') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Latitude</label>
                        <input type="number" step="0.00000001" name="latitude" id="latitude" value="{{ old('latitude', -4.444) }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                        @error('latitude') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Longitude</label>
                        <input type="number" step="0.00000001" name="longitude" id="longitude" value="{{ old('longitude', 105.045) }}" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                        @error('longitude') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Alamat Lengkap</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Contoh: Jl. Diponegoro No. 12, Panaragan Jaya"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                    @error('address') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Deskripsi / Keterangan</label>
                    <textarea name="description" placeholder="Informasi tambahan mengenai fasilitas..." rows="2"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400"></textarea>
                    @error('description') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Foto Lokasi</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold transition-all dark:text-white" />
                    @error('photo') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Radius Restriction Toggle --}}
                <div x-data="{ hasRestriction: false, restrictionMode: 'radius' }" @set-restriction.window="hasRestriction = $event.detail.hasRestriction; restrictionMode = $event.detail.restrictionMode">
                    <label class="flex items-center gap-3 cursor-pointer py-2">
                        <input type="checkbox" name="has_restriction" value="1" x-model="hasRestriction" class="rounded border-slate-300 text-primary-acorn focus:ring-primary-acorn">
                        <span class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">Memiliki Zona Restriksi Radius?</span>
                    </label>

                    <div x-show="hasRestriction" class="mt-4 bg-rose-500/5 border border-rose-500/10 p-5 rounded-2xl space-y-4">
                        <h4 class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Pengaturan Zona Restriksi</h4>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Metode Zona</label>
                                <select id="restriction-mode" x-model="restrictionMode" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all text-slate-700 dark:text-slate-300">
                                    <option value="radius">Radius Lingkaran (Meter)</option>
                                    <option value="manual">Gambar Manual (Poligon Bebas)</option>
                                </select>
                            </div>
                            <div x-show="restrictionMode === 'radius'">
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Radius Restriksi (Meter)</label>
                                <input type="number" id="restriction-radius" value="500" min="10" max="10000" step="50"
                                    :disabled="!hasRestriction || restrictionMode !== 'radius'"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Aktivitas yang Dilarang/Dibatasi</label>
                            <div class="grid grid-cols-1 gap-2.5">
                                @foreach(['Aktivitas Industri', 'Pembangunan Tempat Hiburan', 'Perdagangan & Jasa Skala Besar', 'Pembangunan Rumah Ibadah Lain', 'Peternakan Skala Besar'] as $act)
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" name="restricted_activities[]" value="{{ $act }}" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $act }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Catatan Tambahan Restriksi</label>
                            <textarea name="restriction_notes" placeholder="Contoh: Dilarang mendirikan usaha industri dalam radius 500m dari rumah ibadah..." rows="2"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400"></textarea>
                        </div>

                        {{-- Hidden field for restriction GeoJSON polygon --}}
                        <textarea name="restriction_geojson" id="restriction_geojson" class="hidden"></textarea>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all">
                        Simpan Lokasi
                    </button>
                    <a href="{{ route('map-locations.index') }}" class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Batal
                    </a>
                </div>
            </div>

            {{-- Drawing Map Container --}}
            <div class="flex flex-col gap-4">
                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1 block">Tentukan Koordinat & Poligon Restriksi</label>
                    <p class="text-[10px] text-slate-400 font-medium">Klik pada peta untuk menempatkan pin lokasi (POI). Jika zona restriksi aktif, gunakan alat gambar poligon untuk melukis batas wilayah restriksi di sekitar pin.</p>
                </div>
                <div id="map-draw" class="w-full h-[28rem] rounded-2xl border border-black/10 dark:border-white/10 overflow-hidden shadow-inner relative z-10"></div>
                <div class="flex flex-col sm:flex-row justify-between gap-3 sm:items-center bg-slate-50 dark:bg-slate-800/30 p-3.5 rounded-xl border border-black/[0.03] dark:border-white/[0.03]">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pin Posisi</span>
                        <span id="pin-status" class="inline-block text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/10 mt-1 max-w-max">
                            Pin Terpasang
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="geojson-upload" class="cursor-pointer bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-3 py-2 rounded-lg font-bold text-[10px] uppercase tracking-wider transition-all flex items-center gap-1.5 border border-black/5 dark:border-white/5">
                            <iconify-icon icon="lucide:upload-cloud" class="text-xs"></iconify-icon>
                            Upload GeoJSON / KML
                        </label>
                        <input type="file" id="geojson-upload" accept=".geojson,.json,.kml" class="hidden">
                    </div>
                </div>

                {{-- Overpass POI Scanner --}}
                <div class="bg-slate-50 dark:bg-slate-800/30 p-5 rounded-2xl border border-black/[0.03] dark:border-white/[0.03] space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest flex items-center gap-1.5">
                            <iconify-icon icon="lucide:database" class="text-blue-500"></iconify-icon>
                            Auto-Fill Koordinat (OpenStreetMap)
                        </h4>
                        <span id="overpass-status" class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 text-slate-500 border border-slate-300/30">Idle</span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium">Cari data koordinat dari OpenStreetMap sekitar posisi pin saat ini berdasarkan Kategori POI menggunakan Overpass API.</p>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[9px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1 block">Radius Pencarian (Meter)</label>
                            <input type="number" id="overpass-radius" value="1000" min="100" max="10000" step="100"
                                class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-black/[0.05] dark:border-white/[0.05] rounded-xl text-[10px] font-bold dark:text-white focus:outline-none" />
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="btn-fetch-overpass"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white py-2.5 rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md transition-all">
                                Scan Sekitar (OSM)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-overpass-layer@2.9.0/dist/OverPassLayer.css" />
@endpush
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load leaflet-overpass-layer dynamically so it runs after Vite's window.L is defined
        const overpassScript = document.createElement('script');
        overpassScript.src = 'https://cdn.jsdelivr.net/npm/leaflet-overpass-layer@2.9.0/dist/OverPassLayer.bundle.js';
        document.head.appendChild(overpassScript);

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        // Initialize Leaflet Map
        const map = L.map('map-draw', {
            zoomControl: false
        }).setView([-4.444, 105.045], 13);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Define base layers
        const baseMaps = {
            "CartoDB Dark": L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
            "CartoDB Light": L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
            "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 })
        };

        // Add CartoDB Dark as default
        baseMaps["CartoDB Dark"].addTo(map);

        // Add Layer control to the map
        const layerControl = L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

        // Load Google Maps API & GoogleMutant dynamically
        window.googleMapsReady = function() {
            const mutantScript = document.createElement('script');
            mutantScript.src = 'https://unpkg.com/leaflet.gridlayer.googlemutant@latest/dist/Leaflet.GoogleMutant.js';
            mutantScript.onload = function() {
                try {
                    const gRoadmap = L.gridLayer.googleMutant({ type: 'roadmap' });
                    const gSatellite = L.gridLayer.googleMutant({ type: 'satellite' });
                    const gHybrid = L.gridLayer.googleMutant({ type: 'hybrid' });
                    const gTerrain = L.gridLayer.googleMutant({ type: 'terrain' });

                    layerControl.addBaseLayer(gRoadmap, "Google Roadmap");
                    layerControl.addBaseLayer(gSatellite, "Google Satellite");
                    layerControl.addBaseLayer(gHybrid, "Google Hybrid");
                    layerControl.addBaseLayer(gTerrain, "Google Terrain");
                } catch (e) {
                    console.error("Failed to load GoogleMutant:", e);
                }
            };
            document.head.appendChild(mutantScript);
        };

        const googleMapsScript = document.createElement('script');
        googleMapsScript.src = 'https://maps.googleapis.com/maps/api/js?key={{ config("services.google.maps_api_key") }}&loading=async&callback=googleMapsReady';
        googleMapsScript.async = true;
        googleMapsScript.defer = true;
        document.head.appendChild(googleMapsScript);

        // Marker for POI position
        let poiMarker = L.marker([latInput.value, lngInput.value], {
            draggable: true
        }).addTo(map);

        const categorySelect = document.querySelector('select[name="category_id"]');
        const photoInput = document.querySelector('input[name="photo"]');

        function updateMarkerIcon() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            if (selectedOption) {
                const color = selectedOption.getAttribute('data-color') || '#ef4444';
                const icon = selectedOption.getAttribute('data-icon') || 'lucide:map-pin';
                const customIcon = L.divIcon({
                    html: `<div class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-800 shadow-lg flex items-center justify-center bg-slate-800/20" style="background-color: ${color}">
                             <iconify-icon icon="${icon}" class="text-white text-lg"></iconify-icon>
                           </div>`,
                    className: 'custom-poi-icon-marker',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });
                poiMarker.setIcon(customIcon);
            }
        }

        // Initialize marker icon
        updateMarkerIcon();

        // Listen for changes
        categorySelect.addEventListener('change', updateMarkerIcon);

        // Update inputs when marker is dragged
        poiMarker.on('dragend', function(e) {
            const position = poiMarker.getLatLng();
            latInput.value = position.lat.toFixed(8);
            lngInput.value = position.lng.toFixed(8);
            if (typeof window.updateRadiusRestriction === 'function') {
                window.updateRadiusRestriction();
            }
        });

        // Click map to reposition marker
        map.on('click', function(e) {
            poiMarker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat.toFixed(8);
            lngInput.value = e.latlng.lng.toFixed(8);
            if (typeof window.updateRadiusRestriction === 'function') {
                window.updateRadiusRestriction();
            }
        });

        // Update marker when inputs change
        latInput.addEventListener('input', function() {
            updateMarkerFromInputs();
            if (typeof window.updateRadiusRestriction === 'function') {
                window.updateRadiusRestriction();
            }
        });
        lngInput.addEventListener('input', function() {
            updateMarkerFromInputs();
            if (typeof window.updateRadiusRestriction === 'function') {
                window.updateRadiusRestriction();
            }
        });

        function updateMarkerFromInputs() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                poiMarker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        }

        // Overpass integration
        let overpassLayer = null;
        let overpassMarkers = [];

        window.useOSMLocation = function(lat, lng, name, address) {
            latInput.value = lat.toFixed(8);
            lngInput.value = lng.toFixed(8);
            document.querySelector('input[name="name"]').value = name;
            if (address && address !== 'undefined') {
                document.querySelector('input[name="address"]').value = address;
            }
            updateMarkerFromInputs();
            map.closePopup();
        };

        const btnFetchOverpass = document.getElementById('btn-fetch-overpass');
        const overpassStatus = document.getElementById('overpass-status');

        btnFetchOverpass.addEventListener('click', function() {
            if (typeof L.OverPassLayer === 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Mohon Tunggu',
                    text: 'Sedang memuat modul pencarian peta...',
                    showConfirmButton: false,
                    timer: 1500
                });
                return;
            }

            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            const radius = parseInt(document.getElementById('overpass-radius').value) || 1000;
            const categoryId = document.querySelector('select[name="category_id"]').value;

            const categorySlugs = @json($categories->pluck('slug', 'id'));
            const slug = categorySlugs[categoryId] || 'ibadah';

            const categoryMapping = {
                'ibadah': '[amenity=place_of_worship]',
                'pendidikan': '[amenity=school]',
                'pemerintahan': '[office=government]',
                'kesehatan': '[amenity=hospital]',
                'perbelanjaan': '[shop]',
                'spbu': '[amenity=fuel]',
                'keuangan': '[amenity=bank]',
                'wisata': '[tourism]'
            };

            const tag = categoryMapping[slug] || '[amenity]';

            if (overpassLayer) {
                map.removeLayer(overpassLayer);
            }
            
            // Clear existing overpass markers
            overpassMarkers.forEach(m => map.removeLayer(m));
            overpassMarkers = [];

            const queryStr = `node(around:${radius},${lat},${lng})${tag};out;`;
            overpassStatus.textContent = 'Scanning...';
            overpassStatus.className = 'text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-amber-500/10 text-amber-500 border border-amber-500/10';

            overpassLayer = new L.OverPassLayer({
                query: queryStr,
                minZoom: 8,
                onSuccess: function(data) {
                    overpassStatus.textContent = `Ditemukan ${data.elements.length} POI`;
                    overpassStatus.className = 'text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/10';

                    data.elements.forEach(node => {
                        if (node.lat && node.lon) {
                            const name = node.tags.name || 'Tanpa Nama';
                            const addr = node.tags['addr:street'] || node.tags['addr:full'] || '';
                            const cleanName = name.replace(/'/g, "\\'").replace(/"/g, '\\"');
                            const cleanAddr = addr.replace(/'/g, "\\'").replace(/"/g, '\\"');

                            const marker = L.circleMarker([node.lat, node.lon], {
                                radius: 7,
                                fillColor: '#3b82f6',
                                fillOpacity: 0.9,
                                color: '#ffffff',
                                weight: 2
                            }).addTo(map);

                            marker.bindPopup(`
                                <div class="p-1 text-slate-800 dark:text-slate-200">
                                    <h4 class="text-xs font-black">${name}</h4>
                                    <p class="text-[10px] text-slate-500 mt-1">${addr || 'Titik OpenStreetMap'}</p>
                                    <button type="button" onclick="window.useOSMLocation(${node.lat}, ${node.lon}, '${cleanName}', '${cleanAddr}')" 
                                        class="mt-2 w-full px-2 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded text-[9px] font-black uppercase tracking-wider transition-all">
                                        Gunakan Lokasi Ini
                                    </button>
                                </div>
                            `);

                            overpassMarkers.push(marker);
                        }
                    });
                },
                onError: function(err) {
                    overpassStatus.textContent = 'Scan Gagal';
                    overpassStatus.className = 'text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-rose-500/10 text-rose-500 border border-rose-500/10';
                    console.error(err);
                }
            });

            map.addLayer(overpassLayer);
        });

        // FeatureGroup to store drawn shapes (for restriction polygon)
        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Geodesic circle generator
        function generateGeodesicPolygon(centerLat, centerLng, radiusInMeters, pointsCount = 64) {
            const coords = [];
            const distanceX = radiusInMeters / (111.320 * 1000 * Math.cos(centerLat * Math.PI / 180));
            const distanceY = radiusInMeters / (110.574 * 1000);

            for (let i = 0; i < pointsCount; i++) {
                const theta = (i / pointsCount) * (2 * Math.PI);
                const x = distanceX * Math.cos(theta);
                const y = distanceY * Math.sin(theta);
                coords.push([centerLng + x, centerLat + y]);
            }
            coords.push(coords[0]); // Close polygon
            return {
                type: 'Polygon',
                coordinates: [coords]
            };
        }

        const restrictionGeojson = document.getElementById('restriction_geojson');
        const restrictionModeSelect = document.getElementById('restriction-mode');
        const restrictionRadiusInput = document.getElementById('restriction-radius');

        window.updateRadiusRestriction = function() {
            if (!restrictionModeSelect || restrictionModeSelect.value !== 'radius') return;
            
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            const radius = parseFloat(restrictionRadiusInput.value) || 500;

            if (!isNaN(lat) && !isNaN(lng)) {
                drawnItems.clearLayers();
                const geom = generateGeodesicPolygon(lat, lng, radius);
                const circleLayer = L.geoJSON({
                    type: "Feature",
                    geometry: geom,
                    properties: {}
                }, {
                    style: {
                        color: '#ef4444',
                        fillColor: '#ef4444',
                        fillOpacity: 0.2,
                        weight: 2
                    }
                });

                circleLayer.eachLayer(function(layer) {
                    drawnItems.addLayer(layer);
                });

                restrictionGeojson.value = JSON.stringify(geom);
            }
        };

        // Add Leaflet Draw controls for drawing restriction zone
        const drawControl = new L.Control.Draw({
            draw: {
                polyline: false,
                circle: false,
                circlemarker: false,
                marker: false,
                rectangle: false,
                polygon: {
                    allowIntersection: false,
                    drawError: {
                        color: '#ef4444',
                        message: '<strong>Poligon saling berpotongan!</strong>'
                    },
                    shapeOptions: {
                        color: '#ef4444',
                        fillColor: '#ef4444',
                        fillOpacity: 0.2,
                        weight: 2
                    }
                }
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });

        let drawControlAdded = true;
        map.addControl(drawControl);

        function updateDrawControls() {
            if (!restrictionModeSelect) return;
            if (restrictionModeSelect.value === 'radius') {
                if (drawControlAdded) {
                    map.removeControl(drawControl);
                    drawControlAdded = false;
                }
            } else {
                if (!drawControlAdded) {
                    map.addControl(drawControl);
                    drawControlAdded = true;
                }
            }
        }

        if (restrictionModeSelect) {
            restrictionModeSelect.addEventListener('change', function() {
                updateDrawControls();
                if (this.value === 'radius') {
                    window.updateRadiusRestriction();
                }
            });
        }

        if (restrictionRadiusInput) {
            restrictionRadiusInput.addEventListener('input', window.updateRadiusRestriction);
        }

        // Initial setup on load
        updateDrawControls();
        if (restrictionModeSelect && restrictionModeSelect.value === 'radius') {
            window.updateRadiusRestriction();
        }

        function updateRestrictionGeoJSON() {
            if (restrictionModeSelect && restrictionModeSelect.value === 'radius') return;
            const layers = drawnItems.getLayers();
            if (layers.length > 0) {
                const geojson = layers[0].toGeoJSON();
                restrictionGeojson.value = JSON.stringify(geojson.geometry);
            } else {
                restrictionGeojson.value = '';
            }
        }

        // Draw created handler
        map.on(L.Draw.Event.CREATED, function (event) {
            if (restrictionModeSelect && restrictionModeSelect.value === 'radius') return;
            drawnItems.clearLayers();
            const layer = event.layer;
            drawnItems.addLayer(layer);
            updateRestrictionGeoJSON();
        });

        map.on(L.Draw.Event.EDITED, function (event) {
            updateRestrictionGeoJSON();
        });

        map.on(L.Draw.Event.DELETED, function (event) {
            updateRestrictionGeoJSON();
        });

        // KML Parser Function
        function parseKML(xmlText) {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlText, "text/xml");
            
            const parserError = xmlDoc.getElementsByTagName("parsererror");
            if (parserError.length > 0) {
                throw new Error("Gagal mengurai file KML. Format XML tidak valid.");
            }
            
            const parseCoordinatesString = (str) => {
                return str.trim().split(/[\s\r\n]+/).map(coordStr => {
                    const parts = coordStr.split(',');
                    if (parts.length >= 2) {
                        const lng = parseFloat(parts[0]);
                        const lat = parseFloat(parts[1]);
                        if (!isNaN(lng) && !isNaN(lat)) {
                            return [lng, lat];
                        }
                    }
                    return null;
                }).filter(c => c !== null);
            };

            const features = [];

            // 1. MultiGeometry -> MultiPolygon
            const multiGeometries = xmlDoc.getElementsByTagName("MultiGeometry");
            for (let i = 0; i < multiGeometries.length; i++) {
                const mg = multiGeometries[i];
                const polys = mg.getElementsByTagName("Polygon");
                if (polys.length > 0) {
                    const multiCoords = [];
                    for (let j = 0; j < polys.length; j++) {
                        const poly = polys[j];
                        const outerBoundary = poly.getElementsByTagName("outerBoundaryIs");
                        const rings = [];
                        if (outerBoundary.length > 0) {
                            const outerCoordsTags = outerBoundary[0].getElementsByTagName("coordinates");
                            if (outerCoordsTags.length > 0) {
                                const outerCoords = parseCoordinatesString(outerCoordsTags[0].textContent);
                                if (outerCoords.length > 0) {
                                    rings.push(outerCoords);
                                    const innerBoundaries = poly.getElementsByTagName("innerBoundaryIs");
                                    for (let k = 0; k < innerBoundaries.length; k++) {
                                        const innerCoordsTags = innerBoundaries[k].getElementsByTagName("coordinates");
                                        if (innerCoordsTags.length > 0) {
                                            const innerCoords = parseCoordinatesString(innerCoordsTags[0].textContent);
                                            if (innerCoords.length > 0) {
                                                rings.push(innerCoords);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (rings.length > 0) {
                            multiCoords.push(rings);
                        }
                    }
                    if (multiCoords.length > 0) {
                        features.push({
                            type: "Feature",
                            geometry: {
                                type: "MultiPolygon",
                                coordinates: multiCoords
                            },
                            properties: {}
                        });
                    }
                }
            }

            // 2. Standalone Polygons
            const polygons = xmlDoc.getElementsByTagName("Polygon");
            for (let i = 0; i < polygons.length; i++) {
                const poly = polygons[i];
                
                let isInsideMultiGeometry = false;
                let parent = poly.parentNode;
                while (parent) {
                    if (parent.tagName === 'MultiGeometry') {
                        isInsideMultiGeometry = true;
                        break;
                    }
                    parent = parent.parentNode;
                }
                if (isInsideMultiGeometry) continue;

                const outerBoundary = poly.getElementsByTagName("outerBoundaryIs");
                const rings = [];
                
                if (outerBoundary.length > 0) {
                    const outerCoordsTags = outerBoundary[0].getElementsByTagName("coordinates");
                    if (outerCoordsTags.length > 0) {
                        const outerCoords = parseCoordinatesString(outerCoordsTags[0].textContent);
                        if (outerCoords.length > 0) {
                            rings.push(outerCoords);
                            const innerBoundaries = poly.getElementsByTagName("innerBoundaryIs");
                            for (let j = 0; j < innerBoundaries.length; j++) {
                                const innerCoordsTags = innerBoundaries[j].getElementsByTagName("coordinates");
                                if (innerCoordsTags.length > 0) {
                                    const innerCoords = parseCoordinatesString(innerCoordsTags[0].textContent);
                                    if (innerCoords.length > 0) {
                                        rings.push(innerCoords);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    const coordsTags = poly.getElementsByTagName("coordinates");
                    for (let j = 0; j < coordsTags.length; j++) {
                        const coords = parseCoordinatesString(coordsTags[j].textContent);
                        if (coords.length > 0) {
                            rings.push(coords);
                        }
                    }
                }
                
                if (rings.length > 0) {
                    features.push({
                        type: "Feature",
                        geometry: {
                            type: "Polygon",
                            coordinates: rings
                        },
                        properties: {}
                    });
                }
            }

            // 3. Points
            const points = xmlDoc.getElementsByTagName("Point");
            for (let i = 0; i < points.length; i++) {
                const pt = points[i];
                const coordsTags = pt.getElementsByTagName("coordinates");
                if (coordsTags.length > 0) {
                    const ptCoords = parseCoordinatesString(coordsTags[0].textContent);
                    if (ptCoords.length > 0) {
                        features.push({
                            type: "Feature",
                            geometry: {
                                type: "Point",
                                coordinates: ptCoords[0]
                            },
                            properties: {}
                        });
                    }
                }
            }

            // 4. LineStrings
            const lineStrings = xmlDoc.getElementsByTagName("LineString");
            for (let i = 0; i < lineStrings.length; i++) {
                const ls = lineStrings[i];
                const coordsTags = ls.getElementsByTagName("coordinates");
                if (coordsTags.length > 0) {
                    const coords = parseCoordinatesString(coordsTags[0].textContent);
                    if (coords.length > 0) {
                        features.push({
                            type: "Feature",
                            geometry: {
                                type: "LineString",
                                coordinates: coords
                            },
                            properties: {}
                        });
                    }
                }
            }

            if (features.length === 0) {
                const coordsTags = xmlDoc.getElementsByTagName("coordinates");
                for (let i = 0; i < coordsTags.length; i++) {
                    const coords = parseCoordinatesString(coordsTags[i].textContent);
                    if (coords.length > 0) {
                        if (coords.length >= 4 && coords[0][0] === coords[coords.length-1][0] && coords[0][1] === coords[coords.length-1][1]) {
                            features.push({
                                type: "Feature",
                                geometry: {
                                    type: "Polygon",
                                    coordinates: [coords]
                                },
                                properties: {}
                            });
                        } else {
                            features.push({
                                type: "Feature",
                                geometry: {
                                    type: "LineString",
                                    coordinates: coords
                                },
                                properties: {}
                            });
                        }
                    }
                }
            }

            if (features.length === 0) {
                throw new Error("File KML tidak mengandung data koordinat yang dapat dikenali.");
            }

            if (features.length === 1) {
                return features[0];
            }
            
            return {
                type: "FeatureCollection",
                features: features
            };
        }

        // GeoJSON / KML Upload Handler
        const geojsonUpload = document.getElementById('geojson-upload');
        if (geojsonUpload) {
            geojsonUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(evt) {
                    try {
                        let parsed;
                        const isKml = file.name.toLowerCase().endsWith('.kml') || evt.target.result.trim().startsWith('<');

                        if (isKml) {
                            parsed = parseKML(evt.target.result);
                        } else {
                            parsed = JSON.parse(evt.target.result);
                        }

                        let geometry = null;

                        if (parsed.type === 'FeatureCollection') {
                            if (parsed.features && parsed.features.length > 0) {
                                geometry = parsed.features[0].geometry;
                            }
                        } else if (parsed.type === 'Feature') {
                            geometry = parsed.geometry;
                        } else if (parsed.type === 'Polygon' || parsed.type === 'MultiPolygon' || parsed.type === 'Point') {
                            geometry = parsed;
                        }

                        if (!geometry) {
                            throw new Error("File tidak mengandung koordinat Point, Polygon, atau MultiPolygon yang valid.");
                        }

                        if (geometry.type === 'Point') {
                            // Update POI coordinates
                            const coords = geometry.coordinates;
                            const lat = coords[1];
                            const lng = coords[0];

                            latInput.value = lat.toFixed(8);
                            lngInput.value = lng.toFixed(8);

                            poiMarker.setLatLng([lat, lng]);
                            map.panTo([lat, lng]);

                            if (typeof window.updateRadiusRestriction === 'function') {
                                window.updateRadiusRestriction();
                            }

                            Swal.fire({
                                title: 'Berhasil!',
                                text: isKml ? 'Titik koordinat lokasi berhasil dimuat dari KML.' : 'Titik koordinat lokasi berhasil dimuat dari GeoJSON.',
                                icon: 'success',
                                confirmButtonColor: '#4f46e5',
                                timer: 3000
                            });
                        } else if (geometry.type === 'Polygon' || geometry.type === 'MultiPolygon') {
                            // Update/Set restriction zone
                            // Dispatch custom event to update Alpine state (make sure UI is synced)
                            window.dispatchEvent(new CustomEvent('set-restriction', {
                                detail: { hasRestriction: true, restrictionMode: 'manual' }
                            }));

                            setTimeout(() => {
                                // Clear current drawing layers
                                drawnItems.clearLayers();

                                // Render imported polygon
                                const geojsonLayer = L.geoJSON({
                                    type: "Feature",
                                    geometry: geometry,
                                    properties: {}
                                }, {
                                    style: {
                                        color: '#ef4444',
                                        fillColor: '#ef4444',
                                        fillOpacity: 0.2,
                                        weight: 2
                                    }
                                });

                                geojsonLayer.eachLayer(function(layer) {
                                    drawnItems.addLayer(layer);
                                });

                                // Fit bounds
                                map.fitBounds(drawnItems.getBounds(), { padding: [30, 30] });

                                // Sync the textarea
                                updateRestrictionGeoJSON();

                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: isKml ? 'Poligon zona restriksi berhasil dimuat dari KML.' : 'Poligon zona restriksi berhasil dimuat dari GeoJSON.',
                                    icon: 'success',
                                    confirmButtonColor: '#4f46e5',
                                    timer: 3000
                                });
                            }, 50);
                        } else {
                            throw new Error("Format jenis koordinat spasial tidak didukung.");
                        }
                    } catch (err) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal membaca file: ' + err.message,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                };
                reader.readAsText(file);
                geojsonUpload.value = '';
            });
        }
    });
</script>
@endpush

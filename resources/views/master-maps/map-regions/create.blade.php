@extends('layouts.app')

@section('title', 'Tambah Batas Wilayah')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Tambah Batas Wilayah</h1>
        <p class="text-xs text-slate-500 font-medium tracking-tight">Definisikan region administratif baru beserta batas poligon spasialnya.</p>
    </div>

    <div class="premium-card p-6 md:p-8">
        <form action="{{ route('map-regions.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @csrf
            
            {{-- Form Fields --}}
            <div class="space-y-5">
                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Nama Wilayah</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Kecamatan Way Kenanga, Tiyuh Indraloka I" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                    @error('name') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Kode Wilayah</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: 18.12.06"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                        @error('code') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Tingkat Wilayah</label>
                        <select name="level" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all text-slate-700 dark:text-slate-300">
                            <option value="desa">Tiyuh/Desa</option>
                            <option value="kecamatan">Kecamatan</option>
                            <option value="kabupaten">Kabupaten</option>
                        </select>
                        @error('level') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Wilayah Induk (Parent)</label>
                        <select name="parent_id"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all text-slate-700 dark:text-slate-300">
                            <option value="">— Tidak Ada (Level Kabupaten) —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->level }})</option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Warna Peta</label>
                        <div class="flex gap-2">
                            <input type="color" name="color" value="#3b82f6"
                                class="h-11 w-14 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl cursor-pointer" />
                            <input type="text" id="color-hex" placeholder="#3b82f6" readonly
                                class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-800/80 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-mono font-bold dark:text-white" />
                        </div>
                        @error('color') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Luas Wilayah (KM²)</label>
                    <input type="number" step="0.0001" name="area_km2" value="{{ old('area_km2') }}" placeholder="Contoh: 14.52"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                    @error('area_km2') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Hidden input for GeoJSON data --}}
                <textarea name="geojson" id="geojson" class="hidden"></textarea>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all">
                        Simpan Wilayah
                    </button>
                    <a href="{{ route('map-regions.index') }}" class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Batal
                    </a>
                </div>
            </div>

            {{-- Drawing Map Container --}}
            <div class="flex flex-col gap-3">
                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1 block">Gambar Batas Poligon</label>
                    <p class="text-[10px] text-slate-400 font-medium">Gunakan alat gambar di peta sebelah kanan untuk mendefinisikan batas poligon geospasial.</p>
                </div>
                <div id="map-draw" class="w-full h-96 rounded-2xl border border-black/10 dark:border-white/10 overflow-hidden shadow-inner relative z-10"></div>
                <div class="flex flex-col sm:flex-row justify-between gap-3 sm:items-center bg-slate-50 dark:bg-slate-800/30 p-3.5 rounded-xl border border-black/[0.03] dark:border-white/[0.03]">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status Gambar</span>
                        <span id="draw-status" class="inline-block text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-rose-500/10 text-rose-500 border border-rose-500/10 mt-1 max-w-max">
                            Belum Menggambar
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
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sync color inputs
        const colorInput = document.querySelector('input[name="color"]');
        const colorHex = document.getElementById('color-hex');
        colorHex.value = colorInput.value;
        colorInput.addEventListener('input', (e) => {
            colorHex.value = e.target.value;
        });

        // Initialize Leaflet Map
        const map = L.map('map-draw', {
            zoomControl: false
        }).setView([-4.444, 105.045], 11);

        // Add standard zoom control
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

        // FeatureGroup to store drawn shapes
        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Add Leaflet Draw controls
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
                        message: '<strong>Poligon saling berpotongan!</strong> Batas wilayah tidak boleh tumpang tindih.'
                    },
                    shapeOptions: {
                        color: '#3b82f6',
                        fillColor: '#3b82f6',
                        fillOpacity: 0.3,
                        weight: 2
                    }
                }
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });
        map.addControl(drawControl);

        // Status badge
        const drawStatus = document.getElementById('draw-status');
        const geojsonArea = document.getElementById('geojson');

        function updateGeoJSON() {
            const layers = drawnItems.getLayers();
            if (layers.length > 0) {
                const geojson = layers[0].toGeoJSON();
                // We only need the geometry object
                geojsonArea.value = JSON.stringify(geojson.geometry);
                
                drawStatus.textContent = 'Poligon Siap';
                drawStatus.className = 'text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/10';
            } else {
                geojsonArea.value = '';
                drawStatus.textContent = 'Belum Menggambar';
                drawStatus.className = 'text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-rose-500/10 text-rose-500 border border-rose-500/10';
            }
        }

        // Draw created handler
        map.on(L.Draw.Event.CREATED, function (event) {
            // Remove previous shapes if any (only allow 1 polygon per region)
            drawnItems.clearLayers();
            
            const layer = event.layer;
            drawnItems.addLayer(layer);
            updateGeoJSON();
        });

        // Draw edited handler
        map.on(L.Draw.Event.EDITED, function (event) {
            updateGeoJSON();
        });

        // Draw deleted handler
        map.on(L.Draw.Event.DELETED, function (event) {
            updateGeoJSON();
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
                        } else if (parsed.type === 'Polygon' || parsed.type === 'MultiPolygon') {
                            geometry = parsed;
                        }

                        if (!geometry || (geometry.type !== 'Polygon' && geometry.type !== 'MultiPolygon')) {
                            throw new Error("File tidak mengandung koordinat Poligon atau MultiPoligon yang valid.");
                        }

                        // Clear any existing drawings
                        drawnItems.clearLayers();

                        // Add imported geometry to map
                        const geojsonLayer = L.geoJSON({
                            type: "Feature",
                            geometry: geometry,
                            properties: {}
                        }, {
                            style: {
                                color: colorInput ? colorInput.value : '#3b82f6',
                                fillColor: colorInput ? colorInput.value : '#3b82f6',
                                fillOpacity: 0.3,
                                weight: 2
                            }
                        });

                        geojsonLayer.eachLayer(function(layer) {
                            drawnItems.addLayer(layer);
                        });

                        // Fit map bounds to show the imported polygon
                        map.fitBounds(drawnItems.getBounds(), { padding: [30, 30] });

                        // Update the hidden input
                        updateGeoJSON();

                        Swal.fire({
                            title: 'Berhasil!',
                            text: isKml ? 'File KML berhasil dimuat ke peta.' : 'File GeoJSON berhasil dimuat ke peta.',
                            icon: 'success',
                            confirmButtonColor: '#4f46e5',
                            timer: 3000
                        });
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

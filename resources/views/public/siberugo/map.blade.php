<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Interaktif SIBERUGO — Mal Pelayanan Publik Tubaba</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-mpp.png') }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.15) rgba(15, 23, 42, 0.3);
        }

        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #0f172a;
        }

        #map {
            width: 100%;
            height: 100vh;
            z-index: 1;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.5);
        }

        /* Customize Leaflet elements to dark theme */
        .leaflet-container :focus,
        .leaflet-interactive:focus,
        path.leaflet-interactive:focus,
        .leaflet-marker-icon:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .leaflet-bar {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
            border-radius: 12px !important;
            overflow: hidden;
        }

        .leaflet-bar a {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            transition: all 0.2s ease;
        }

        .leaflet-bar a:hover {
            background-color: #334155 !important;
            color: #ffffff !important;
        }

        .leaflet-popup-content-wrapper {
            background: #1e293b !important;
            color: #f1f5f9 !important;
            border-radius: 16px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
            font-family: 'Outfit', sans-serif;
        }

        .leaflet-popup-tip {
            background: #1e293b !important;
        }

        .category-pill {
            transition: all 0.2s ease;
        }

        .category-pill.active {
            background-color: var(--pill-color);
            color: #ffffff;
        }

        /* Fix custom POI marker positioning — critical for correct anchor during zoom */
        .custom-poi-icon-marker {
            background: transparent !important;
            border: none !important;
        }

        .custom-poi-icon-marker>div {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }

        /* Pulse animation for restriction zones */
        .restriction-highlight {
            stroke-dasharray: 5, 5;
            animation: dash 10s linear infinite;
        }

        @keyframes dash {
            to {
                stroke-dashoffset: -100;
            }
        }

        /* GPS pulse marker */
        .custom-gps-icon {
            background: transparent !important;
            border: none !important;
        }

        .gps-pulse-marker {
            width: 18px;
            height: 18px;
            background: #3b82f6;
            border: 3.5px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.9);
            position: relative;
        }

        .gps-pulse-marker::after {
            content: '';
            width: 38px;
            height: 38px;
            border: 3px solid #3b82f6;
            border-radius: 50%;
            position: absolute;
            top: -13px;
            left: -13px;
            animation: gps-pulse 2s infinite;
            opacity: 0;
        }

        @keyframes gps-pulse {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        /* Check pin marker */
        .custom-check-pin-icon {
            background: transparent !important;
            border: none !important;
        }

        .check-pin-marker {
            width: 28px;
            height: 28px;
            background: #10b981;
            /* Default emerald */
            border: 3px solid #ffffff;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounce-pin 0.3s ease-out;
        }

        .check-pin-marker iconify-icon {
            transform: rotate(45deg);
            color: white;
            font-size: 14px;
        }

        @keyframes bounce-pin {
            0% {
                transform: translateY(-20px) rotate(-45deg);
                opacity: 0;
            }

            100% {
                transform: translateY(0) rotate(-45deg);
                opacity: 1;
            }
        }
    </style>
</head>

<body x-data="mapApp()" x-init="initApp()" class="relative min-h-screen">

    <!-- Mobile Sidebar Toggle Button -->
    <div class="absolute top-4 left-4 z-[999] lg:hidden pointer-events-auto">
        <button @click="sidebarOpen = true" x-show="!sidebarOpen"
            class="w-12 h-12 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-white/10 flex items-center justify-center text-white shadow-2xl hover:bg-slate-800 transition active:scale-95">
            <iconify-icon icon="lucide:menu" class="text-xl"></iconify-icon>
        </button>
    </div>

    <!-- Sidebar & Controls Overlay -->
    <div class="absolute inset-y-0 left-0 z-[1000] p-4 pointer-events-none flex gap-4 transition-transform duration-300"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <!-- Sidebar Search and Details Panel -->
        <div
            class="glass-panel w-[calc(100vw-2rem)] sm:w-96 rounded-[2rem] p-6 flex flex-col justify-between pointer-events-auto shrink-0 h-full max-h-[calc(100vh-2rem)]">
            <div class="flex flex-col gap-5 overflow-y-auto pr-1">
                <!-- Branding Header -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('siberugo.index') }}" class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg overflow-hidden bg-slate-800/80 flex items-center justify-center border border-white/10">
                            <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo"
                                class="w-6 h-6 object-contain">
                        </div>
                        <div>
                            <h1 class="text-xs font-black uppercase tracking-wider text-white">SIBERUGO</h1>
                            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Kab. Tulang Bawang
                                Barat</p>
                        </div>
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('siberugo.index') }}" class="text-slate-400 hover:text-white transition p-1"
                            title="Kembali ke Beranda">
                            <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
                        </a>
                        <button @click="sidebarOpen = false"
                            class="lg:hidden text-slate-400 hover:text-white transition p-1" title="Sembunyikan Panel">
                            <iconify-icon icon="lucide:chevrons-left" class="text-xl"></iconify-icon>
                        </button>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <iconify-icon icon="lucide:search" class="text-slate-400 text-sm"></iconify-icon>
                    </span>
                    <input type="text" x-model="searchQuery" @input="filterPOIs()"
                        placeholder="Cari fasilitas, dinas, sekolah..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-white/5 rounded-2xl text-xs font-bold placeholder-slate-500 text-white focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition">
                </div>

                <!-- Cek Lokasi Mandiri Module -->
                <div
                    class="flex flex-col gap-2.5 bg-gradient-to-br from-blue-950/40 to-slate-900/40 p-4 rounded-3xl border border-blue-500/20 shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="lucide:map-pin-check" class="text-blue-400 text-base"></iconify-icon>
                            <h3 class="text-xs font-black text-white uppercase tracking-wider">Cek Tata Ruang Mandiri
                            </h3>
                        </div>
                        <span class="flex h-2 w-2 relative" x-show="checkLocationMode">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Klik tombol di bawah lalu klik
                        pada peta untuk memeriksa apakah lokasi tersebut bebas restriksi dan diperbolehkan membangun
                        usaha.</p>

                    <div class="grid grid-cols-2 gap-2 mt-1">
                        <button type="button" @click="toggleCheckMode()"
                            :class="checkLocationMode ?
                                'bg-emerald-600 text-white border border-emerald-500 shadow-lg shadow-emerald-500/20' :
                                'bg-slate-800/80 text-slate-300 hover:bg-slate-700 border border-white/5'"
                            class="px-2.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-1.5 pointer-events-auto">
                            <iconify-icon
                                :icon="checkLocationMode ? 'lucide:check-circle' : 'lucide:mouse-pointer-click'"></iconify-icon>
                            <span x-text="checkLocationMode ? 'Mode Aktif' : 'Mulai Cek'"></span>
                        </button>
                        <button type="button" @click="locateUser()"
                            class="px-2.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider bg-blue-600/90 text-white hover:bg-blue-600 border border-blue-500/30 transition-all duration-300 flex items-center justify-center gap-1.5 shadow-lg shadow-blue-500/10 pointer-events-auto">
                            <iconify-icon icon="lucide:gps" class="animate-pulse"></iconify-icon>
                            <span>Lokasi Saya</span>
                        </button>
                    </div>
                </div>

                <!-- Layers Quick Toggle -->
                <div class="flex flex-col gap-2 bg-slate-800/20 p-4 rounded-2xl border border-white/5">
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Layer Peta</h3>

                    <!-- Layer 0: Batas Kabupaten -->
                    <label class="flex items-center justify-between cursor-pointer py-1.5">
                        <span class="text-xs font-semibold text-slate-300">Batas Kabupaten</span>
                        <input type="checkbox" x-model="activeLayers.kabupaten" @change="toggleLayer('kabupaten')"
                            class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 relative">
                        </div>
                    </label>

                    <!-- Layer 1: Batas Kecamatan -->
                    <label class="flex items-center justify-between cursor-pointer py-1.5">
                        <span class="text-xs font-semibold text-slate-300">Batas Kecamatan</span>
                        <input type="checkbox" x-model="activeLayers.kecamatan" @change="toggleLayer('kecamatan')"
                            class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 relative">
                        </div>
                    </label>

                    <!-- Layer 2: Batas Desa -->
                    <label class="flex items-center justify-between cursor-pointer py-1.5">
                        <span class="text-xs font-semibold text-slate-300">Batas Desa/Tiyuh</span>
                        <input type="checkbox" x-model="activeLayers.desa" @change="toggleLayer('desa')"
                            class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 relative">
                        </div>
                    </label>

                    <!-- Layer 3: Zonasi Tata Ruang -->
                    <label class="flex items-center justify-between cursor-pointer py-1.5">
                        <span class="text-xs font-semibold text-slate-300">Zonasi Tata Ruang</span>
                        <input type="checkbox" x-model="activeLayers.zonasi" @change="toggleLayer('zonasi')"
                            class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 relative">
                        </div>
                    </label>

                    <!-- Layer 4: Titik Lokasi -->
                    <label class="flex items-center justify-between cursor-pointer py-1.5">
                        <span class="text-xs font-semibold text-slate-300">Titik Lokasi (POI)</span>
                        <input type="checkbox" x-model="activeLayers.poi" @change="toggleLayer('poi')"
                            class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 relative">
                        </div>
                    </label>

                    <!-- Map Style Selector -->
                    <div class="flex flex-col gap-2 py-1.5 border-t border-white/5 mt-1 pt-2">
                        <span class="text-xs font-semibold text-slate-300 flex items-center gap-1.5">
                            <iconify-icon icon="lucide:layers" class="text-slate-400"></iconify-icon>
                            Gaya Peta (Base Map)
                        </span>
                        <div class="grid grid-cols-2 gap-2 mt-1">
                            <button type="button" @click="setMapStyle('dark')"
                                :class="mapStyle === 'dark' ?
                                    'bg-blue-600 text-white border border-blue-500 shadow-lg shadow-blue-500/20' :
                                    'bg-slate-800/40 text-slate-400 hover:bg-slate-800 hover:text-slate-200 border border-transparent'"
                                class="px-2 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all duration-300">
                                Malam (Dark)
                            </button>
                            <button type="button" @click="setMapStyle('light')"
                                :class="mapStyle === 'light' ?
                                    'bg-blue-600 text-white border border-blue-500 shadow-lg shadow-blue-500/20' :
                                    'bg-slate-800/40 text-slate-400 hover:bg-slate-800 hover:text-slate-200 border border-transparent'"
                                class="px-2 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all duration-300">
                                Siang (Light)
                            </button>
                            <button type="button" @click="setMapStyle('google-roadmap')"
                                :class="mapStyle === 'google-roadmap' ?
                                    'bg-blue-600 text-white border border-blue-500 shadow-lg shadow-blue-500/20' :
                                    'bg-slate-800/40 text-slate-400 hover:bg-slate-800 hover:text-slate-200 border border-transparent'"
                                class="px-2 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all duration-300">
                                Google Jalan
                            </button>
                            <button type="button" @click="setMapStyle('google-satellite')"
                                :class="mapStyle === 'google-satellite' ?
                                    'bg-blue-600 text-white border border-blue-500 shadow-lg shadow-blue-500/20' :
                                    'bg-slate-800/40 text-slate-400 hover:bg-slate-800 hover:text-slate-200 border border-transparent'"
                                class="px-2 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all duration-300">
                                Google Satelit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- POI Category Filter -->
                <div x-show="activeLayers.poi">
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Filter Kategori POI
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <button @click="toggleCategory('all')"
                            :class="selectedCategory === 'all' ? 'bg-slate-700 text-white' :
                                'bg-slate-800/40 text-slate-400'"
                            class="px-3.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition">
                            Semua
                        </button>
                        @foreach ($categories as $cat)
                            <button @click="toggleCategory('{{ $cat->slug }}')"
                                :class="selectedCategory === '{{ $cat->slug }}' ? 'text-white' :
                                    'bg-slate-800/40 text-slate-400 hover:bg-slate-800'"
                                :style="selectedCategory === '{{ $cat->slug }}' ?
                                { backgroundColor: '{{ $cat->color ?? '#3b82f6' }}' } : {}"
                                class="px-3.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition flex items-center gap-1.5">
                                <iconify-icon icon="{{ $cat->icon }}"></iconify-icon>
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- POI List -->
                <div x-show="activeLayers.poi">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Daftar Lokasi</h3>
                    <div class="flex flex-col gap-2.5 max-h-48 overflow-y-auto custom-scrollbar">
                        <template x-for="loc in filteredPOIs" :key="loc.id">
                            <button @click="focusLocation(loc)"
                                class="flex items-start text-left gap-3 p-3 rounded-2xl bg-slate-800/40 hover:bg-slate-800 border border-white/5 transition w-full">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                    :style="{ backgroundColor: loc.category.color + '20' }">
                                    <iconify-icon :icon="loc.category.icon"
                                        :style="{ color: loc.category.color }"></iconify-icon>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-black text-white truncate" x-text="loc.name"></h4>
                                    <p class="text-xs text-slate-400 truncate mt-0.5" x-text="loc.address || '-'"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Details Panel / Footer -->
            <div class="border-t border-white/5 pt-4 mt-4">
                <!-- Case 1: Check Results Active -->
                <template x-if="checkResults">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md bg-blue-950/40 text-blue-400 border border-blue-500/20">
                                    Hasil Analisis Lokasi
                                </span>
                                <h3 class="text-xs font-bold text-slate-400 mt-2">
                                    GPS: <span class="text-white font-mono"
                                        x-text="checkResults.latitude + ', ' + checkResults.longitude"></span>
                                </h3>
                            </div>
                            <button @click="clearCheck()" class="text-slate-500 hover:text-white transition"
                                title="Hapus Pin Cek">
                                <iconify-icon icon="lucide:x-circle" class="text-xl"></iconify-icon>
                            </button>
                        </div>

                        <!-- Status Badge -->
                        <div :class="checkResults.isAllowed ? 'bg-emerald-950/20 border-emerald-500/20 text-emerald-400' :
                            'bg-red-950/20 border-red-500/20 text-red-400'"
                            class="flex items-center gap-2.5 p-3 rounded-2xl border text-xs font-black uppercase tracking-wider">
                            <iconify-icon
                                :icon="checkResults.isAllowed ? 'lucide:check-circle-2' : 'lucide:alert-triangle'"
                                class="text-lg"></iconify-icon>
                            <span
                                x-text="checkResults.isAllowed ? 'Sesuai / Diperbolehkan' : 'Terbatas / Ada Larangan'"></span>
                        </div>

                        <!-- Zonasi Tata Ruang -->
                        <div class="mt-1">
                            <h4
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                <iconify-icon icon="lucide:globe" class="text-slate-500"></iconify-icon>
                                Zonasi Tata Ruang
                            </h4>
                            <template x-if="checkResults.zones.length > 0">
                                <div class="space-y-2">
                                    <template x-for="zone in checkResults.zones">
                                        <div class="p-3 bg-slate-800/40 border border-white/5 rounded-2xl">
                                            <div class="flex items-center gap-2">
                                                <span class="w-3 h-3 rounded-full border border-white/10"
                                                    :style="{ backgroundColor: zone.color || '#f97316' }"></span>
                                                <span class="text-xs font-black text-white" x-text="zone.name"></span>
                                            </div>
                                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider mt-1"
                                                x-text="zone.type_name"></p>
                                            <p class="text-xs text-slate-300 mt-1.5 leading-relaxed"
                                                x-text="zone.description || '-'"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="checkResults.zones.length === 0">
                                <p
                                    class="text-xs text-slate-500 italic p-3 bg-slate-800/20 rounded-2xl border border-white/5">
                                    Tidak terdeteksi di dalam zonasi tata ruang khusus.</p>
                            </template>
                        </div>

                        <!-- Restriksi POI -->
                        <div class="mt-1">
                            <h4
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                <iconify-icon icon="lucide:shield-ban" class="text-slate-500"></iconify-icon>
                                Restriksi POI / Larangan
                            </h4>
                            <template x-if="checkResults.poiRestrictions.length > 0">
                                <div class="space-y-2.5">
                                    <template x-for="rest in checkResults.poiRestrictions">
                                        <div class="p-3 bg-red-950/10 border border-red-500/10 rounded-2xl">
                                            <p class="text-xs font-bold text-red-400"
                                                x-text="'Dekat dengan: ' + rest.location_name"></p>
                                            <div class="flex flex-wrap gap-1.5 mt-2">
                                                <template x-for="act in rest.restricted_activities"
                                                    :key="act">
                                                    <span
                                                        class="px-2 py-1 rounded bg-red-900/30 text-red-300 text-[10px] font-bold uppercase tracking-wider"
                                                        x-text="act"></span>
                                                </template>
                                            </div>
                                            <p class="text-xs text-slate-300 italic mt-2 leading-relaxed"
                                                x-text="'Catatan: ' + rest.notes"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="checkResults.poiRestrictions.length === 0">
                                <p
                                    class="text-xs text-slate-500 italic p-3 bg-slate-800/20 rounded-2xl border border-white/5">
                                    Aman. Lokasi ini tidak berada di dalam wilayah pembatasan/larangan usaha POI.</p>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Case 2: Focused POI Active (when not checking) -->
                <template x-if="!checkResults && focusedPOI">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[11px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md"
                                    :style="{ backgroundColor: (focusedPOI.category.color || '#3b82f6') + '20',
                                        color: focusedPOI.category.color || '#3b82f6' }"
                                    x-text="focusedPOI.category.name"></span>
                                <h3 class="text-lg font-black text-white mt-2" x-text="focusedPOI.name"></h3>
                            </div>
                            <button @click="clearFocus()" class="text-slate-500 hover:text-white transition">
                                <iconify-icon icon="lucide:x" class="text-lg"></iconify-icon>
                            </button>
                        </div>

                        <template x-if="focusedPOI.photo_url">
                            <div class="w-full h-32 rounded-xl overflow-hidden mt-1 border border-white/10">
                                <img :src="focusedPOI.photo_url" alt="POI Photo" class="w-full h-full object-cover">
                            </div>
                        </template>

                        <div class="text-slate-300 text-sm font-medium space-y-2 mt-2">
                            <div class="flex gap-2.5 items-start">
                                <iconify-icon icon="lucide:map-pin" class="text-slate-500 mt-0.5"></iconify-icon>
                                <span x-text="focusedPOI.address || '-'"></span>
                            </div>
                            <template x-if="focusedPOI.description">
                                <div class="flex gap-2.5 items-start">
                                    <iconify-icon icon="lucide:info" class="text-slate-500 mt-0.5"></iconify-icon>
                                    <span x-text="focusedPOI.description"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Radius Restrictions -->
                        <template x-if="focusedPOI.restrictions && focusedPOI.restrictions.length > 0">
                            <div
                                class="mt-2 bg-red-950/20 border border-red-500/20 p-4 rounded-2xl flex flex-col gap-2">
                                <div class="flex items-center gap-2 text-red-400">
                                    <iconify-icon icon="lucide:shield-alert" class="text-base"></iconify-icon>
                                    <span class="text-xs font-black uppercase tracking-wider">Zona Restriksi
                                        Radius</span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium">Dalam radius restriksi titik ini,
                                    aktivitas berikut dilarang atau dibatasi:</p>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    <template x-for="act in focusedPOI.restrictions[0].restricted_activities"
                                        :key="act">
                                        <span
                                            class="px-2 py-1 rounded bg-red-900/30 text-red-300 text-[11px] font-bold uppercase tracking-wider"
                                            x-text="act"></span>
                                    </template>
                                </div>
                                <template x-if="focusedPOI.restrictions[0].notes">
                                    <p class="text-[11px] text-slate-300 italic mt-1"
                                        x-text="'Catatan: ' + focusedPOI.restrictions[0].notes"></p>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Case 3: Empty State (no POI focus & no check results) -->
                <template x-if="!checkResults && !focusedPOI">
                    <div class="text-center py-4">
                        <iconify-icon icon="lucide:info" class="text-slate-600 text-2xl mb-2"></iconify-icon>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Klik wilayah atau
                            titik untuk melihat detail informasi.</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- MPP Logo Overlay (bottom-right) -->
    <div class="absolute bottom-5 right-5 z-[1000] pointer-events-none">
        <div
            class="bg-slate-900/80 backdrop-blur-md border border-white/10 p-2.5 rounded-2xl shadow-2xl flex items-center justify-center">
            <img src="{{ asset('assets/images/logo-mpp.png') }}" alt="Logo MPP"
                class="h-8 md:h-10 w-auto object-contain opacity-95">
        </div>
    </div>

    <!-- Leaflet & Script Integration -->
    <script>
        // Point-in-polygon spatial logic using Ray Casting Algorithm
        function isPointInGeoJSON(lat, lng, geometry) {
            if (!geometry) return false;

            const pt = [lat, lng]; // [lat, lng]

            const inRing = (point, ring) => {
                // GeoJSON coordinates are [longitude, latitude]
                const x = point[1]; // longitude
                const y = point[0]; // latitude
                let inside = false;
                for (let i = 0, j = ring.length - 1; i < ring.length; j = i++) {
                    const xi = ring[i][0],
                        yi = ring[i][1];
                    const xj = ring[j][0],
                        yj = ring[j][1];

                    const intersect = ((yi > y) !== (yj > y)) &&
                        (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                    if (intersect) inside = !inside;
                }
                return inside;
            };

            const inPolygon = (point, rings) => {
                if (!inRing(point, rings[0])) return false;
                for (let i = 1; i < rings.length; i++) {
                    if (inRing(point, rings[i])) return false; // Inside a hole
                }
                return true;
            };

            if (geometry.type === 'Polygon') {
                return inPolygon(pt, geometry.coordinates);
            } else if (geometry.type === 'MultiPolygon') {
                for (const poly of geometry.coordinates) {
                    if (inPolygon(pt, poly)) return true;
                }
            }
            return false;
        }

        function mapApp() {
            // Store Leaflet and Google Mutant instances as non-reactive local variables
            // inside the closure to prevent Alpine JS reactivity proxies from breaking
            // the internal Leaflet map layer state and event synchronization.
            let map = null;
            let googleMutantLayer = null;
            let tileLayer = null;
            let layers = {
                kabupaten: null,
                kecamatan: null,
                desa: null,
                zonasi: null,
                poi: []
            };
            let restrictionLayer = null;
            let poiMarkers = {};
            let userLocMarker = null;
            let checkLocationPin = null;

            return {
                searchQuery: '',
                sidebarOpen: window.innerWidth >= 1024,
                selectedCategory: 'all',
                activeLayers: {
                    kabupaten: true,
                    kecamatan: true,
                    desa: true,
                    zonasi: true,
                    poi: true
                },
                regionsData: null,
                zonesData: null,
                locationsData: [],
                filteredPOIs: [],
                focusedPOI: null,
                mapStyle: 'light',
                checkLocationMode: false,
                checkResults: null,

                async initApp() {
                    // Initialize Leaflet Map centered on Tulang Bawang Barat
                    map = L.map('map', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView([-4.444, 105.045], 11);

                    // Add zoom control at top right to avoid overlapping the logo
                    L.control.zoom({
                        position: 'topright'
                    }).addTo(map);

                    // Light map tile provider (default)
                    tileLayer = L.tileLayer(
                    'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        maxZoom: 19
                    }).addTo(map);

                    // Load Google Maps API & GoogleMutant dynamically in the background
                    window.googleMapsReady = function() {
                        const mutantScript = document.createElement('script');
                        mutantScript.src =
                            'https://unpkg.com/leaflet.gridlayer.googlemutant@latest/dist/Leaflet.GoogleMutant.js';
                        document.head.appendChild(mutantScript);
                    };

                    const googleMapsScript = document.createElement('script');
                    googleMapsScript.src =
                        'https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&loading=async&callback=googleMapsReady';
                    googleMapsScript.async = true;
                    googleMapsScript.defer = true;
                    document.head.appendChild(googleMapsScript);

                    // Listen to map clicks for check mode
                    map.on('click', (e) => {
                        if (this.checkLocationMode) {
                            this.handleMapClickForCheck(e.latlng);
                        }
                    });

                    // Fetch data
                    await this.loadMapData();
                },

                setMapStyle(style) {
                    this.mapStyle = style;

                    // Remove non-Google tile layer if it exists
                    if (tileLayer) {
                        map.removeLayer(tileLayer);
                        tileLayer = null;
                    }

                    if (style === 'dark') {
                        // Remove Google mutant layer from display, but keep the instance reference
                        if (googleMutantLayer) {
                            map.removeLayer(googleMutantLayer);
                        }
                        tileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                            maxZoom: 19
                        }).addTo(map);
                    } else if (style === 'light') {
                        // Remove Google mutant layer from display, but keep the instance reference
                        if (googleMutantLayer) {
                            map.removeLayer(googleMutantLayer);
                        }
                        tileLayer = L.tileLayer(
                        'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            maxZoom: 19
                        }).addTo(map);
                    } else if (style.startsWith('google-')) {
                        const type = style.replace('google-', '');
                        if (typeof L.gridLayer.googleMutant === 'function') {
                            if (googleMutantLayer) {
                                // Dynamic map type change using setMapTypeId to prevent synchronization loss
                                if (googleMutantLayer._mutant) {
                                    googleMutantLayer._mutant.setMapTypeId(type);
                                } else {
                                    // Fallback: destroy and recreate if mutant instance is not yet fully initialized
                                    map.removeLayer(googleMutantLayer);
                                    googleMutantLayer = L.gridLayer.googleMutant({
                                        type: type
                                    });
                                }
                                // Ensure the layer is added back to the map
                                if (!map.hasLayer(googleMutantLayer)) {
                                    googleMutantLayer.addTo(map);
                                }
                            } else {
                                // First-time creation
                                googleMutantLayer = L.gridLayer.googleMutant({
                                    type: type
                                }).addTo(map);
                            }
                        } else {
                            // Fallback to CartoDB Dark and show notification
                            tileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                                maxZoom: 19
                            }).addTo(map);

                            // Reset state selection
                            this.mapStyle = 'dark';

                            Swal.fire({
                                icon: 'info',
                                title: 'Memuat Peta',
                                text: 'Sedang memuat peta Google Maps. Silakan coba sesaat lagi.',
                                background: '#1e293b',
                                color: '#f1f5f9',
                                confirmButtonColor: '#3b82f6',
                                confirmButtonText: 'OK'
                            });
                        }
                    }

                    // Force Leaflet to recalculate size, reset pane offsets, and lock all layers to coordinates
                    setTimeout(() => {
                        map.invalidateSize();
                        map.setView(map.getCenter(), map.getZoom(), {
                            animate: false
                        });
                    }, 50);
                },

                async loadMapData() {
                    try {
                        // 1. Fetch Regions (Kecamatan & Desa boundaries)
                        const regionsRes = await fetch("{{ route('api.map.regions') }}");
                        this.regionsData = await regionsRes.json();

                        // 2. Fetch Zones (Zonasi Wilayah)
                        const zonesRes = await fetch("{{ route('api.map.zones') }}");
                        this.zonesData = await zonesRes.json();

                        // 3. Fetch Points of Interest (POIs)
                        const locsRes = await fetch("{{ route('api.map.locations') }}");
                        this.locationsData = await locsRes.json();
                        this.filteredPOIs = this.locationsData;

                        // Render layers
                        this.renderLayers();

                    } catch (err) {
                        console.error('Failed to load map spatial data:', err);
                    }
                },

                renderLayers() {
                    const self = this;

                    // Clear existing layers if any
                    if (layers.kabupaten) map.removeLayer(layers.kabupaten);
                    if (layers.kecamatan) map.removeLayer(layers.kecamatan);
                    if (layers.desa) map.removeLayer(layers.desa);
                    if (layers.zonasi) map.removeLayer(layers.zonasi);
                    layers.poi.forEach(m => map.removeLayer(m));
                    layers.poi = [];
                    poiMarkers = {};

                    // 0. Render Kabupaten
                    if (this.regionsData && this.activeLayers.kabupaten) {
                        layers.kabupaten = L.geoJSON(this.regionsData, {
                            filter: (feature) => feature.properties.level === 'kabupaten',
                            style: (feature) => ({
                                fillColor: feature.properties.color || '#1e293b',
                                fillOpacity: 0.05,
                                color: feature.properties.color || '#1e293b',
                                weight: 3,
                                opacity: 1,
                                dashArray: '6, 6'
                            }),
                            onEachFeature: (feature, layer) => {
                                layer.bindTooltip(`Kabupaten ${feature.properties.name}`, {
                                    sticky: true
                                });
                                layer.on('click', (e) => {
                                    if (self.checkLocationMode) {
                                        L.DomEvent.stopPropagation(e);
                                        self.handleMapClickForCheck(e.latlng);
                                    }
                                });
                            }
                        }).addTo(map);
                    }

                    // 1. Render Kecamatan
                    if (this.regionsData && this.activeLayers.kecamatan) {
                        layers.kecamatan = L.geoJSON(this.regionsData, {
                            filter: (feature) => feature.properties.level === 'kecamatan',
                            style: (feature) => ({
                                fillColor: feature.properties.color || '#3b82f6',
                                fillOpacity: 0.25,
                                color: feature.properties.color || '#3b82f6',
                                weight: 2,
                                opacity: 1
                            }),
                            onEachFeature: (feature, layer) => {
                                layer.bindTooltip(`Kecamatan ${feature.properties.name}`, {
                                    sticky: true
                                });
                                layer.on('click', (e) => {
                                    if (self.checkLocationMode) {
                                        L.DomEvent.stopPropagation(e);
                                        self.handleMapClickForCheck(e.latlng);
                                    }
                                });
                            }
                        }).addTo(map);
                    }

                    // 2. Render Desa/Tiyuh
                    if (this.regionsData && this.activeLayers.desa) {
                        layers.desa = L.geoJSON(this.regionsData, {
                            filter: (feature) => feature.properties.level === 'desa',
                            style: (feature) => ({
                                fillColor: feature.properties.color || '#8b5cf6',
                                fillOpacity: 0.15,
                                color: feature.properties.color || '#8b5cf6',
                                weight: 1.5,
                                opacity: 0.8
                            }),
                            onEachFeature: (feature, layer) => {
                                layer.bindTooltip(`Tiyuh/Desa ${feature.properties.name}`, {
                                    sticky: true
                                });
                                layer.on('click', (e) => {
                                    if (self.checkLocationMode) {
                                        L.DomEvent.stopPropagation(e);
                                        self.handleMapClickForCheck(e.latlng);
                                    }
                                });
                            }
                        }).addTo(map);
                    }

                    // 3. Render Zonasi Wilayah
                    if (this.zonesData && this.activeLayers.zonasi) {
                        layers.zonasi = L.geoJSON(this.zonesData, {
                            style: (feature) => ({
                                fillColor: feature.properties.color || '#f97316',
                                fillOpacity: 0.35,
                                color: feature.properties.color || '#f97316',
                                weight: 1.5,
                                opacity: 0.9
                            }),
                            onEachFeature: (feature, layer) => {
                                const popupContent = `
                                    <div class="p-1">
                                        <h4 class="text-sm font-black text-white uppercase tracking-tight">${feature.properties.name}</h4>
                                        <p class="text-xs text-blue-400 font-bold uppercase tracking-widest mt-1">${feature.properties.type_name}</p>
                                        <p class="text-[11px] text-slate-300 mt-2">${feature.properties.description || '-'}</p>
                                    </div>
                                `;
                                layer.bindPopup(popupContent);
                                layer.bindTooltip(
                                    `${feature.properties.name} (${feature.properties.type_name})`, {
                                        sticky: true
                                    });
                                layer.on('click', (e) => {
                                    if (self.checkLocationMode) {
                                        L.DomEvent.stopPropagation(e);
                                        // Delay to prevent Leaflet from automatically opening the popup
                                        setTimeout(() => {
                                            map.closePopup();
                                        }, 10);
                                        self.handleMapClickForCheck(e.latlng);
                                    }
                                });
                            }
                        }).addTo(map);
                    }

                    // 4. Render POIs (Markers) & Restriction Zones
                    if (this.activeLayers.poi) {
                        this.filteredPOIs.forEach(loc => {
                            const color = loc.category.color || '#ef4444';

                            // Render restriction zone if exists
                            if (loc.restrictions && loc.restrictions.length > 0) {
                                const rest = loc.restrictions[0];
                                if (rest.geojson) {
                                    const resLayer = L.geoJSON(rest.geojson, {
                                        style: {
                                            fillColor: color,
                                            fillOpacity: 0.15,
                                            color: color,
                                            weight: 1.5,
                                            className: 'restriction-highlight'
                                        },
                                        onEachFeature: (feature, layer) => {
                                            layer.bindTooltip(`Zona Restriksi: ${loc.name}`, {
                                                sticky: true
                                            });
                                            layer.on('click', (e) => {
                                                if (self.checkLocationMode) {
                                                    L.DomEvent.stopPropagation(e);
                                                    self.handleMapClickForCheck(e.latlng);
                                                } else {
                                                    self.focusLocation(loc);
                                                }
                                            });
                                        }
                                    }).addTo(map);
                                    layers.poi.push(resLayer);
                                }
                            }

                            // Use inline SVG map-pin icon so it renders immediately without
                            // waiting for Iconify web component — fixes drift/unlock during zoom
                            const customIcon = L.divIcon({
                                html: `<div style="
                                    width:32px;height:32px;border-radius:50%;
                                    background-color:${color};
                                    border:2.5px solid rgba(255,255,255,0.9);
                                    box-shadow:0 2px 8px rgba(0,0,0,0.4);
                                    display:flex;align-items:center;justify-content:center;
                                ">
                                    <iconify-icon icon="${loc.category.icon || 'lucide:map-pin'}" style="color: white; font-size: 15px; display: flex; align-items: center; justify-content: center;"></iconify-icon>
                                </div>`,
                                className: 'custom-poi-icon-marker',
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                                popupAnchor: [0, -32]
                            });
                            const marker = L.marker([loc.latitude, loc.longitude], {
                                icon: customIcon
                            }).addTo(map);

                            // Build beautiful popup content
                            let popupHtml = `
                                <div class="p-1 min-w-[280px] max-w-[340px] text-slate-100">
                                    <div class="flex items-center gap-1.5 mb-1.5">
                                        <span class="text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded" style="background-color: ${color}20; color: ${color}; border: 1px solid ${color}40;">
                                            ${loc.category.name}
                                        </span>
                                    </div>
                                    <h4 class="text-base font-black text-white leading-tight mb-2">${loc.name}</h4>
                            `;

                            if (loc.photo_url) {
                                popupHtml += `
                                    <div class="w-full h-28 rounded-lg overflow-hidden mb-2.5 border border-white/10">
                                        <img src="${loc.photo_url}" alt="${loc.name}" class="w-full h-full object-cover">
                                    </div>
                                `;
                            }

                            if (loc.address) {
                                popupHtml += `
                                    <div class="flex items-start gap-1.5 text-xs text-slate-300 mb-1.5">
                                        <iconify-icon icon="lucide:map-pin" class="text-slate-400 mt-0.5 shrink-0"></iconify-icon>
                                        <span>${loc.address}</span>
                                    </div>
                                `;
                            }

                            if (loc.description) {
                                popupHtml += `
                                    <div class="flex items-start gap-1.5 text-xs text-slate-300 mb-2.5">
                                        <iconify-icon icon="lucide:info" class="text-slate-400 mt-0.5 shrink-0"></iconify-icon>
                                        <span>${loc.description}</span>
                                    </div>
                                `;
                            }

                            if (loc.restrictions && loc.restrictions.length > 0) {
                                const rest = loc.restrictions[0];
                                popupHtml += `
                                    <div class="mt-2.5 pt-2 border-t border-red-500/20 bg-red-950/20 rounded-lg p-2.5 border border-red-500/10">
                                        <div class="flex items-center gap-1.5 text-red-400 font-black text-[10px] uppercase tracking-wider mb-1.5">
                                            <iconify-icon icon="lucide:shield-alert"></iconify-icon>
                                            <span>Restriksi / Larangan</span>
                                        </div>
                                `;

                                if (rest.restricted_activities && rest.restricted_activities.length > 0) {
                                    popupHtml +=
                                        `<ul class="list-disc pl-4 text-xs text-slate-300 space-y-0.5 mb-1.5">`;
                                    rest.restricted_activities.forEach(act => {
                                        popupHtml += `<li>${act}</li>`;
                                    });
                                    popupHtml += `</ul>`;
                                }

                                if (rest.notes) {
                                    popupHtml +=
                                        `<p class="text-xs text-slate-300 italic font-medium">Catatan: ${rest.notes}</p>`;
                                }

                                popupHtml += `</div>`;
                            }

                            popupHtml += `</div>`;

                            marker.bindPopup(popupHtml);

                            // Save marker reference
                            if (typeof poiMarkers !== 'undefined') {
                                poiMarkers[loc.id] = marker;
                            }

                            // Tooltip & Click events
                            marker.bindTooltip(loc.name, {
                                sticky: true
                            });
                            marker.on('click', () => {
                                this.focusLocation(loc);
                            });

                            layers.poi.push(marker);
                        });
                    }

                    // Adjust map view to fit features
                    if (layers.kabupaten && this.regionsData.features.some(f => f.properties.level === 'kabupaten')) {
                        try {
                            map.fitBounds(layers.kabupaten.getBounds(), {
                                padding: [20, 20]
                            });
                        } catch (e) {}
                    } else if (layers.kecamatan && this.regionsData.features.some(f => f.properties.level ===
                        'kecamatan')) {
                        try {
                            map.fitBounds(layers.kecamatan.getBounds(), {
                                padding: [20, 20]
                            });
                        } catch (e) {}
                    }
                },

                toggleLayer(layerName) {
                    this.renderLayers();
                    if (!this.activeLayers.poi && this.focusedPOI) {
                        this.clearFocus();
                    }
                },

                toggleCategory(catSlug) {
                    this.selectedCategory = catSlug;
                    this.filterPOIs();
                },

                filterPOIs() {
                    let results = this.locationsData;

                    // Filter category
                    if (this.selectedCategory !== 'all') {
                        results = results.filter(loc => loc.category.slug === this.selectedCategory);
                    }

                    // Filter search query
                    if (this.searchQuery.trim() !== '') {
                        const q = this.searchQuery.toLowerCase();
                        results = results.filter(loc =>
                            loc.name.toLowerCase().includes(q) ||
                            (loc.address && loc.address.toLowerCase().includes(q)) ||
                            (loc.description && loc.description.toLowerCase().includes(q))
                        );
                    }

                    this.filteredPOIs = results;
                    this.renderLayers();
                },

                focusLocation(loc) {
                    this.clearCheck();
                    this.focusedPOI = loc;

                    // Center map on location
                    map.setView([loc.latitude, loc.longitude], 15);

                    // Clear existing restriction highlight
                    if (restrictionLayer) {
                        map.removeLayer(restrictionLayer);
                        restrictionLayer = null;
                    }

                    // Render restriction zone if exists
                    if (loc.restrictions && loc.restrictions.length > 0) {
                        const rest = loc.restrictions[0];
                        if (rest.geojson) {
                            const color = loc.category.color || '#ef4444';
                            restrictionLayer = L.geoJSON(rest.geojson, {
                                style: {
                                    fillColor: color,
                                    fillOpacity: 0.35,
                                    color: color,
                                    weight: 3,
                                    className: 'restriction-highlight'
                                },
                                onEachFeature: (feature, layer) => {
                                    layer.bindTooltip('ZONA RESTRIKSI', {
                                        sticky: true
                                    });
                                }
                            }).addTo(map);
                        }
                    }

                    // Open popup on marker if exists
                    if (typeof poiMarkers !== 'undefined' && poiMarkers[loc.id]) {
                        poiMarkers[loc.id].openPopup();
                    }

                    // Auto collapse sidebar on mobile when selecting a POI
                    if (window.innerWidth < 1024) {
                        this.sidebarOpen = false;
                    }
                },

                clearFocus() {
                    this.focusedPOI = null;
                    if (restrictionLayer) {
                        map.removeLayer(restrictionLayer);
                        restrictionLayer = null;
                    }
                    // Close any open popups
                    map.closePopup();

                    // Reset view
                    if (layers.kecamatan) {
                        try {
                            map.fitBounds(layers.kecamatan.getBounds(), {
                                padding: [20, 20]
                            });
                        } catch (e) {}
                    }
                },

                toggleCheckMode() {
                    this.checkLocationMode = !this.checkLocationMode;
                    if (this.checkLocationMode) {
                        this.clearFocus();
                    } else {
                        this.clearCheck();
                    }
                },

                clearCheck() {
                    this.checkResults = null;
                    this.checkLocationMode = false;
                    if (checkLocationPin) {
                        map.removeLayer(checkLocationPin);
                        checkLocationPin = null;
                    }
                    if (userLocMarker) {
                        map.removeLayer(userLocMarker);
                        userLocMarker = null;
                    }
                    map.closePopup();
                },

                analyzeCoordinates(latlng) {
                    let matchedZones = [];
                    if (this.zonesData && this.zonesData.features) {
                        this.zonesData.features.forEach(feature => {
                            if (isPointInGeoJSON(latlng.lat, latlng.lng, feature.geometry)) {
                                matchedZones.push({
                                    name: feature.properties.name,
                                    type_name: feature.properties.type_name,
                                    color: feature.properties.color,
                                    description: feature.properties.description
                                });
                            }
                        });
                    }

                    let matchedRestrictions = [];
                    this.locationsData.forEach(loc => {
                        if (loc.restrictions && loc.restrictions.length > 0) {
                            const rest = loc.restrictions[0];
                            if (rest.geojson) {
                                if (isPointInGeoJSON(latlng.lat, latlng.lng, rest.geojson)) {
                                    matchedRestrictions.push({
                                        location_name: loc.name,
                                        restricted_activities: rest.restricted_activities,
                                        notes: rest.notes,
                                        color: loc.category.color
                                    });
                                }
                            }
                        }
                    });

                    return {
                        matchedZones,
                        matchedRestrictions
                    };
                },

                buildCheckPopupHtml(latlng, matchedZones, matchedRestrictions) {
                    const isAllowed = matchedRestrictions.length === 0;
                    const latStr = latlng.lat.toFixed(6);
                    const lngStr = latlng.lng.toFixed(6);

                    // Build suitability badge
                    const statusBadge = isAllowed ?
                        `<span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase">DIPERBOLEHKAN</span>` :
                        `<span class="px-2 py-0.5 rounded text-[10px] font-black bg-red-500/20 text-red-400 border border-red-500/30 uppercase">TERBATAS / ADA LARANGAN</span>`;

                    // Build zones section
                    let zonesHtml = '';
                    if (matchedZones.length > 0) {
                        matchedZones.forEach(zone => {
                            const color = zone.color || '#f97316';
                            zonesHtml += `
                                <div class="mt-2 p-2 rounded-lg bg-slate-800/60 border border-white/5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full" style="background-color: ${color}"></span>
                                        <h5 class="text-[11px] font-bold text-white uppercase tracking-tight">${zone.name}</h5>
                                    </div>
                                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mt-0.5">${zone.type_name}</p>
                                    ${zone.description ? `<p class="text-[10px] text-slate-400 mt-1">${zone.description}</p>` : ''}
                                </div>
                            `;
                        });
                    } else {
                        zonesHtml =
                            `<p class="text-[10px] text-slate-400 italic mt-1">Tidak berada di dalam zonasi tataruang.</p>`;
                    }

                    // Build restrictions section
                    let restHtml = '';
                    if (matchedRestrictions.length > 0) {
                        matchedRestrictions.forEach(rest => {
                            restHtml += `
                                <div class="mt-2 p-2 rounded-lg bg-red-950/20 border border-red-500/20">
                                    <div class="flex items-center gap-1.5">
                                        <iconify-icon icon="lucide:ban" class="text-red-400 text-xs"></iconify-icon>
                                        <h5 class="text-[11px] font-bold text-red-300 uppercase tracking-tight">${rest.location_name}</h5>
                                    </div>
                                    <p class="text-[10px] text-red-400 font-bold mt-1 uppercase tracking-wider">Aktivitas Dilarang:</p>
                                    <p class="text-[10px] text-slate-300 leading-relaxed font-semibold">${rest.restricted_activities}</p>
                                    ${rest.notes ? `
                                            <p class="text-[9px] text-red-400/80 font-bold mt-1 uppercase tracking-wider">Catatan:</p>
                                            <p class="text-[9px] text-slate-400 leading-normal">${rest.notes}</p>
                                        ` : ''}
                                </div>
                            `;
                        });
                    } else {
                        restHtml =
                            `<p class="text-[10px] text-slate-400 italic mt-1">Aman. Bebas dari pembatasan/larangan usaha.</p>`;
                    }

                    return `
                        <div class="p-1 min-w-[200px] max-w-[260px] max-h-[300px] overflow-y-auto">
                            <h4 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-1">
                                <iconify-icon icon="lucide:search-code" class="text-blue-400 text-sm"></iconify-icon>
                                Analisis Lokasi
                            </h4>
                            <p class="text-[9px] text-slate-400 font-bold mt-0.5 font-mono">${latStr}, ${lngStr}</p>

                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400">Kelayakan:</span>
                                ${statusBadge}
                            </div>

                            <div class="mt-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Zonasi Tata Ruang</span>
                                ${zonesHtml}
                            </div>

                            <div class="mt-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Batasan / Restriksi POI</span>
                                ${restHtml}
                            </div>
                        </div>
                    `;
                },

                isPointInKabupaten(lat, lng) {
                    if (!this.regionsData || !this.regionsData.features) {
                        return true;
                    }
                    const kabFeature = this.regionsData.features.find(f => f.properties.level === 'kabupaten');
                    if (!kabFeature) {
                        return true;
                    }
                    return isPointInGeoJSON(lat, lng, kabFeature.geometry);
                },

                handleMapClickForCheck(latlng) {
                    // 1. Drop or move marker
                    if (checkLocationPin) {
                        checkLocationPin.setLatLng(latlng);
                    } else {
                        const checkIcon = L.divIcon({
                            html: '<div class="check-pin-marker"><iconify-icon icon="lucide:search-code" class="text-white text-xs"></iconify-icon></div>',
                            className: 'custom-check-pin-icon',
                            iconSize: [28, 28],
                            iconAnchor: [14, 28]
                        });
                        checkLocationPin = L.marker(latlng, {
                            icon: checkIcon
                        }).addTo(map);
                    }

                    // 2. Perform checks
                    const {
                        matchedZones,
                        matchedRestrictions
                    } = this.analyzeCoordinates(latlng);

                    // 3. Update Alpine state
                    this.checkResults = {
                        latitude: latlng.lat.toFixed(6),
                        longitude: latlng.lng.toFixed(6),
                        zones: matchedZones,
                        poiRestrictions: matchedRestrictions,
                        isAllowed: matchedRestrictions.length === 0
                    };

                    // 4. Bind and open popup
                    const popupHtml = this.buildCheckPopupHtml(latlng, matchedZones, matchedRestrictions);
                    setTimeout(() => {
                        checkLocationPin.bindPopup(popupHtml).openPopup();
                    }, 50);

                    // Pan to pin slightly
                    map.panTo(latlng);
                },

                locateUser() {
                    if (!navigator.geolocation) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak Didukung',
                            text: 'Geolocation tidak didukung oleh browser Anda.',
                            background: '#1e293b',
                            color: '#f1f5f9',
                            confirmButtonColor: '#3b82f6',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    const self = this;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const latlng = {
                                lat,
                                lng
                            };

                            // Check if coordinate is outside Kabupaten Tubaba boundary
                            const insideKab = self.isPointInKabupaten(lat, lng);
                            if (!insideKab) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Di Luar Wilayah',
                                    text: 'Lokasi Anda (' + lat.toFixed(6) + ', ' + lng.toFixed(6) +
                                        ') berada di luar wilayah Kabupaten Tulang Bawang Barat. Peta dipindahkan ke lokasi Anda, namun analisis tata ruang dan restriksi hanya aktif di dalam batas wilayah Tulang Bawang Barat.',
                                    background: '#1e293b',
                                    color: '#f1f5f9',
                                    confirmButtonColor: '#3b82f6',
                                    confirmButtonText: 'OK'
                                });
                            }

                            // Center map
                            map.setView([lat, lng], 15);

                            // Update marker
                            if (userLocMarker) {
                                map.removeLayer(userLocMarker);
                            }

                            const gpsIcon = L.divIcon({
                                html: '<div class="gps-pulse-marker"></div>',
                                className: 'custom-gps-icon',
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });

                            userLocMarker = L.marker([lat, lng], {
                                icon: gpsIcon
                            }).addTo(map);

                            // Perform checks
                            const {
                                matchedZones,
                                matchedRestrictions
                            } = self.analyzeCoordinates(latlng);

                            // Update Alpine state
                            self.checkLocationMode = true;
                            self.checkResults = {
                                latitude: lat.toFixed(6),
                                longitude: lng.toFixed(6),
                                zones: matchedZones,
                                poiRestrictions: matchedRestrictions,
                                isAllowed: matchedRestrictions.length === 0
                            };

                            // Bind and open popup
                            const popupHtml = self.buildCheckPopupHtml(latlng, matchedZones, matchedRestrictions);
                            setTimeout(() => {
                                userLocMarker.bindPopup(popupHtml).openPopup();
                            }, 50);
                        },
                        (error) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Akses GPS',
                                text: 'Gagal mendapatkan lokasi Anda. Pastikan izin lokasi / GPS telah diaktifkan.',
                                background: '#1e293b',
                                color: '#f1f5f9',
                                confirmButtonColor: '#3b82f6',
                                confirmButtonText: 'OK'
                            });
                        }, {
                            enableHighAccuracy: true
                        }
                    );
                }
            };
        }
    </script>
</body>

</html>

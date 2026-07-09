<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIBERUGO — Mal Pelayanan Publik Tubaba</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-mpp.png') }}">
    <meta name="description"
        content="Sistem Informasi Geospasial Kabupaten Tulang Bawang Barat (SIBERUGO). Portal pemetaan batas wilayah, rencana tata ruang/zonasi, dan titik lokasi pelayanan publik.">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top, #e8dec9 0%, #d6c5b3 100%);
            color: #3c2f2f;
        }

        .glass-card {
            background: linear-gradient(135deg, #7b323b 0%, #5d1e25 100%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 30px 70px -10px rgba(93, 30, 37, 0.45);
            border-radius: 2.5rem;
            color: #ffffff;
        }

        .premium-border {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-premium {
            background: linear-gradient(135deg, #f4edd8 0%, #e8dec9 100%);
            color: #5d1e25;
            box-shadow: 0 10px 30px -5px rgba(244, 237, 216, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(244, 237, 216, 0.5);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(244, 237, 216, 0.3);
            transform: translateY(-4px);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between">

    {{-- Header --}}
    <div class="w-full bg-[#6d272e] border-b border-white/10 shadow-lg">
        <header class="w-full max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl overflow-hidden bg-white/10 flex items-center justify-center border border-white/20">
                    <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo"
                        class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-sm font-black uppercase tracking-wider text-white">SIBERUGO</h1>
                    <p class="text-[9px] text-rose-200/70 font-bold uppercase tracking-widest">Kab. Tulang Bawang Barat
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="text-xs font-black uppercase tracking-wider text-[#6d272e] bg-[#f4edd8] px-4 py-2 rounded-full shadow-sm">
                    Home
                </span>
                @auth
                    <a href="{{ route('services.verification') }}"
                        class="text-xs font-black uppercase tracking-wider text-white border border-white/25 px-4 py-2 rounded-full hover:bg-white/10 transition">
                        Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-xs font-black uppercase tracking-wider text-white border border-white/25 px-4 py-2 rounded-full hover:bg-white/10 transition">
                        Masuk Admin
                    </a>
                @endauth
            </div>
        </header>
    </div>

    {{-- Main --}}
    <main class="w-full max-w-4xl mx-auto px-6 py-10 flex-1 flex items-center justify-center">
        <div class="glass-card w-full p-8 md:p-14 text-center relative overflow-hidden">
            {{-- Decorative glows --}}
            <div class="absolute -top-32 -left-32 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-64 h-64 bg-rose-500/15 rounded-full blur-3xl"></div>

            <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-[#f4edd8] text-xs font-semibold mb-6">
                <iconify-icon icon="lucide:map-pin" class="pulse-ring"></iconify-icon>
                Portal Peta Pelayanan Perizinan
            </span>

            <h2 class="text-3xl md:text-5xl font-black tracking-tight text-white mb-4 leading-tight">
                Peta Pelayanan Perizinan<br>
                <span class="bg-gradient-to-r from-[#f4edd8] to-[#fcebb6] bg-clip-text text-transparent">SIBERUGO
                    TUBABA</span>
            </h2>

            <p class="text-rose-200/70 text-sm md:text-base font-medium max-w-xl mx-auto mb-10 leading-relaxed">
                Pemetaan digital komprehensif Kabupaten Tulang Bawang Barat. Jelajahi batas wilayah kecamatan, batas
                desa, zonasi fungsi lahan, dan temukan titik lokasi fasilitas umum serta instansi pelayanan publik.
            </p>

            <div class="flex justify-center gap-4 mb-12">
                <a href="{{ route('siberugo.map') }}"
                    class="btn-premium px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest text-white flex items-center gap-2 shadow-lg">
                    <iconify-icon icon="lucide:map" class="text-base"></iconify-icon>
                    Jelajahi Peta Spasial
                </a>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-left border-t border-white/10 pt-8">
                <div class="stat-card p-5 rounded-2xl">
                    <div class="text-xs font-black text-rose-200/50 uppercase tracking-widest mb-1">Kecamatan</div>
                    <div class="text-2xl font-black text-white flex items-baseline gap-1">
                        {{ $stats['kecamatan'] }}
                        <span class="text-xs text-[#f4edd8] font-semibold">Wilayah</span>
                    </div>
                </div>
                <div class="stat-card p-5 rounded-2xl">
                    <div class="text-xs font-black text-rose-200/50 uppercase tracking-widest mb-1">Desa/Tiyuh</div>
                    <div class="text-2xl font-black text-white flex items-baseline gap-1">
                        {{ $stats['desa'] }}
                        <span class="text-xs text-[#f4edd8] font-semibold">Tiyuh</span>
                    </div>
                </div>
                <div class="stat-card p-5 rounded-2xl">
                    <div class="text-xs font-black text-rose-200/50 uppercase tracking-widest mb-1">Zonasi Tata Ruang
                    </div>
                    <div class="text-2xl font-black text-white flex items-baseline gap-1">
                        {{ $stats['zona'] }}
                        <span class="text-xs text-[#f4edd8] font-semibold">Kawasan</span>
                    </div>
                </div>
                <div class="stat-card p-5 rounded-2xl">
                    <div class="text-xs font-black text-rose-200/50 uppercase tracking-widest mb-1">Titik Lokasi (POI)
                    </div>
                    <div class="text-2xl font-black text-white flex items-baseline gap-1">
                        {{ $stats['poi'] }}
                        <span class="text-xs text-[#f4edd8] font-semibold">Fasilitas</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full py-8 text-center text-[10px] font-bold text-[#7a6b68] uppercase tracking-widest">
        &copy; {{ date('Y') }} SIBERUGO — Pemerintah Kabupaten Tulang Bawang Barat. All rights reserved.
    </footer>

</body>

</html>

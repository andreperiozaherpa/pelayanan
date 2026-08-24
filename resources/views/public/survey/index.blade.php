<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survei Kepuasan Masyarakat — {{ config('app.name') }}</title>
    <meta name="description"
        content="Survei Kepuasan Masyarakat (SKM) berdasarkan Permen PAN-RB No. 14 Tahun 2017. Pilih gerai layanan dan berikan penilaian Anda.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --crimson: #5d1e25;
            --maroon: #6d272e;
            --rose: #7b323b;
            --cream: #f4edd8;
            --cream-dark: #e8dec9;
            --cream-deep: #d6c5b3;
            --ink: #3c2f2f;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--ink);
            background: radial-gradient(circle at top, var(--cream-dark) 0%, var(--cream-deep) 100%);
            min-height: 100vh;
        }

        .glass {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.55);
            box-shadow: 0 8px 32px 0 rgba(93, 30, 37, 0.10);
            border-radius: 1.5rem;
        }

        .glass-strong {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 12px 40px -8px rgba(93, 30, 37, 0.14);
            border-radius: 1.75rem;
        }

        .hero-blob {
            position: absolute;
            border-radius: 9999px;
            filter: blur(60px);
            pointer-events: none;
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--cream) 0%, var(--cream-dark) 100%);
            color: var(--crimson);
            box-shadow: 0 10px 30px -5px rgba(244, 237, 216, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(244, 237, 216, 0.5);
        }

        .gerai-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 24px -8px rgba(93, 30, 37, 0.10);
            transition: all 0.3s ease;
        }

        .gerai-card:hover {
            background: rgba(255, 255, 255, 0.7);
            transform: translateY(-3px);
            border-color: rgba(244, 237, 216, 0.9);
            box-shadow: 0 18px 40px -10px rgba(93, 30, 37, 0.20);
        }

        .stat-chip {
            background: linear-gradient(135deg, #7b323b 0%, #5d1e25 100%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 30px -8px rgba(93, 30, 37, 0.45);
            transition: all 0.3s ease;
        }

        .stat-chip:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px -10px rgba(93, 30, 37, 0.55);
            border-color: rgba(244, 237, 216, 0.45);
        }
    </style>
</head>

<body class="min-h-screen pb-16">

    {{-- Top Bar --}}
    <div class="px-4 pt-6">
        <div class="max-w-3xl mx-auto glass flex items-center justify-between px-5 py-3">
            <a href="{{ route('landing.index') }}" class="flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-xl overflow-hidden bg-white/60 border border-white/70 flex items-center justify-center">
                    <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo"
                        class="w-7 h-7 object-contain">
                </div>
                <div class="leading-none">
                    <p class="text-[11px] font-black uppercase tracking-wider text-[#5d1e25]">DPMPTSP</p>
                    <p class="text-[8px] text-[#7b323b] font-bold uppercase tracking-widest">Kab. Tulang Bawang Barat
                    </p>
                </div>
            </a>
            <a href="{{ route('landing.index') }}"
                class="flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-[#7b323b] hover:text-[#5d1e25] transition-colors">
                <iconify-icon icon="lucide:arrow-left" class="text-sm"></iconify-icon>
                Beranda
            </a>
        </div>
    </div>

    {{-- Hero --}}
    <div class="px-4 pt-10 pb-6 relative">
        <div class="max-w-3xl mx-auto text-center">
            <div class="flex justify-center mb-5">
                <div
                    class="w-20 h-20 rounded-2xl overflow-hidden shadow-2xl border-2 border-white/60 bg-white/40 backdrop-blur-sm">
                    <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo"
                        class="w-full h-full object-contain">
                </div>
            </div>
            <span
                class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#7b323b]/10 text-[#7b323b] text-[9px] font-black uppercase tracking-widest rounded-full border border-[#7b323b]/20 mb-4">
                <iconify-icon icon="lucide:clipboard-check" class="text-sm"></iconify-icon>
                Permen PAN-RB No. 14 Tahun 2017
            </span>
            <h1 class="text-3xl md:text-4xl font-black text-[#5d1e25] uppercase tracking-tight mb-3">Survei Kepuasan
                Masyarakat</h1>
            <p class="text-sm md:text-base text-[#7b323b]/80 font-medium max-w-xl mx-auto leading-relaxed">
                Bantu kami meningkatkan kualitas pelayanan. Pilih gerai layanan yang Anda gunakan lalu berikan penilaian
                jujur Anda.
            </p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="px-4 py-6">
        <div class="max-w-3xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="stat-chip rounded-2xl p-4 text-center">
                <iconify-icon icon="lucide:list-checks" class="text-xl text-[#f4edd8] mb-1.5"></iconify-icon>
                <p class="text-lg font-black text-white leading-none">9</p>
                <p class="text-[8px] font-bold text-[#f4edd8]/70 uppercase tracking-widest mt-1">Unsur SKM</p>
            </div>
            <div class="stat-chip rounded-2xl p-4 text-center">
                <iconify-icon icon="lucide:scale" class="text-xl text-[#f4edd8] mb-1.5"></iconify-icon>
                <p class="text-lg font-black text-white leading-none">1-4</p>
                <p class="text-[8px] font-bold text-[#f4edd8]/70 uppercase tracking-widest mt-1">Skala Nilai</p>
            </div>
            <div class="stat-chip rounded-2xl p-4 text-center">
                <iconify-icon icon="lucide:bar-chart-3" class="text-xl text-[#f4edd8] mb-1.5"></iconify-icon>
                <p class="text-lg font-black text-white leading-none">IKM</p>
                <p class="text-[8px] font-bold text-[#f4edd8]/70 uppercase tracking-widest mt-1">Nilai Komposit</p>
            </div>
            <div class="stat-chip rounded-2xl p-4 text-center">
                <iconify-icon icon="lucide:shield-check" class="text-xl text-[#f4edd8] mb-1.5"></iconify-icon>
                <p class="text-lg font-black text-white leading-none">Gratis</p>
                <p class="text-[8px] font-bold text-[#f4edd8]/70 uppercase tracking-widest mt-1">Tanpa Login</p>
            </div>
        </div>
    </div>

    {{-- Daftar Gerai --}}
    <div class="px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-3 mb-5">
                <div
                    class="w-10 h-10 rounded-2xl bg-[#7b323b]/10 text-[#7b323b] flex items-center justify-center border border-[#7b323b]/15">
                    <iconify-icon icon="lucide:building-2" class="text-xl"></iconify-icon>
                </div>
                <div>
                    <h2 class="text-[11px] font-black text-[#5d1e25] uppercase tracking-widest">Pilih Gerai Layanan</h2>
                    <p class="text-[10px] font-bold text-[#7b323b]/60 uppercase tracking-widest">Pilih satu untuk mulai
                        menilai</p>
                </div>
            </div>

            @if (session('success'))
                <div class="glass-strong p-6 mb-6 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <iconify-icon icon="lucide:check-circle-2" class="text-emerald-500 text-3xl"></iconify-icon>
                    </div>
                    <h2 class="text-base font-black text-[#5d1e25] uppercase tracking-tight mb-2">Terima Kasih</h2>
                    <p class="text-sm font-medium text-[#7b323b]/80">{{ session('success') }}</p>
                </div>
            @endif

            <div class="space-y-3">
                @forelse ($instansis as $instansi)
                    <a href="{{ route('survey.show', $instansi) }}"
                        class="gerai-card group flex items-center justify-between gap-4 p-5 rounded-3xl">
                        <div class="flex items-center gap-4 min-w-0">
                            <div
                                class="w-11 h-11 rounded-2xl bg-[#7b323b]/10 border border-[#7b323b]/15 flex items-center justify-center shrink-0 group-hover:bg-[#5d1e25] transition-colors">
                                <iconify-icon icon="lucide:monitor"
                                    class="text-[#7b323b] text-xl group-hover:text-[#f4edd8] transition-colors"></iconify-icon>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-black text-[#5d1e25] truncate">{{ $instansi->name }}</p>
                                <p class="text-[9px] font-bold text-[#7b323b]/60 uppercase tracking-widest">
                                    @if ($instansi->gerais->first()->location)
                                        · {{ $instansi->gerais->first()->location }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-1.5 text-[#7b323b] font-black text-[9px] uppercase tracking-widest shrink-0">
                            Isi Survei
                            <iconify-icon icon="lucide:arrow-right"
                                class="group-hover:translate-x-1 transition-transform text-lg"></iconify-icon>
                        </div>
                    </a>
                @empty
                    <div class="glass-strong p-10 text-center">
                        <iconify-icon icon="lucide:inbox"
                            class="text-3xl text-[#7b323b]/40 mx-auto mb-3"></iconify-icon>
                        <p class="text-sm font-medium text-[#7b323b]/70">Belum ada instansi yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="px-4 pt-10">
        <div
            class="max-w-3xl mx-auto border-t border-[#5d1e25]/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[10px] font-bold text-[#7b323b]/50 uppercase tracking-widest">
                &copy; {{ date('Y') }} {{ config('app.name') }} — DPMPTSP Kab. Tubaba
            </p>
            <a href="{{ route('landing.index') }}"
                class="text-[10px] font-black text-[#7b323b] hover:text-[#5d1e25] uppercase tracking-widest transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>

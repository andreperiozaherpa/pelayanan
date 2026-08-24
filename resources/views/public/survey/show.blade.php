<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survei {{ $instansi->name }} — {{ config('app.name') }}</title>
    <meta name="description" content="Survei Kepuasan Masyarakat (SKM) berdasarkan Permen PAN-RB No. 14 Tahun 2017.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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

        html { scroll-behavior: smooth; }

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

        .btn-premium {
            background: linear-gradient(135deg, var(--cream) 0%, var(--cream-dark) 100%);
            color: var(--crimson);
            box-shadow: 0 10px 30px -5px rgba(244, 237, 216, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 15px 35px -5px rgba(244, 237, 216, 0.5); }

        .option-box {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1.5px solid rgba(93, 30, 37, 0.12);
            border-radius: 1rem;
            padding: 0.65rem 0.85rem;
            transition: all .2s ease;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .option-box:hover { border-color: rgba(123, 50, 59, 0.45); background: rgba(255, 255, 255, 0.6); }

        .option-box:has(input:checked) {
            border-color: var(--crimson);
            background: rgba(93, 30, 37, 0.07);
            box-shadow: 0 0 0 3px rgba(93, 30, 37, 0.12);
        }

        .option-box input { position: absolute; opacity: 0; pointer-events: none; }

        .option-dot {
            width: 1.25rem; height: 1.25rem; border-radius: 9999px;
            border: 2px solid rgba(93, 30, 37, 0.25);
            background: rgba(255, 255, 255, 0.4);
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: all .2s ease;
        }

        .option-box:has(input:checked) .option-dot { border-color: var(--crimson); background: var(--crimson); }
        .option-box:has(input:checked) .option-dot::after { content: ""; width: 0.5rem; height: 0.5rem; border-radius: 9999px; background: var(--cream); }

        input, select, textarea {
            width: 100%; padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.55);
            border: 1.5px solid rgba(93, 30, 37, 0.12);
            border-radius: 1rem;
            font-size: 0.875rem; font-weight: 600; color: var(--ink);
            outline: none; transition: all .2s ease;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        input::placeholder, textarea::placeholder { color: rgba(60, 47, 47, 0.35); }

        input:focus, select:focus, textarea:focus {
            border-color: var(--rose);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 0 3px rgba(123, 50, 59, 0.15);
        }

        .q-number {
            width: 2.25rem; height: 2.25rem; border-radius: 0.9rem;
            background: linear-gradient(135deg, var(--crimson), var(--rose));
            color: var(--cream); font-weight: 800; font-size: 0.8rem;
            display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 4px 12px -2px rgba(93, 30, 37, 0.4);
        }

        .card-icon {
            width: 2.5rem; height: 2.5rem; border-radius: 0.9rem;
            background: rgba(123, 50, 59, 0.10);
            border: 1px solid rgba(123, 50, 59, 0.15);
            color: var(--rose);
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
    </style>
</head>

<body class="min-h-screen pb-16">

    {{-- Top Bar --}}
    <div class="px-4 pt-6">
        <div class="max-w-3xl mx-auto glass flex items-center justify-between px-5 py-3">
            <a href="{{ route('landing.index') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl overflow-hidden bg-white/60 border border-white/70 flex items-center justify-center">
                    <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>
                <div class="leading-none">
                    <p class="text-[11px] font-black uppercase tracking-wider text-[#5d1e25]">DPMPTSP</p>
                    <p class="text-[8px] text-[#7b323b] font-bold uppercase tracking-widest">Kab. Tulang Bawang Barat</p>
                </div>
            </a>
            <a href="{{ route('survey.index') }}"
                class="flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-[#7b323b] hover:text-[#5d1e25] transition-colors">
                <iconify-icon icon="lucide:arrow-left" class="text-sm"></iconify-icon>
                Daftar Gerai
            </a>
        </div>
    </div>

    {{-- Hero --}}
    <div class="px-4 pt-10 pb-8 relative">
        <div class="max-w-3xl mx-auto text-center">
            <div class="flex justify-center mb-5">
                <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-2xl border-2 border-white/60 bg-white/40 backdrop-blur-sm">
                    <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#7b323b]/10 text-[#7b323b] text-[9px] font-black uppercase tracking-widest rounded-full border border-[#7b323b]/20 mb-4">
                <iconify-icon icon="lucide:monitor" class="text-sm"></iconify-icon>
                {{ $instansi->gerais->first()->code }} · {{ $instansi->name }}
            </span>
            <h1 class="text-2xl md:text-3xl font-black text-[#5d1e25] uppercase tracking-tight mb-3">Survei Kepuasan Masyarakat</h1>
            <p class="text-xs md:text-sm text-[#7b323b]/75 font-medium max-w-lg mx-auto leading-relaxed">
                Berikan penilaian Anda terhadap pelayanan <span class="font-black">{{ $instansi->name }}</span>. Jawaban bersifat rahasia.
            </p>
        </div>
    </div>

    {{-- Form --}}
    <div class="px-4 py-4">
        <form action="{{ route('survey.store', $instansi) }}" method="POST" class="max-w-3xl mx-auto space-y-6">
            @csrf

            {{-- Bagian Pertama: Profil Responden --}}
            <div class="glass-strong p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="card-icon">
                        <iconify-icon icon="lucide:user-round" class="text-xl"></iconify-icon>
                    </span>
                    <div>
                        <h2 class="text-sm font-black text-[#5d1e25] uppercase tracking-tight">Profil Responden</h2>
                        <p class="text-[10px] font-bold text-[#7b323b]/60 uppercase tracking-widest">Bagian Pertama</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-[#5d1e25] uppercase tracking-widest mb-1.5 block">Nama <span class="text-[#7b323b]/40 normal-case">(opsional)</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-[#5d1e25] uppercase tracking-widest mb-1.5 block">Umur <span class="text-[#7b323b]/40 normal-case">(opsional)</span></label>
                        <input type="number" name="umur" value="{{ old('umur') }}" placeholder="Tahun" min="10" max="120">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-[#5d1e25] uppercase tracking-widest mb-1.5 block">Jenis Kelamin <span class="text-[#7b323b]/40 normal-case">(opsional)</span></label>
                        <select name="jenis_kelamin">
                            <option value="">— Pilih —</option>
                            <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-[#5d1e25] uppercase tracking-widest mb-1.5 block">Pendidikan Terakhir <span class="text-[#7b323b]/40 normal-case">(opsional)</span></label>
                        <select name="pendidikan">
                            <option value="">— Pilih —</option>
                            @foreach (['SD', 'SMP', 'SMA/SMK', 'D1-D4', 'S1', 'S2', 'S3'] as $p)
                                <option value="{{ $p }}" @selected(old('pendidikan') === $p)>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-[#5d1e25] uppercase tracking-widest mb-1.5 block">Pekerjaan <span class="text-[#7b323b]/40 normal-case">(opsional)</span></label>
                        <select name="pekerjaan">
                            <option value="">— Pilih —</option>
                            @foreach (['PNS/TNI/Polri', 'Pegawai Swasta', 'Wiraswasta/UMKM', 'Petani/Nelayan', 'Pelajar/Mahasiswa', 'Ibu Rumah Tangga', 'Lainnya'] as $p)
                                <option value="{{ $p }}" @selected(old('pekerjaan') === $p)>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Bagian Kedua: 9 Pertanyaan SKM --}}
            <div class="glass-strong p-6 md:p-8">
                <div class="flex items-center gap-3 mb-2">
                    <span class="card-icon">
                        <iconify-icon icon="lucide:clipboard-check" class="text-xl"></iconify-icon>
                    </span>
                    <div>
                        <h2 class="text-sm font-black text-[#5d1e25] uppercase tracking-tight">Penilaian Layanan</h2>
                        <p class="text-[10px] font-bold text-[#7b323b]/60 uppercase tracking-widest">Bagian Kedua · 9 Unsur SKM</p>
                    </div>
                </div>
                <p class="text-xs font-medium text-[#7b323b]/70 mb-6">Pilih satu jawaban yang paling sesuai dengan pengalaman Saudara.</p>

                <div class="space-y-6">
                    @foreach ($unsur as $index => $u)
                        <div class="border-b border-[#5d1e25]/10 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-start gap-3 mb-3">
                                <span class="q-number">{{ $index + 1 }}</span>
                                <div>
                                    <p class="text-[10px] font-black text-[#7b323b] uppercase tracking-widest mb-0.5">{{ $u['kode'] }} · {{ $u['nama'] }}</p>
                                    <p class="text-sm font-bold text-[#5d1e25]">{{ $u['pertanyaan'] }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                @foreach ($u['opsi'] as $i => $opsi)
                                    <label class="option-box">
                                        <input type="radio" name="{{ $u['key'] }}" value="{{ $i + 1 }}" @checked(old($u['key']) == $i + 1)>
                                        <span class="option-dot"></span>
                                        <span class="text-[13px] font-semibold text-[#3c2f2f]">{{ $opsi }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error($u['key'])
                                <p class="text-[11px] font-bold text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Saran --}}
            <div class="glass-strong p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="card-icon">
                        <iconify-icon icon="lucide:message-square-text" class="text-xl"></iconify-icon>
                    </span>
                    <div>
                        <h2 class="text-sm font-black text-[#5d1e25] uppercase tracking-tight">Saran & Masukan</h2>
                        <p class="text-[10px] font-bold text-[#7b323b]/60 uppercase tracking-widest">Opsional</p>
                    </div>
                </div>
                <textarea name="saran" rows="4" placeholder="Tuliskan saran atau masukan Anda untuk peningkatan pelayanan...">{{ old('saran') }}</textarea>
            </div>

            <button type="submit"
                class="btn-premium w-full py-4 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl active:scale-[0.99] flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:send" class="text-lg"></iconify-icon>
                Kirim Survei
            </button>
        </form>

        <div class="max-w-3xl mx-auto pt-10">
            <div class="border-t border-[#5d1e25]/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-[10px] font-bold text-[#7b323b]/50 uppercase tracking-widest">
                    &copy; {{ date('Y') }} {{ config('app.name') }} — DPMPTSP Kab. Tubaba
                </p>
                <a href="{{ route('survey.index') }}" class="text-[10px] font-black text-[#7b323b] hover:text-[#5d1e25] uppercase tracking-widest transition-colors">
                    Kembali ke Daftar Gerai
                </a>
            </div>
        </div>
    </div>

</body>
</html>

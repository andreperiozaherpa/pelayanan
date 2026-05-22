<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Keabsahan Surat — {{ config('app.name') }}</title>
    <meta name="description" content="Halaman verifikasi keabsahan surat resmi yang diterbitkan oleh pemerintah desa. Scan QR code atau masukkan NIK untuk mengecek keaslian dokumen.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; background: #f0f4f8; }

        .glass-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 20px 60px -15px rgba(0,0,0,0.1);
            border-radius: 2rem;
        }

        .status-valid {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 1px solid #6ee7b7;
            color: #065f46;
        }
        .status-invalid {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            color: #7f1d1d;
        }
        .status-orange {
            background: linear-gradient(135deg, #ffedd5, #fed7aa);
            border: 1px solid #fdba74;
            color: #7c2d12;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
        }

        .pulse-ring {
            animation: pulse-ring 2s ease-out infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .verified-bounce {
            animation: verified-bounce 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes verified-bounce {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>

<body class="min-h-screen">

    {{-- Hero Header --}}
    <div class="hero-gradient px-4 pt-12 pb-24 relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-xl mx-auto text-center relative z-10">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="pulse-ring absolute inset-0 bg-blue-400/30 rounded-full"></div>
                    <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-2xl border-2 border-white/20">
                        <img src="{{ asset('assets/images/logo-tubaba.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
            <h1 class="text-2xl font-black text-white uppercase tracking-tight mb-2">
                Cek Keabsahan Surat
            </h1>
            <p class="text-blue-200 text-sm font-medium">
                Verifikasi keaslian dokumen yang diterbitkan oleh pemerintah desa
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-xl mx-auto px-4 -mt-14 pb-12 relative z-10">

        {{-- Result Card --}}
        @if($citizen)
            <div class="glass-card p-8 mb-6 verified-bounce">

                {{-- Status Badge --}}
                <div class="flex justify-center mb-6">
                    @if($isValid)
                        <div class="status-valid inline-flex items-center gap-3 px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-wide">
                            <iconify-icon icon="lucide:shield-check" class="text-2xl text-emerald-600"></iconify-icon>
                            {{ $statusLabel }}
                        </div>
                    @elseif($statusColor === 'orange')
                        <div class="status-orange inline-flex items-center gap-3 px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-wide">
                            <iconify-icon icon="lucide:clock" class="text-2xl text-orange-600"></iconify-icon>
                            {{ $statusLabel }}
                        </div>
                    @else
                        <div class="status-invalid inline-flex items-center gap-3 px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-wide">
                            <iconify-icon icon="lucide:shield-x" class="text-2xl text-red-600"></iconify-icon>
                            {{ $statusLabel }}
                        </div>
                    @endif
                </div>

                {{-- Jenis Dokumen --}}
                <div class="text-center mb-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jenis Dokumen</p>
                    <p class="text-base font-black text-slate-800">{{ $serviceLabel }}</p>
                </div>

                <div class="border-t border-slate-100 pt-6 space-y-4">
                    {{-- Nama Warga --}}
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                            <iconify-icon icon="lucide:user" class="text-slate-400 text-base"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</p>
                            <p class="text-sm font-black text-slate-800 mt-0.5">{{ $citizen->nama_lengkap }}</p>
                        </div>
                    </div>

                    {{-- Desa --}}
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                            <iconify-icon icon="lucide:map-pin" class="text-slate-400 text-base"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Asal Desa</p>
                            <p class="text-sm font-black text-slate-800 mt-0.5">
                                {{ $citizen->village->name ?? '-' }}
                                @if($citizen->village?->district)
                                    <span class="font-medium text-slate-500">, Kec. {{ $citizen->village->district->name }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Tanggal Berlaku --}}
                    @if($record && $record->valid_until)
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                <iconify-icon icon="lucide:calendar" class="text-slate-400 text-base"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Berlaku Hingga</p>
                                <p class="text-sm font-black text-slate-800 mt-0.5">
                                    {{ \Carbon\Carbon::parse($record->valid_until)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Nomor Surat --}}
                    @if($record && $record->letter_number)
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                <iconify-icon icon="lucide:file-text" class="text-slate-400 text-base"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor Surat</p>
                                <p class="text-sm font-black text-slate-800 mt-0.5">{{ $record->letter_number }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Serial Number TTE --}}
                    @if($serialNumber)
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                <iconify-icon icon="lucide:badge-check" class="text-slate-400 text-base"></iconify-icon>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Serial Sertifikat TTE</p>
                                <p class="text-[10px] font-mono text-slate-600 mt-0.5 break-all">{{ $serialNumber }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Waktu Verifikasi --}}
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                            <iconify-icon icon="lucide:clock-3" class="text-slate-400 text-base"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Pengecekan</p>
                            <p class="text-sm font-black text-slate-800 mt-0.5">
                                {{ now()->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif(request()->hasAny(['q', 'type']))
            {{-- NIK tidak ditemukan --}}
            <div class="glass-card p-8 mb-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="lucide:file-x" class="text-red-500 text-3xl"></iconify-icon>
                </div>
                <h2 class="text-base font-black text-slate-800 uppercase tracking-tight mb-2">Dokumen Tidak Ditemukan</h2>
                <p class="text-slate-500 text-sm font-medium">
                    Data warga tidak ditemukan dalam sistem. Pastikan QR code yang Anda scan berasal dari dokumen resmi yang sah.
                </p>
            </div>
        @endif

        {{-- Form Pengecekan Manual --}}
        <div class="glass-card p-8">
            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Cek Manual dengan NIK</h2>
            <form action="{{ route('public.verify') }}" method="GET" class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 block">Nomor Induk Kependudukan (NIK)</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Masukkan 16 digit NIK"
                        maxlength="16"
                        pattern="[0-9]{16}"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition tracking-wider">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 block">Jenis Dokumen</label>
                    <select name="type"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition">
                        <option value="">— Pilih Jenis Dokumen —</option>
                        <option value="poverty" {{ request('type') === 'poverty' ? 'selected' : '' }}>Surat Keterangan Tidak Mampu (SKTM)</option>
                        <option value="domicile" {{ request('type') === 'domicile' ? 'selected' : '' }}>Surat Keterangan Domisili</option>
                        <option value="move" {{ request('type') === 'move' ? 'selected' : '' }}>Surat Pengantar Pindah</option>
                        <option value="death" {{ request('type') === 'death' ? 'selected' : '' }}>Surat Keterangan Kematian</option>
                        <option value="arrival" {{ request('type') === 'arrival' ? 'selected' : '' }}>Surat Pengantar Datang</option>
                    </select>
                </div>
                <button type="submit"
                    class="w-full py-3.5 bg-slate-800 hover:bg-slate-700 active:scale-95 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl transition-all shadow-lg">
                    <iconify-icon icon="lucide:search" class="mr-2"></iconify-icon>
                    Verifikasi Dokumen
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center mt-8 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>

</body>
</html>

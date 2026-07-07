@extends('layouts.public', ['seo' => $page->seo ?? null])

@section('content')
<main class="flex-grow pt-32 pb-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <nav class="flex text-xs text-[#7b323b] font-bold uppercase tracking-wider mb-4" aria-label="Breadcrumb">
                <a href="{{ route('landing.index') }}" class="hover:underline">Beranda</a>
                <span class="mx-2 text-[#3c2f2f]/40">/</span>
                <span class="text-[#3c2f2f]/60">{{ $page->title }}</span>
            </nav>

            <div class="mb-8">
                <h1 class="text-3xl md:text-5xl font-black text-[#5d1e25] mb-3 leading-tight">
                    {{ $page->title }}
                </h1>
                <p class="text-sm md:text-base text-[#3c2f2f]/70 font-semibold leading-relaxed">
                    Sampaikan keluhan, aspirasi, atau aduan Anda secara langsung kepada kami. Kami berkomitmen untuk memberikan pelayanan publik yang transparan dan responsif.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-start gap-3 shadow-sm" role="alert">
                    <iconify-icon icon="lucide:check-circle" class="text-xl text-emerald-600 flex-shrink-0 mt-0.5"></iconify-icon>
                    <div>
                        <h4 class="font-bold text-sm">Pengaduan Terkirim</h4>
                        <p class="text-xs text-emerald-700/90 mt-1 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-start gap-3 shadow-sm" role="alert">
                    <iconify-icon icon="lucide:alert-circle" class="text-xl text-rose-600 flex-shrink-0 mt-0.5"></iconify-icon>
                    <div>
                        <h4 class="font-bold text-sm">Terjadi Kesalahan Pengisian</h4>
                        <ul class="list-disc list-inside text-xs text-rose-700/90 mt-1.5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="glass-card p-6 md:p-10 border border-white/40 shadow-xl rounded-[2.5rem]">
                <form action="{{ route('landing.complaint.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#3c2f2f]/40">
                                    <iconify-icon icon="lucide:user" class="text-base"></iconify-icon>
                                </span>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       required 
                                       value="{{ old('name') }}"
                                       placeholder="Masukkan nama lengkap Anda"
                                       class="w-full pl-11 pr-4 py-3 bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl text-sm text-[#3c2f2f] placeholder-[#3c2f2f]/40 focus:outline-none focus:ring-2 focus:ring-[#7b323b]/20 focus:border-[#7b323b] shadow-sm transition">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-2">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#3c2f2f]/40">
                                    <iconify-icon icon="lucide:mail" class="text-base"></iconify-icon>
                                </span>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       required 
                                       value="{{ old('email') }}"
                                       placeholder="contoh@email.com"
                                       class="w-full pl-11 pr-4 py-3 bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl text-sm text-[#3c2f2f] placeholder-[#3c2f2f]/40 focus:outline-none focus:ring-2 focus:ring-[#7b323b]/20 focus:border-[#7b323b] shadow-sm transition">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nomor Telepon / WA -->
                        <div>
                            <label for="phone" class="block text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-2">No. Telepon / WhatsApp</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#3c2f2f]/40">
                                    <iconify-icon icon="lucide:phone" class="text-base"></iconify-icon>
                                </span>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       required 
                                       value="{{ old('phone') }}"
                                       placeholder="Contoh: 08123456789"
                                       class="w-full pl-11 pr-4 py-3 bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl text-sm text-[#3c2f2f] placeholder-[#3c2f2f]/40 focus:outline-none focus:ring-2 focus:ring-[#7b323b]/20 focus:border-[#7b323b] shadow-sm transition">
                            </div>
                        </div>

                        <!-- Subjek -->
                        <div>
                            <label for="subject" class="block text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-2">Subjek Pengaduan</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#3c2f2f]/40">
                                    <iconify-icon icon="lucide:tag" class="text-base"></iconify-icon>
                                </span>
                                <input type="text" 
                                       name="subject" 
                                       id="subject" 
                                       required 
                                       value="{{ old('subject') }}"
                                       placeholder="Judul atau topik laporan"
                                       class="w-full pl-11 pr-4 py-3 bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl text-sm text-[#3c2f2f] placeholder-[#3c2f2f]/40 focus:outline-none focus:ring-2 focus:ring-[#7b323b]/20 focus:border-[#7b323b] shadow-sm transition">
                            </div>
                        </div>
                    </div>

                    <!-- Isi Laporan -->
                    <div>
                        <label for="content" class="block text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-2">Detail Pengaduan</label>
                        <div class="relative">
                            <textarea name="content" 
                                      id="content" 
                                      required 
                                      rows="6"
                                      placeholder="Tuliskan secara lengkap rincian kejadian, keluhan, atau aspirasi Anda..."
                                      class="w-full p-4 bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl text-sm text-[#3c2f2f] placeholder-[#3c2f2f]/40 focus:outline-none focus:ring-2 focus:ring-[#7b323b]/20 focus:border-[#7b323b] shadow-sm transition leading-relaxed"></textarea>
                        </div>
                    </div>

                    <!-- Upload Lampiran -->
                    <div>
                        <label for="attachment" class="block text-xs font-black text-[#5d1e25] uppercase tracking-wider mb-2">Lampiran Bukti (Opsional)</label>
                        <div class="relative">
                            <input type="file" 
                                   name="attachment" 
                                   id="attachment" 
                                   class="hidden">
                            <label for="attachment" class="flex flex-col items-center justify-center border-2 border-dashed border-[#5d1e25]/20 hover:border-[#7b323b] bg-white/30 hover:bg-white/50 rounded-2xl p-6 cursor-pointer transition shadow-inner">
                                <iconify-icon icon="lucide:upload-cloud" class="text-3xl text-[#5d1e25]/60 mb-2"></iconify-icon>
                                <span class="text-xs font-bold text-[#5d1e25] uppercase tracking-wider">Pilih Berkas Bukti</span>
                                <span class="text-[10px] text-[#3c2f2f]/50 mt-1">Format: JPG, JPEG, PNG, atau PDF (Maks. 5MB)</span>
                                <span id="file-name" class="text-xs font-semibold text-[#7b323b] mt-2 hidden"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Google reCAPTCHA v2 -->
                    <div class="flex justify-start">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="w-full md:w-auto bg-[#5d1e25] hover:bg-[#7b323b] text-white px-8 py-3.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all duration-300 hover:shadow-lg active:scale-95 flex items-center justify-center gap-2">
                            <iconify-icon icon="lucide:send" class="text-sm"></iconify-icon>
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    document.getElementById('attachment').addEventListener('change', function(e) {
        const fileLabel = document.getElementById('file-name');
        if (e.target.files.length > 0) {
            fileLabel.textContent = 'Berkas dipilih: ' + e.target.files[0].name;
            fileLabel.classList.remove('hidden');
        } else {
            fileLabel.textContent = '';
            fileLabel.classList.add('hidden');
        }
    });
</script>
@endpush

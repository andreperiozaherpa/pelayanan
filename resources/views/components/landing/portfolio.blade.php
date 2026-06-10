{{-- Jenis Perizinan & Investasi Section Component --}}
<section id="portfolio" class="py-24 bg-transparent">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Jenis Layanan Perizinan</p>
            <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25]">Informasi Perizinan & Non-Perizinan</h2>
            <p class="text-[#3c2f2f]/70 mt-2 text-sm max-w-xl mx-auto">Informasi lengkap mengenai persyaratan, durasi, dan pengajuan untuk jenis izin yang paling sering diakses masyarakat.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- 1. Perizinan Berusaha --}}
            <div class="group bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/40 hover:shadow-xl hover:bg-white/60 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 bg-[#f4edd8] rounded-2xl flex items-center justify-center mb-6 text-[#5d1e25]">
                    <iconify-icon icon="lucide:briefcase" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-lg font-bold text-[#5d1e25] mb-2">Perizinan Berusaha</h3>
                <p class="text-xs text-[#3c2f2f]/70 leading-relaxed mb-6">Penerbitan perizinan berusaha berbasis resiko terintegrasi elektronik melalui portal OSS-RBA.</p>
                <a href="/pelayanan/perizinan-berusaha" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#7b323b] group-hover:text-[#5d1e25] transition-colors">
                    Syarat & Prosedur
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- 2. Perizinan Non Berusaha --}}
            <div class="group bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/40 hover:shadow-xl hover:bg-white/60 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 bg-[#f4edd8] rounded-2xl flex items-center justify-center mb-6 text-[#5d1e25]">
                    <iconify-icon icon="lucide:file-text" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-lg font-bold text-[#5d1e25] mb-2">Perizinan Non Berusaha</h3>
                <p class="text-xs text-[#3c2f2f]/70 leading-relaxed mb-6">Penerbitan surat izin praktik tenaga kesehatan dan izin sektoral daerah lainnya.</p>
                <a href="/pelayanan/perizinan-non-berusaha" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#7b323b] group-hover:text-[#5d1e25] transition-colors">
                    Syarat & Prosedur
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- 3. Persetujuan Bangunan Gedung (PBG) --}}
            <div class="group bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/40 hover:shadow-xl hover:bg-white/60 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 bg-[#f4edd8] rounded-2xl flex items-center justify-center mb-6 text-[#5d1e25]">
                    <iconify-icon icon="lucide:home" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-lg font-bold text-[#5d1e25] mb-2">Persetujuan Bangunan</h3>
                <p class="text-xs text-[#3c2f2f]/70 leading-relaxed mb-6">Prosedur pengajuan PBG (Pengganti IMB) dan Sertifikat Layak Fungsi (SLF) via SIMBG.</p>
                <a href="/pelayanan/pbg" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#7b323b] group-hover:text-[#5d1e25] transition-colors">
                    Syarat & Prosedur
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- 4. Izin Penelitian --}}
            <div class="group bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/40 hover:shadow-xl hover:bg-white/60 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 bg-[#f4edd8] rounded-2xl flex items-center justify-center mb-6 text-[#5d1e25]">
                    <iconify-icon icon="lucide:graduation-cap" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-lg font-bold text-[#5d1e25] mb-2">Izin Penelitian</h3>
                <p class="text-xs text-[#3c2f2f]/70 leading-relaxed mb-6">Pengurusan izin survei riset ilmiah, observasi, dan magang di wilayah Tulang Bawang Barat.</p>
                <a href="/pelayanan/alur-penelitian" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#7b323b] group-hover:text-[#5d1e25] transition-colors">
                    Syarat & Prosedur
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Potensi & Peluang Investasi (Asal data dari portfolios) --}}
        @if ($portfolios && $portfolios->isNotEmpty())
            <div class="mt-20 border-t border-white/20 pt-16">
                <div class="text-center mb-12">
                    <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-2">Potensi Daerah</p>
                    <h3 class="text-2xl font-black text-[#5d1e25]">Peluang Investasi Unggulan</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($portfolios as $portfolio)
                        <div class="group bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/40 hover:-translate-y-1 hover:bg-white/60 transition-all duration-300 shadow-sm">
                            <span class="text-[10px] font-bold text-[#5d1e25] uppercase tracking-wider bg-[#f4edd8] px-2.5 py-1 rounded-full">
                                {{ $portfolio->client }}
                            </span>
                            <h4 class="font-bold text-[#5d1e25] mt-4 mb-2 text-sm">{{ $portfolio->name }}</h4>
                            <p class="text-xs text-[#3c2f2f]/70 leading-relaxed">Tulang Bawang Barat menawarkan iklim investasi yang sehat dan dukungan infrastruktur penunjang.</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

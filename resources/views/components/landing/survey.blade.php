{{-- Survey SKM Section Component --}}
{{-- $gerais: Collection<Opd> --}}
<section id="survey" class="py-24 bg-gradient-to-br from-[#7b323b] via-[#6d272e] to-[#5d1e25] border-t border-b border-white/10">
    <div class="container mx-auto px-6">
        <div class="text-center mb-14">
            <p class="text-[#f4edd8] font-bold text-xs uppercase tracking-widest mb-3">Survei Kepuasan Masyarakat</p>
            <h2 class="text-3xl md:text-4xl font-black text-white">Berikan Penilaian Pelayanan</h2>
            <p class="text-rose-100/80 max-w-2xl mx-auto mt-4 leading-relaxed text-sm">
                Survei Kepuasan Masyarakat (SKM) berdasarkan Permen PAN-RB No. 14 Tahun 2017. Pilih gerai layanan yang Anda gunakan dan berikan penilaian Anda.
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 p-6 md:p-8 mb-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="stat-card p-4 rounded-2xl">
                        <iconify-icon icon="lucide:users" class="text-2xl text-[#f4edd8] mb-2"></iconify-icon>
                        <p class="text-2xl font-black text-white">9</p>
                        <p class="text-[10px] font-bold text-rose-200/70 uppercase tracking-widest mt-1">Unsur SKM</p>
                    </div>
                    <div class="stat-card p-4 rounded-2xl">
                        <iconify-icon icon="lucide:scale" class="text-2xl text-[#f4edd8] mb-2"></iconify-icon>
                        <p class="text-2xl font-black text-white">1-4</p>
                        <p class="text-[10px] font-bold text-rose-200/70 uppercase tracking-widest mt-1">Skala Nilai</p>
                    </div>
                    <div class="stat-card p-4 rounded-2xl">
                        <iconify-icon icon="lucide:bar-chart-3" class="text-2xl text-[#f4edd8] mb-2"></iconify-icon>
                        <p class="text-2xl font-black text-white">IKM</p>
                        <p class="text-[10px] font-bold text-rose-200/70 uppercase tracking-widest mt-1">Nilai Komposit</p>
                    </div>
                    <div class="stat-card p-4 rounded-2xl">
                        <iconify-icon icon="lucide:shield-check" class="text-2xl text-[#f4edd8] mb-2"></iconify-icon>
                        <p class="text-2xl font-black text-white">Gratis</p>
                        <p class="text-[10px] font-bold text-rose-200/70 uppercase tracking-widest mt-1">Tanpa Login</p>
                    </div>
                </div>
            </div>

            @if ($gerais->isNotEmpty())
                <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/10 p-6 md:p-8 mb-10">
                    <h3 class="text-white font-bold uppercase tracking-widest text-xs mb-5">Gerai yang Dapat Dinilai</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($gerais->take(6) as $gerai)
                            <a href="{{ route('survey.show', $gerai) }}"
                               class="group flex items-center justify-between gap-3 bg-white/5 hover:bg-[#f4edd8] hover:text-[#5d1e25] rounded-2xl px-4 py-3.5 border border-white/10 hover:border-[#f4edd8] transition-all duration-300">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="px-2 py-0.5 bg-[#f4edd8] text-[#5d1e25] text-[9px] font-black uppercase tracking-wider rounded-lg group-hover:bg-[#5d1e25] group-hover:text-[#f4edd8] transition-colors">{{ $gerai->gerais->first()->code }}</span>
                                    <span class="text-sm font-bold text-white truncate group-hover:text-[#5d1e25] transition-colors">{{ $gerai->name }}</span>
                                </div>
                                <iconify-icon icon="lucide:chevron-right" class="text-lg text-rose-200/70 group-hover:text-[#5d1e25] flex-shrink-0"></iconify-icon>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="text-center">
                <a href="{{ route('survey.index') }}"
                   class="btn-premium inline-flex items-center gap-2 font-black px-10 py-4 rounded-2xl shadow-lg hover:scale-105 transition-transform duration-300 text-lg">
                    <iconify-icon icon="lucide:clipboard-check" class="text-xl"></iconify-icon>
                    Mulai Survei Sekarang
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

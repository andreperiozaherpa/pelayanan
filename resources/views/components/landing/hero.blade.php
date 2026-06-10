{{-- Hero Section Component --}}
{{-- $hero: CmsWebsiteSection|null --}}
@if ($hero)
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-24 bg-gradient-to-br from-[#7b323b] via-[#6d272e] to-[#5d1e25]">
    {{-- Background mesh details --}}
    <div class="absolute inset-0 z-0 opacity-30 pointer-events-none">
        <div class="absolute top-10 left-10 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-[500px] h-[500px] bg-rose-500/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            {{-- Text/Sambutan Column --}}
            <div class="lg:col-span-7 text-left"
                 x-data="{ visible: false }"
                 x-init="setTimeout(() => visible = true, 100)"
                 x-show="visible"
                 x-transition:enter="transition duration-1000 ease-out"
                 x-transition:enter-start="opacity-0 translate-x-[-20px]"
                 x-transition:enter-end="opacity-100 translate-x-0">
                 
                <p class="inline-block text-[#f4edd8] font-semibold text-xs uppercase tracking-widest mb-4 border border-white/20 rounded-full px-4 py-1 bg-white/5 backdrop-blur-sm">
                    Portal Pelayanan Resmi
                </p>

                <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4 drop-shadow-md">
                    {{ $hero->title }}
                </h1>

                @if ($hero->subtitle)
                    <p class="text-base md:text-lg text-rose-200/80 mb-6 leading-relaxed">
                        {{ $hero->subtitle }}
                    </p>
                @endif

                @if ($hero->content)
                    <div class="border-l-4 border-[#f4edd8] pl-4 py-2 mb-8 bg-white/5 rounded-r-xl pr-4">
                        <span class="text-xs font-bold text-[#f4edd8] uppercase tracking-wider block mb-1">Sambutan Kepala Dinas:</span>
                        <p class="text-sm text-rose-100 italic leading-relaxed">
                            "{{ $hero->content }}"
                        </p>
                    </div>
                @endif

                <div class="flex flex-wrap gap-4 items-center">
                    @if ($hero->button_text && $hero->button_url)
                        <a href="{{ $hero->button_url }}" target="_blank"
                           class="btn-premium px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2 shadow-lg">
                            {{ $hero->button_text }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    @endif
                    <a href="#services"
                       class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold px-6 py-3.5 rounded-xl border border-white/10 transition-all duration-300 hover:-translate-y-0.5">
                        Layanan Online
                    </a>
                </div>
            </div>

            {{-- Illustration or Head of Office Column --}}
            <div class="lg:col-span-5 flex justify-center"
                 x-data="{ visible: false }"
                 x-init="setTimeout(() => visible = true, 200)"
                 x-show="visible"
                 x-transition:enter="transition duration-1000 ease-out"
                 x-transition:enter-start="opacity-0 translate-x-[20px]"
                 x-transition:enter-end="opacity-100 translate-x-0">
                 
                <div class="relative w-full max-w-sm">
                    <div class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-rose-500 rounded-3xl rotate-3 scale-105 opacity-20 blur-lg"></div>
                    <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl overflow-hidden p-4 shadow-2xl">
                        <div class="aspect-[3/4] w-full rounded-2xl bg-gradient-to-b from-[#6d272e] to-[#5d1e25] flex items-center justify-center overflow-hidden border border-white/10 relative">
                            <iconify-icon icon="lucide:user-round" class="text-7xl text-[#f4edd8]/20 absolute"></iconify-icon>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>
                            
                            {{-- Info Badge --}}
                            <div class="absolute bottom-4 left-4 right-4 z-20 bg-black/40 backdrop-blur-sm border border-white/10 p-3.5 rounded-xl text-center">
                                <h4 class="text-sm font-bold text-white">Drs. H. Syahrul, M.IP.</h4>
                                <p class="text-xs text-[#f4edd8] font-semibold mt-0.5">Kepala Dinas DPMPTSP</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <a href="#services">
            <svg class="w-6 h-6 text-white/40 hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </a>
    </div>
</section>
@endif

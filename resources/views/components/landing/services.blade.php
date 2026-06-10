{{-- Services Section Component --}}
{{-- $services: Collection<CmsService>|null --}}
@if ($services && $services->isNotEmpty())
<section id="services" class="py-24 bg-transparent">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Akses Cepat Layanan</p>
            <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25]">Sistem Pelayanan Terintegrasi</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($services as $service)
                <div class="group bg-white/40 backdrop-blur-md rounded-3xl p-8 border border-white/40 shadow-sm hover:shadow-xl hover:bg-white/60 hover:shadow-[#7b323b]/5 transition-all duration-300 hover:-translate-y-1">
                    @if ($service->icon)
                        <div class="w-14 h-14 bg-[#f4edd8] rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#5d1e25] group-hover:text-white transition-colors duration-300">
                            <iconify-icon icon="{{ $service->icon }}" class="text-2xl text-[#5d1e25] group-hover:text-[#f4edd8] transition-colors duration-300"></iconify-icon>
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-[#5d1e25] mb-3">{{ $service->name }}</h3>
                    <p class="text-[#3c2f2f]/70 leading-relaxed mb-6 text-sm">{{ $service->summary }}</p>
                    @if ($service->link_url)
                        <a href="{{ $service->link_url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 text-[#7b323b] font-bold text-sm hover:text-[#5d1e25] hover:gap-3 transition-all duration-300">
                            Buka Layanan
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

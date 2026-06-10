{{-- About & Vision/Mission Component --}}
{{-- $about: CmsPage|null, $vision: CmsWebsiteSection|null, $mission: CmsWebsiteSection|null --}}

@if ($about || $vision || $mission)
<section id="about" class="py-24 bg-transparent">
    <div class="container mx-auto px-6">

        {{-- About --}}
        @if ($about)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-24">
            @if ($about->featured_image)
                <div class="order-2 lg:order-1 relative">
                    <div class="absolute -inset-4 bg-[#6d272e]/10 rounded-3xl -rotate-3"></div>
                    <img src="{{ asset('storage/' . $about->featured_image) }}"
                         alt="{{ $about->title }}"
                         class="relative z-10 w-full rounded-2xl shadow-2xl object-cover aspect-[4/3] border border-[#6d272e]/10"
                         loading="lazy">
                </div>
            @endif
            <div class="{{ $about->featured_image ? 'order-1 lg:order-2' : '' }}">
                <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Tentang Kami</p>
                <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25] mb-6 leading-tight">
                    {{ $about->title }}
                </h2>
                @if ($about->content)
                    <p class="text-[#3c2f2f]/80 text-lg leading-relaxed mb-8">
                        {{ Str::limit(strip_tags($about->content), 250) }}
                    </p>
                @endif
                <a href="#" class="inline-flex items-center gap-2 text-[#7b323b] font-bold hover:text-[#5d1e25] hover:gap-4 transition-all duration-300">
                    Selengkapnya
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
        @endif

        {{-- Vision & Mission --}}
        @if ($vision || $mission)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @if ($vision)
            <div class="bg-gradient-to-br from-[#7b323b] to-[#5d1e25] rounded-3xl p-10 border border-white/10 shadow-xl text-white">
                <div class="w-14 h-14 bg-[#f4edd8] rounded-2xl flex items-center justify-center mb-6 text-[#5d1e25]">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-4">{{ $vision->title }}</h3>
                @if ($vision->content)
                    <p class="text-rose-100/80 leading-relaxed">{{ $vision->content }}</p>
                @endif
            </div>
            @endif

            @if ($mission)
            <div class="bg-gradient-to-br from-[#7b323b]/90 to-[#5d1e25]/90 rounded-3xl p-10 border border-white/10 shadow-xl text-white">
                <div class="w-14 h-14 bg-[#f4edd8] rounded-2xl flex items-center justify-center mb-6 text-[#5d1e25]">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-4">{{ $mission->title }}</h3>
                @if ($mission->content)
                    <p class="text-rose-100/80 leading-relaxed">{{ $mission->content }}</p>
                @endif
            </div>
            @endif
        </div>
        @endif

    </div>
</section>
@endif

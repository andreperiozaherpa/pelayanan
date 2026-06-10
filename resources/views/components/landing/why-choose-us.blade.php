{{-- Why Choose Us Component --}}
{{-- $whyChooseUs: Collection<CmsWhyChooseUs>|null --}}
@if ($whyChooseUs && $whyChooseUs->isNotEmpty())
<section id="why-choose-us" class="py-24 bg-transparent">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            {{-- Illustration --}}
            <div class="hidden lg:block">
                <div class="bg-gradient-to-br from-[#7b323b] to-[#5d1e25] rounded-3xl p-12 text-center border border-white/10 shadow-xl">
                    <svg class="w-48 h-48 mx-auto text-[#f4edd8]/80" fill="none" viewBox="0 0 200 200" stroke="currentColor">
                        <circle cx="100" cy="100" r="80" stroke-width="4" opacity="0.2"/>
                        <path d="M60 100 L85 125 L140 75" stroke-linecap="round" stroke-linejoin="round" stroke-width="8"/>
                    </svg>
                    <p class="text-white/80 font-bold text-lg mt-6">Pilihan Terbaik untuk Anda</p>
                </div>
            </div>

            {{-- Reasons list --}}
            <div>
                <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Keunggulan Kami</p>
                <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25] mb-10 leading-tight">
                    Mengapa Memilih Kami?
                </h2>

                <div class="space-y-6">
                    @foreach ($whyChooseUs as $item)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#f4edd8] rounded-2xl flex items-center justify-center flex-shrink-0 text-[#5d1e25]">
                                @if ($item->icon)
                                    <iconify-icon icon="{{ $item->icon }}" class="text-xl text-[#5d1e25]"></iconify-icon>
                                @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-[#5d1e25] mb-1">{{ $item->title }}</h4>
                                <p class="text-[#3c2f2f]/70 text-sm leading-relaxed">{{ $item->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

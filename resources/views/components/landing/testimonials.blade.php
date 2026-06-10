{{-- Testimonials Carousel Component --}}
{{-- $testimonials: Collection<CmsTestimonial>|null --}}
@if ($testimonials && $testimonials->isNotEmpty())
<section id="testimonials" class="py-24 bg-transparent">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Suara Masyarakat & Pelaku Usaha</p>
            <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25]">Survei Kepuasan Masyarakat</h2>
        </div>

        <div x-data="{ active: 0, total: {{ $testimonials->count() }} }" class="relative">
            <div class="overflow-hidden rounded-3xl">
                @foreach ($testimonials as $index => $testimonial)
                    <div x-show="active === {{ $index }}"
                          x-transition:enter="transition duration-500 ease-out"
                          x-transition:enter-start="opacity-0 translate-x-10"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          x-transition:leave="transition duration-300 ease-in"
                          x-transition:leave-start="opacity-100"
                          x-transition:leave-end="opacity-0"
                          class="bg-white/40 backdrop-blur-md p-10 md:p-14 rounded-3xl border border-white/40 shadow-sm">
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            <div class="flex-shrink-0">
                                @if ($testimonial->avatar)
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                          alt="{{ $testimonial->name }}"
                                          class="w-20 h-20 rounded-full object-cover border-4 border-[#f4edd8]"
                                          loading="lazy">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-[#f4edd8] flex items-center justify-center border-4 border-white/10">
                                        <iconify-icon icon="lucide:user" class="text-3xl text-[#5d1e25]"></iconify-icon>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Stars --}}
                                <div class="flex gap-1 mb-4">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <iconify-icon icon="{{ $s <= ($testimonial->rating ?? 5) ? 'mdi:star' : 'mdi:star-outline' }}"
                                                      class="text-amber-500 text-xl"></iconify-icon>
                                    @endfor
                                </div>

                                <blockquote class="text-[#3c2f2f]/80 text-lg leading-relaxed mb-6 italic">
                                    "{{ $testimonial->content }}"
                                </blockquote>

                                <div>
                                    <p class="font-bold text-[#5d1e25]">{{ $testimonial->name }}</p>
                                    <p class="text-xs text-[#3c2f2f]/60 font-medium">
                                        {{ $testimonial->position }}
                                        @if ($testimonial->company) — {{ $testimonial->company }} @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Navigation --}}
            <div class="flex justify-center items-center gap-4 mt-8">
                <button @click="active = (active - 1 + total) % total"
                        class="w-10 h-10 rounded-full bg-[#f4edd8] flex items-center justify-center text-[#5d1e25] hover:bg-[#5d1e25] hover:text-[#f4edd8] transition-all duration-200 shadow-sm border border-white/10">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="flex gap-2">
                    @foreach ($testimonials as $index => $testimonial)
                        <button @click="active = {{ $index }}"
                                :class="active === {{ $index }} ? 'bg-[#5d1e25] w-6' : 'bg-[#7b323b]/20 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>

                <button @click="active = (active + 1) % total"
                        class="w-10 h-10 rounded-full bg-[#f4edd8] flex items-center justify-center text-[#5d1e25] hover:bg-[#5d1e25] hover:text-[#f4edd8] transition-all duration-200 shadow-sm border border-white/10">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>
@endif

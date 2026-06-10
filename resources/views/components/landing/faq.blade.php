{{-- FAQ Section (Accordion) Component --}}
{{-- $faqs: Collection<CmsFaq>|null --}}
@if ($faqs && $faqs->isNotEmpty())
<section id="faq" class="py-24 bg-transparent">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-16">
            <div class="lg:col-span-2">
                <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Bantuan</p>
                <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25] mb-6">
                    Pertanyaan yang Sering Ditanya
                </h2>
                <p class="text-[#3c2f2f]/70 leading-relaxed">
                    Tidak menemukan jawaban yang Anda cari? Hubungi tim kami secara langsung.
                </p>
            </div>

            <div class="lg:col-span-3 space-y-4">
                @foreach ($faqs as $index => $faq)
                    <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                          class="bg-white/40 backdrop-blur-md rounded-2xl border border-white/40 overflow-hidden shadow-sm">
                        <button @click="open = !open"
                                class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-bold text-[#5d1e25] pr-4">{{ $faq->question }}</span>
                            <div :class="open ? 'rotate-45' : ''"
                                 class="flex-shrink-0 w-8 h-8 bg-[#f4edd8] rounded-full flex items-center justify-center transition-transform duration-300">
                                <svg class="w-4 h-4 text-[#5d1e25]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition duration-300 ease-out"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition duration-200 ease-in"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            <div class="px-6 pb-6 text-[#3c2f2f]/80 leading-relaxed border-t border-white/20 pt-4">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

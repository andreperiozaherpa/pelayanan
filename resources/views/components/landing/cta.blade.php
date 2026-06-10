{{-- CTA Section Component --}}
{{-- $cta: CmsWebsiteSection|null --}}
@if ($cta)
<section id="cta" class="py-24 bg-gradient-to-br from-[#7b323b] via-[#6d272e] to-[#5d1e25] border-t border-b border-white/10">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-5xl font-black text-white mb-6 leading-tight">
            {{ $cta->title }}
        </h2>
        @if ($cta->subtitle)
            <p class="text-rose-100 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                {{ $cta->subtitle }}
            </p>
        @endif
        @if ($cta->button_text && $cta->button_url)
            <a href="{{ $cta->button_url }}"
               class="btn-premium inline-flex items-center gap-2 font-black px-10 py-4 rounded-2xl shadow-lg hover:scale-105 transition-transform duration-300 text-lg">
                {{ $cta->button_text }}
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        @endif
    </div>
</section>
@endif

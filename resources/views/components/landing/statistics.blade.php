{{-- Statistics Section Component --}}
{{-- $statistics: Collection<CmsStatistic>|null --}}
@if ($statistics && $statistics->isNotEmpty())
<section id="statistics" class="py-12 md:py-16 bg-[#6d272e] border-b border-white/10">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            @foreach ($statistics as $stat)
                <div class="stat-card p-4 sm:p-6 rounded-2xl text-center overflow-hidden flex flex-col justify-center items-center"
                     x-data="{ count: 0, target: {{ (int) $stat->value }} }"
                     x-intersect="
                         let step = Math.ceil(target / 60);
                         let interval = setInterval(() => {
                             count = Math.min(count + step, target);
                             if (count >= target) clearInterval(interval);
                         }, 20);
                     ">
                    @if ($stat->icon)
                        <div class="text-3xl sm:text-4xl mb-2 sm:mb-3">
                            <iconify-icon icon="{{ $stat->icon }}" class="text-[#f4edd8]/80"></iconify-icon>
                        </div>
                    @endif
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-1 sm:mb-2 break-all" x-text="count.toLocaleString() + '+'">
                        {{ number_format($stat->value) }}+
                    </div>
                    <p class="text-[#f4edd8] font-bold text-[9px] sm:text-xs uppercase tracking-wider line-clamp-2 break-words max-w-full">
                        {{ $stat->label }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif


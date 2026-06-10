{{-- Statistics Section Component --}}
{{-- $statistics: Collection<CmsStatistic>|null --}}
@if ($statistics && $statistics->isNotEmpty())
<section id="statistics" class="py-16 bg-[#6d272e] border-b border-white/10">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach ($statistics as $stat)
                <div class="stat-card p-6 rounded-2xl text-center"
                     x-data="{ count: 0, target: {{ (int) $stat->value }} }"
                     x-intersect="
                         let step = Math.ceil(target / 60);
                         let interval = setInterval(() => {
                             count = Math.min(count + step, target);
                             if (count >= target) clearInterval(interval);
                         }, 20);
                     ">
                    @if ($stat->icon)
                        <div class="text-4xl mb-3">
                            <iconify-icon icon="{{ $stat->icon }}" class="text-[#f4edd8]/80"></iconify-icon>
                        </div>
                    @endif
                    <div class="text-5xl font-black text-white mb-2" x-text="count.toLocaleString() + '+'">
                        {{ number_format($stat->value) }}+
                    </div>
                    <p class="text-[#f4edd8] font-semibold text-xs uppercase tracking-wider">{{ $stat->label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

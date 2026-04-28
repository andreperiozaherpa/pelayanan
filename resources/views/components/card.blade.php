@props([
    'title' => null,
    'description' => null,
    'padding' => 'p-6',
    'headerClass' => 'px-6 py-4 border-b border-black/[0.03] dark:border-white/[0.03]'
])

<div {{ $attributes->merge(['class' => 'premium-card overflow-hidden']) }}>
    @if($title || $description || isset($header))
        <div class="{{ $headerClass }}">
            @if(isset($header))
                {{ $header }}
            @else
                @if($title)
                    <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="text-[10px] text-slate-500 font-medium mt-1">{{ $description }}</p>
                @endif
            @endif
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-black/[0.01] dark:bg-white/[0.01] border-t border-black/[0.03] dark:border-white/[0.03]">
            {{ $footer }}
        </div>
    @endif
</div>

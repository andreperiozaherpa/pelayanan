@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-4 px-4 py-3 mb-1 text-[11px] font-black bg-white dark:bg-slate-800 text-primary-acorn rounded-2xl transition-all duration-300 shadow shadow-black/[0.04] translate-x-1'
            : 'flex items-center gap-4 px-4 py-3 mb-1 text-[11px] font-bold text-slate-400 dark:text-slate-500 hover:text-primary-acorn hover:bg-white/50 dark:hover:bg-slate-800/30 rounded-2xl transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <iconify-icon icon="{{ $icon }}" class="text-xl flex-shrink-0 {{ ($active ?? false) ? 'opacity-100' : 'opacity-40' }}"></iconify-icon>
    <span class="tracking-tight">{{ $slot }}</span>
</a>

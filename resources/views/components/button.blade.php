@props([
    'variant' => 'primary', // primary, secondary, danger, ghost
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-black uppercase tracking-widest transition-all duration-300 rounded-xl disabled:opacity-50 disabled:pointer-events-none group';
    
    $variants = [
        'primary' => 'bg-primary-acorn text-white shadow-lg shadow-primary-acorn/20 hover:scale-[1.02] active:scale-[0.98]',
        'secondary' => 'bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 shadow-sm',
        'danger' => 'bg-rose-500 text-white shadow-lg shadow-rose-500/20 hover:scale-[1.02] active:scale-[0.98]',
        'ghost' => 'text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-[8px] gap-1.5',
        'md' => 'px-5 py-2.5 text-[9px] gap-2',
        'lg' => 'px-8 py-3.5 text-[10px] gap-2.5',
    ];

    $classes = ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $classes]) }}>
        @if($icon)
            <iconify-icon icon="{{ $icon }}" class="text-base group-hover:scale-110 transition"></iconify-icon>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $classes]) }}>
        @if($icon)
            <iconify-icon icon="{{ $icon }}" class="text-base group-hover:scale-110 transition"></iconify-icon>
        @endif
        {{ $slot }}
    </button>
@endif

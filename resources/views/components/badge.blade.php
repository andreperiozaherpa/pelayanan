@props([
    'variant' => 'primary', // primary, success, danger, warning, info, slate
    'label' => null,
])

@php
    $variants = [
        'primary' => 'bg-primary-acorn/10 text-primary-acorn',
        'success' => 'bg-emerald-500/10 text-emerald-500',
        'danger' => 'bg-rose-500/10 text-rose-500',
        'warning' => 'bg-amber-500/10 text-amber-500',
        'info' => 'bg-blue-500/10 text-blue-500',
        'slate' => 'bg-slate-500/10 text-slate-500',
    ];

    $classes = $variants[$variant] ?? $variants['primary'];
@endphp

<span {{ $attributes->merge(['class' => 'px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest inline-flex items-center justify-center ' . $classes]) }}>
    {{ $label ?? $slot }}
</span>

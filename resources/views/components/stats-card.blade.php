@props([
    'label' => null,
    'value' => 0,
    'icon' => 'lucide:activity',
    'color' => 'primary', // primary, emerald, amber, rose, blue
])

@php
    $colors = [
        'primary' => 'bg-primary-acorn/10 text-primary-acorn',
        'emerald' => 'bg-emerald-500/10 text-emerald-500',
        'amber'   => 'bg-amber-500/10 text-amber-500',
        'rose'    => 'bg-rose-500/10 text-rose-500',
        'blue'    => 'bg-blue-500/10 text-blue-500',
    ];

    $textColors = [
        'primary' => 'text-slate-800 dark:text-white',
        'emerald' => 'text-emerald-500',
        'amber'   => 'text-amber-500',
        'rose'    => 'text-rose-500',
        'blue'    => 'text-blue-500',
    ];

    $colorClass = $colors[$color] ?? $colors['primary'];
    $valueClass = $textColors[$color] ?? $textColors['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'premium-card p-5 xl:p-6 flex items-center gap-5 group hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300']) }}>
    <div class="w-12 h-12 rounded-xl {{ $colorClass }} flex items-center justify-center shrink-0">
        <iconify-icon icon="{{ $icon }}" class="text-2xl group-hover:scale-110 transition duration-300"></iconify-icon>
    </div>
    <div class="min-w-0">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">{{ $label }}</p>
        <p class="text-xl font-black {{ $valueClass }} mt-0.5 tabular-nums truncate">
            {{ is_numeric($value) ? number_format($value) : $value }}
        </p>
    </div>
</div>

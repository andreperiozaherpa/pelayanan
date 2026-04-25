@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-3 text-xs font-bold bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500 transition-all duration-200'
            : 'flex items-center gap-3 px-4 py-3 text-xs font-medium text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
    </svg>
    <span>{{ $slot }}</span>
</a>

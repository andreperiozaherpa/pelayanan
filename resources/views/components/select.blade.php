@props([
    'label' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'select2' => false,
])

<div class="space-y-1.5 w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">
            {{ $label }} @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    <div class="relative">
        <select 
            name="{{ $name }}" 
            id="{{ $name }}" 
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full pl-4 pr-10 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-primary-acorn/10 transition appearance-none cursor-pointer shadow-sm' . ($select2 ? ' select2-hidden' : '')]) }}
        >
            {{ $slot }}
        </select>
        @unless($select2)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
            <iconify-icon icon="lucide:chevron-down" class="text-xs"></iconify-icon>
        </div>
        @endunless
    </div>

    @error($name)
        <p class="text-[9px] font-bold text-rose-500 mt-1 px-1 uppercase tracking-tighter">{{ $message }}</p>
    @enderror
</div>

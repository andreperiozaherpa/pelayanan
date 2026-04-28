@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'icon' => null,
    'helper' => null,
])

<div class="space-y-1.5 w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">
            {{ $label }} @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    <div class="relative group flex items-center">
        @if(isset($prepend))
            {{ $prepend }}
        @endif

        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-primary-acorn transition">
                <iconify-icon icon="{{ $icon }}" class="text-lg"></iconify-icon>
            </div>
        @endif

        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-primary-acorn/10 transition shadow-sm placeholder-slate-300 ' . ($icon ? 'pl-11' : 'px-4') . ' py-2.5']) }}
        >

        @if(isset($append))
            {{ $append }}
        @endif
    </div>

    @error($name)
        <p class="text-[9px] font-bold text-rose-500 mt-1 px-1 uppercase tracking-tighter">{{ $message }}</p>
    @enderror

    @if($helper)
        <p class="text-[8px] text-slate-400 mt-1 px-1">{{ $helper }}</p>
    @endif
</div>

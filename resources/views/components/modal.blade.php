@props([
    'name',
    'title' => null,
    'maxWidth' => '2xl'
])

@php
    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
    ][$maxWidth];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail.name === '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-[100]"
    style="display: none;"
>
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    </div>

    <div
        x-show="show"
        class="mb-6 bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-2xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto border border-white/40 dark:border-white/10"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        @if($title)
            <div class="px-8 py-6 border-b border-black/[0.03] dark:border-white/[0.03] flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">{{ $title }}</h3>
                <button x-on:click="show = false" class="text-slate-400 hover:text-rose-500 transition">
                    <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
                </button>
            </div>
        @endif

        <div class="px-8 py-8">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="px-8 py-6 bg-slate-50 dark:bg-white/[0.02] border-t border-black/[0.03] dark:border-white/[0.03] flex justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

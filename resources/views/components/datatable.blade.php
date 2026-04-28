@props([
    'records' => null,
    'search' => true,
    'searchPlaceholder' => 'Cari data...',
    'searchValue' => request('search'),
    'searchAction' => null,
])

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @if($search)
        <x-card padding="p-4">
            <form method="GET" action="{{ $searchAction ?? url()->current() }}" class="flex flex-col sm:flex-row gap-3 items-end">
                <div class="flex-1">
                    <x-input name="search" :value="$searchValue" :placeholder="$searchPlaceholder" icon="lucide:search" />
                </div>
                
                <div class="flex gap-2">
                    <x-button type="submit" variant="secondary" class="h-[42px]">
                        Cari
                    </x-button>
                    
                    @if ($searchValue)
                        <x-button href="{{ $searchAction ?? url()->current() }}" variant="secondary" class="h-[42px]">
                            Reset
                        </x-button>
                    @endif
                </div>

                @if(isset($filters))
                    {{ $filters }}
                @endif
            </form>
        </x-card>
    @endif

    <x-card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                        {{ $thead }}
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                    {{ $slot }}
                </tbody>
            </table>
        </div>

        @if ($records && method_exists($records, 'hasPages') && $records->hasPages())
            <div class="px-6 py-4 border-t border-black/[0.02] dark:border-white/[0.02] bg-slate-50/50 dark:bg-slate-800/30">
                <x-pagination :records="$records" />
            </div>
        @endif
    </x-card>
</div>

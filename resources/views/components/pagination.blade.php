@props(['records'])

@if ($records && method_exists($records, 'hasPages') && $records->hasPages())
    <div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row items-center justify-between gap-4']) }}>
        <!-- Showing Info -->
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest order-2 md:order-1">
            Menampilkan {{ $records->firstItem() }} - {{ $records->lastItem() }} Dari {{ $records->total() }} Data
        </p>

        <!-- Custom Pagination Controls -->
        <div class="flex items-center gap-2 order-1 md:order-2">
            {{-- Previous Button --}}
            @if ($records->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-300 cursor-not-allowed">
                    <iconify-icon icon="lucide:chevron-left"></iconify-icon>
                </span>
            @else
                <a href="{{ $records->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-slate-500 hover:text-primary-acorn border border-black/5 transition shadow-sm">
                    <iconify-icon icon="lucide:chevron-left"></iconify-icon>
                </a>
            @endif

            {{-- Page Numbers (Responsive) --}}
            <div class="flex items-center gap-1.5">
                @php
                    $currentPage = $records->currentPage();
                    $lastPage = $records->lastPage();
                    $sidePages = 1;
                @endphp

                {{-- First Page --}}
                @if($currentPage > ($sidePages + 2))
                    <a href="{{ $records->url(1) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-[10px] font-black text-slate-500 hover:text-primary-acorn border border-black/5 transition shadow-sm">1</a>
                    <span class="text-slate-300 text-[10px]">...</span>
                @endif

                {{-- Dynamic Range --}}
                @for ($i = max(1, $currentPage - $sidePages); $i <= min($lastPage, $currentPage + $sidePages); $i++)
                    @if ($i == $currentPage)
                        {{-- Current Page Input (Jump to Page) --}}
                        <form action="{{ url()->current() }}" method="GET" class="relative group">
                            @foreach(request()->except('page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <input type="text" 
                                name="page" 
                                value="{{ $i }}" 
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                onchange="this.form.submit()"
                                class="w-12 h-9 text-center bg-primary-acorn text-white rounded-xl font-black text-[10px] border-none focus:ring-2 focus:ring-primary-acorn/50 shadow-lg shadow-primary-acorn/20 outline-none transition-all"
                            >
                            <div class="absolute -top-9 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[8px] font-black uppercase rounded opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none shadow-xl z-50">
                                Lompat Ke
                                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-slate-800 rotate-45"></div>
                            </div>
                        </form>
                    @else
                        <a href="{{ $records->url($i) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-[10px] font-black text-slate-500 hover:text-primary-acorn border border-black/5 transition shadow-sm">{{ $i }}</a>
                    @endif
                @endfor

                {{-- Last Page --}}
                @if($currentPage < ($lastPage - $sidePages - 1))
                    <span class="text-slate-300 text-[10px]">...</span>
                    <a href="{{ $records->url($lastPage) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-[10px] font-black text-slate-500 hover:text-primary-acorn border border-black/5 transition shadow-sm">{{ $lastPage }}</a>
                @endif
            </div>

            {{-- Next Button --}}
            @if ($records->hasMorePages())
                <a href="{{ $records->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-slate-500 hover:text-primary-acorn border border-black/5 transition shadow-sm">
                    <iconify-icon icon="lucide:chevron-right"></iconify-icon>
                </a>
            @else
                <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-300 cursor-not-allowed">
                    <iconify-icon icon="lucide:chevron-right"></iconify-icon>
                </span>
            @endif
        </div>
    </div>
@endif

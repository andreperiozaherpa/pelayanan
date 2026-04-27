@extends('layouts.app')

@section('title', 'Riwayat Aktivitas Terpadu')

@section('content')
    <div class="space-y-8">
        <!-- Header & Controls -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    Timeline Aktivitas
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                    Log kronologis audit dan verifikasi pelayanan sistem.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Items per Page -->
                <form action="{{ route('dashboard.history') }}" method="GET" id="perPageForm" class="relative">
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <select name="per_page" onchange="document.getElementById('perPageForm').submit()"
                        class="pl-4 pr-10 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-primary-acorn/10 transition appearance-none cursor-pointer shadow-sm">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / HAL</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 / HAL</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / HAL</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / HAL</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <iconify-icon icon="lucide:chevron-down" class="text-xs"></iconify-icon>
                    </div>
                </form>

                <!-- Search -->
                <form action="{{ route('dashboard.history') }}" method="GET" class="relative">
                    @if ($perPage != 15)
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300">
                        <iconify-icon icon="lucide:search" class="text-lg"></iconify-icon>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="CARI NIK..."
                        class="w-full sm:w-64 pl-11 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-primary-acorn/10 transition shadow-sm placeholder-slate-300">
                    @if ($search)
                        <a href="{{ route('dashboard.history', ['per_page' => $perPage]) }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-rose-500 transition">
                            <iconify-icon icon="lucide:x" class="text-lg"></iconify-icon>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Timeline Content -->
        @php $globalCounter = ($paginator->currentPage() - 1) * $paginator->perPage(); @endphp
        <div class="space-y-12">
            @forelse($logs as $date => $dayLogs)
                <div class="space-y-6">
                    <!-- Date Divider -->
                    <div class="flex items-center gap-4">
                        <div class="premium-card px-4 py-1.5 bg-slate-50 dark:bg-slate-900 shadow-none">
                            <span class="text-[9px] font-black text-primary-acorn uppercase tracking-[0.2em]">
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="flex-grow h-px bg-black/[0.03] dark:bg-white/[0.03]"></div>
                    </div>

                    <!-- Log Entries -->
                    <div class="grid gap-4">
                        @foreach ($dayLogs as $log)
                            @php $globalCounter++; @endphp
                            <div
                                class="premium-card p-5 flex items-center gap-6 group hover:border-primary-acorn/20 transition duration-300">
                                <!-- Icon & Number -->
                                <div class="flex items-center gap-4 shrink-0">
                                    <span
                                        class="text-[10px] font-black text-slate-300 tabular-nums w-4 text-right">{{ $globalCounter }}</span>
                                    <div
                                        class="w-12 h-12 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-black/[0.02]">
                                        {{ $log['icon'] }}
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="text-[9px] font-black text-primary-acorn uppercase tracking-widest truncate max-w-[120px]">{{ $log['user'] }}</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                        <span
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">{{ $log['action'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if ($log['target'] !== '-')
                                            <span
                                                class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-tight">{{ $log['target'] }}</span>
                                        @endif
                                        @if ($log['result'])
                                            <span
                                                class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $log['result'] === 'ACTIVE'
                                                    ? 'bg-emerald-500/10 text-emerald-500'
                                                    : ($log['result'] === 'EXPIRED'
                                                        ? 'bg-rose-500/10 text-rose-500'
                                                        : 'bg-amber-500/10 text-amber-500') }}">
                                                {{ $log['result'] }}
                                            </span>
                                        @endif
                                        <span
                                            class="text-[10px] font-medium text-slate-400 uppercase tracking-tight truncate">{{ $log['details'] }}</span>
                                    </div>
                                </div>

                                <!-- Time & Action -->
                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    <span class="text-[10px] font-black text-slate-300 tabular-nums">
                                        {{ \Carbon\Carbon::parse($log['timestamp'])->format('H:i') }}
                                    </span>
                                    @if ($log['type'] === 'VERIFICATION' && strlen($log['target']) === 16)
                                        @can('poverty.print_proof')
                                            <a href="{{ route('dashboard.proof', ['nik' => $log['target']]) }}" target="_blank"
                                                class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-400 rounded-xl hover:bg-primary-acorn hover:text-white transition opacity-0 group-hover:opacity-100 shadow-sm border border-black/[0.03]">
                                                <iconify-icon icon="lucide:printer" class="text-lg"></iconify-icon>
                                            </a>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="premium-card p-20 text-center border-dashed border-2 border-slate-200 dark:border-white/5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada aktivitas tercatat
                        {{ $search ? 'untuk pencarian tersebut' : '' }}.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($paginator->hasPages())
            <div class="flex flex-col items-center gap-6 pt-10">
                <nav class="flex items-center gap-2">
                    @if ($paginator->onFirstPage())
                        <span
                            class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-900/50 rounded-xl text-slate-300 cursor-not-allowed">
                            <iconify-icon icon="lucide:chevron-left" class="text-lg"></iconify-icon>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-900 border border-black/[0.03] rounded-xl text-slate-500 hover:text-primary-acorn transition shadow-sm">
                            <iconify-icon icon="lucide:chevron-left" class="text-lg"></iconify-icon>
                        </a>
                    @endif

                    <div class="hidden md:flex items-center gap-2">
                        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    class="w-10 h-10 flex items-center justify-center bg-primary-acorn rounded-xl text-white font-black text-[10px] shadow-lg shadow-primary-acorn/20">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-900 border border-black/[0.03] rounded-xl text-[10px] font-black text-slate-500 hover:text-primary-acorn transition shadow-sm">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-900 border border-black/[0.03] rounded-xl text-slate-500 hover:text-primary-acorn transition shadow-sm">
                            <iconify-icon icon="lucide:chevron-right" class="text-lg"></iconify-icon>
                        </a>
                    @else
                        <span
                            class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-900/50 rounded-xl text-slate-300 cursor-not-allowed">
                            <iconify-icon icon="lucide:chevron-right" class="text-lg"></iconify-icon>
                        </span>
                    @endif
                </nav>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
                    MENAMPILKAN {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} DARI
                    {{ $paginator->total() }} AKTIVITAS
                </p>
            </div>
        @endif
    </div>

    </div>
@endsection

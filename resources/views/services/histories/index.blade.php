@extends('layouts.app')

@section('title', 'Riwayat Aktivitas Terpadu')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Timeline Aktivitas</h1>
            <p class="text-slate-400 mt-1">Log kronologis audit & verifikasi.</p>
        </div>
        
        <!-- Controls -->
        <div class="flex items-center gap-3">
            <!-- Items per Page -->
            <form action="{{ route('dashboard.history') }}" method="GET" id="perPageForm" class="block relative">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="per_page" onchange="document.getElementById('perPageForm').submit()" 
                    class="pl-4 pr-10 py-3 bg-slate-800/50 border border-slate-700 rounded-2xl text-white text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500/50 transition appearance-none cursor-pointer">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 / hal</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / hal</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 / hal</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </form>

            <!-- Search Form -->
            <form action="{{ route('dashboard.history') }}" method="GET" class="relative group">
                @if($perPage != 15) <input type="hidden" name="per_page" value="{{ $perPage }}"> @endif
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK..." 
                    class="w-full md:w-64 pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-2xl text-white text-sm outline-none focus:ring-2 focus:ring-indigo-500/50 transition">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                @if($search)
                <a href="{{ route('dashboard.history', ['per_page' => $perPage]) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Timeline Container -->
    @php $globalCounter = ($paginator->currentPage() - 1) * $paginator->perPage(); @endphp
    <div class="space-y-10">
        @forelse($logs as $date => $dayLogs)
        <div class="space-y-4">
            <!-- Date Header -->
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 px-4 py-1.5 bg-slate-800 rounded-xl border border-slate-700">
                    <span class="text-xs font-black text-indigo-400 uppercase tracking-widest">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div class="flex-grow h-px bg-slate-800"></div>
            </div>

            <!-- Day's Logs -->
            <div class="grid gap-3">
                @foreach($dayLogs as $log)
                @php $globalCounter++; @endphp
                <div class="flex items-center gap-4 p-4 bg-slate-800/30 border border-slate-700/50 rounded-2xl hover:bg-slate-800/50 transition group">
                    <!-- Global Number & Icon -->
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="text-[10px] font-black text-slate-600 w-4 text-right tabular-nums">{{ $globalCounter }}</span>
                        <div class="w-10 h-10 bg-slate-900 border border-slate-700 rounded-xl flex items-center justify-center text-lg shadow-inner">
                            {{ $log['icon'] }}
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest truncate max-w-[100px]">{{ $log['user'] }}</span>
                            <span class="text-slate-600 font-bold">•</span>
                            <span class="text-xs text-slate-300 font-bold">{{ $log['action'] }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-sm font-bold text-white font-mono">{{ $log['target'] !== '-' ? $log['target'] : '' }}</span>
                            @if($log['result'])
                            <span class="px-1.5 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest {{ 
                                $log['result'] === 'ACTIVE' ? 'bg-emerald-500/20 text-emerald-400' : 
                                ($log['result'] === 'EXPIRED' ? 'bg-rose-500/20 text-rose-400' : 'bg-amber-500/20 text-amber-400')
                            }}">
                                {{ $log['result'] }}
                            </span>
                            @endif
                            <span class="text-slate-500 text-[10px] italic truncate">{{ $log['details'] }}</span>
                        </div>
                    </div>

                    <!-- Time & Action -->
                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                        <span class="text-[10px] font-mono text-slate-500 tabular-nums">
                            {{ \Carbon\Carbon::parse($log['timestamp'])->format('H:i') }}
                        </span>
                        @if($log['type'] === 'VERIFICATION' && strlen($log['target']) === 16)
                        @can('poverty.print_proof')
                        <a href="{{ route('dashboard.proof', ['nik' => $log['target']]) }}" target="_blank" 
                            class="p-1.5 bg-slate-700 text-slate-400 rounded-lg hover:bg-indigo-600 hover:text-white transition opacity-0 group-hover:opacity-100 shadow-lg">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m32 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </a>
                        @endcan
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-slate-800/20 rounded-[3rem] border border-dashed border-slate-700">
            <p class="text-slate-500">Belum ada aktivitas tercatat {{ $search ? 'untuk pencarian tersebut' : '' }}.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if($paginator->hasPages())
    <div class="mt-12 flex justify-center">
        <nav class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-xl text-slate-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-slate-400 hover:text-indigo-400 hover:border-indigo-500/50 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
            @endif

            {{-- Summary for Mobile --}}
            <span class="md:hidden text-xs font-bold text-slate-500 uppercase tracking-widest px-4">
                Hal {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            {{-- Page Numbers --}}
            <div class="hidden md:flex items-center gap-2">
                @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2 bg-indigo-600 border border-indigo-500 rounded-xl text-white font-bold shadow-lg shadow-indigo-500/20">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-slate-400 hover:text-indigo-400 hover:border-indigo-500/50 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-slate-400 hover:text-indigo-400 hover:border-indigo-500/50 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            @else
                <span class="px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-xl text-slate-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </span>
            @endif
        </nav>
    </div>
    <div class="text-center mt-4">
        <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">
            Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} Aktivitas
        </p>
    </div>
    @endif
</div>

</div>
@endsection

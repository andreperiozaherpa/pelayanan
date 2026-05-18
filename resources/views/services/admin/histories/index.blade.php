@extends('layouts.app')

@section('title', 'Riwayat Aktivitas Terpadu')

@section('content')
    <div class="space-y-10">
        <!-- Header & Controls -->
        <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-6">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    Timeline Aktivitas
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                    Log kronologis audit dan verifikasi pelayanan sistem secara terintegrasi.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-end gap-3">
                <!-- Items per Page -->
                <form action="{{ route('services.history') }}" method="GET" id="perPageForm" class="w-32">
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <x-select name="per_page" onchange="document.getElementById('perPageForm').submit()" label="Show">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 BARIS</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 BARIS</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 BARIS</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 BARIS</option>
                    </x-select>
                </form>

                <!-- Search -->
                <form action="{{ route('services.history') }}" method="GET" class="flex-1 sm:w-64">
                    @if ($perPage != 15)
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                    @endif
                    <x-input name="search" :value="$search" placeholder="CARI NIK..." icon="lucide:search">
                        @if ($search)
                            <x-slot:append>
                                <a href="{{ route('services.history', ['per_page' => $perPage]) }}"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-rose-500 transition">
                                    <iconify-icon icon="lucide:x" class="text-lg"></iconify-icon>
                                </a>
                            </x-slot:append>
                        @endif
                    </x-input>
                </form>
            </div>
        </div>

        <!-- Timeline Content -->
        <div class="relative">
            <!-- Vertical Timeline Line -->
            <div class="absolute left-6 top-0 bottom-0 w-px bg-slate-200 dark:bg-slate-800 hidden md:block"></div>

            <div class="space-y-12">
                @forelse($logs as $date => $dayLogs)
                    <div class="space-y-6 relative">
                        <!-- Date Divider -->
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="premium-card px-4 py-1.5 bg-white dark:bg-slate-900 border border-black/5 dark:border-white/5">
                                <span class="text-[9px] font-black text-primary-acorn uppercase tracking-[0.2em]">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Log Entries -->
                        <div class="grid gap-4 ml-0 md:ml-12">
                            @foreach ($dayLogs as $log)
                                <div class="premium-card p-4 flex flex-col sm:flex-row sm:items-center gap-4 group hover:border-primary-acorn/20 transition duration-300 relative">
                                    <!-- Timeline Dot -->
                                    <div class="absolute -left-[54px] top-1/2 -translate-y-1/2 w-3 h-3 rounded-full bg-slate-200 dark:bg-slate-700 border-4 border-white dark:border-slate-900 hidden md:block group-hover:bg-primary-acorn group-hover:scale-125 transition"></div>

                                    <!-- Icon & Type -->
                                    <div class="flex items-center gap-4 shrink-0">
                                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-black/[0.02]">
                                            @if ($log['type'] === 'VERIFICATION')
                                                <iconify-icon icon="lucide:search" class="text-primary-acorn"></iconify-icon>
                                            @elseif (str_contains($log['action'], 'Cetak') || str_contains($log['action'], 'Print'))
                                                <iconify-icon icon="lucide:file-text" class="text-amber-500"></iconify-icon>
                                            @elseif (str_contains($log['action'], 'Lapor') || str_contains($log['action'], 'Report'))
                                                <iconify-icon icon="lucide:check-circle" class="text-emerald-500"></iconify-icon>
                                            @elseif (str_contains($log['action'], 'Warga') || str_contains($log['action'], 'Citizen') || str_contains($log['action'], 'CITIZEN') || str_contains($log['action'], 'Data'))
                                                <iconify-icon icon="lucide:users" class="text-blue-500"></iconify-icon>
                                            @elseif (str_contains($log['action'], 'User') || str_contains($log['action'], 'USER') || str_contains($log['action'], 'Pengguna'))
                                                <iconify-icon icon="lucide:key-round" class="text-purple-500"></iconify-icon>
                                            @elseif (str_contains($log['action'], 'Masuk') || str_contains($log['action'], 'LOGIN'))
                                                <iconify-icon icon="lucide:log-in" class="text-green-500"></iconify-icon>
                                            @elseif (str_contains($log['action'], 'Keluar') || str_contains($log['action'], 'LOGOUT'))
                                                <iconify-icon icon="lucide:log-out" class="text-rose-500"></iconify-icon>
                                            @else
                                                <iconify-icon icon="lucide:activity" class="text-slate-400"></iconify-icon>
                                            @endif
                                        </div>
                                        <div class="sm:hidden">
                                            <x-badge :variant="$log['type'] === 'VERIFICATION' ? 'success' : 'slate'" :label="$log['type']" />
                                        </div>
                                    </div>

                                    <!-- Details -->
                                    <div class="flex-grow min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $log['user'] }}</span>
                                            <span class="hidden sm:inline w-1 h-1 rounded-full bg-slate-200"></span>
                                            <span class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $log['action'] }}</span>
                                            <div class="hidden sm:block">
                                                <x-badge :variant="$log['type'] === 'VERIFICATION' ? 'success' : 'slate'" :label="$log['type']" />
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-x-3 gap-y-1">
                                            @if ($log['target'] !== '-')
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[11px] font-black text-primary-acorn uppercase tracking-widest">
                                                        {{ strlen($log['target']) === 16 ? substr($log['target'], 0, 6) . '********' . substr($log['target'], -2) : $log['target'] }}
                                                    </span>
                                                    @if(isset($log['target_name']) && $log['target_name'])
                                                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-tight">- {{ $log['target_name'] }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            @if ($log['result'])
                                                <x-badge 
                                                    :variant="$log['result'] === 'ACTIVE' ? 'success' : ($log['result'] === 'EXPIRED' ? 'danger' : 'warning')" 
                                                    :label="$log['result']" 
                                                />
                                            @endif
                                            
                                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-tight truncate">
                                                {{ $log['details'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Time & Action -->
                                    <div class="flex items-center sm:flex-col sm:items-end gap-3 shrink-0 ml-auto sm:ml-0 border-t sm:border-t-0 pt-3 sm:pt-0 border-black/5">
                                        <span class="text-[10px] font-black text-slate-300 tabular-nums">
                                            {{ \Carbon\Carbon::parse($log['timestamp'])->format('H:i') }}
                                        </span>
                                        @if ($log['type'] === 'VERIFICATION' && strlen($log['target']) === 16)
                                            @can('service.print_proof')
                                                <a href="{{ route('verification.proof', ['nik' => $log['target']]) }}" target="_blank"
                                                    class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-400 rounded-xl hover:bg-primary-acorn hover:text-white transition shadow-sm border border-black/[0.03]">
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
                    <x-card padding="p-20" class="text-center">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-800 border border-black/5 mb-4">
                            <iconify-icon icon="lucide:clipboard-list" class="text-2xl text-slate-300"></iconify-icon>
                        </div>
                        <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Belum ada aktivitas</h3>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-widest">
                            {{ $search ? 'Tidak ada data yang sesuai dengan kata kunci pencarian.' : 'Seluruh aktivitas sistem akan tercatat secara otomatis di sini.' }}
                        </p>
                    </x-card>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if ($paginator->hasPages())
            <x-card padding="p-4" class="bg-slate-50/50 dark:bg-slate-800/30">
                <x-pagination :records="$paginator" />
            </x-card>
        @endif
    </div>
@endsection

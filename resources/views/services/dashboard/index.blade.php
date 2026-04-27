@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    Welcome, <span class="text-primary-acorn">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                    Ringkasan performa verifikasi dokumen dan aktivitas pelayanan sistem.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="px-4 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl shadow-sm flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">System Operational</span>
                </div>
            </div>
        </div>

        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-2 lg:gap-4 xl:gap-6">
            <!-- Total Verifications -->
            <div
                class="premium-card p-5 xl:p-6 flex items-center gap-5 group hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-xl bg-primary-acorn/10 flex items-center justify-center text-primary-acorn shrink-0">
                    <iconify-icon icon="lucide:database" class="text-2xl"></iconify-icon>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Total Data</p>
                    <p class="text-xl font-black text-slate-800 dark:text-white mt-0.5 tabular-nums truncate">
                        {{ number_format($stats['total_verifications']) }}</p>
                </div>
            </div>

            <!-- Valid Status -->
            <div
                class="premium-card p-5 xl:p-6 flex items-center gap-5 group hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                    <iconify-icon icon="lucide:check-circle-2" class="text-2xl"></iconify-icon>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Status Valid</p>
                    <p class="text-xl font-black text-emerald-500 mt-0.5 tabular-nums truncate">
                        {{ number_format($stats['status_distribution']['active']) }}</p>
                </div>
            </div>

            <!-- Expired Status -->
            <div
                class="premium-card p-5 xl:p-6 flex items-center gap-5 group hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                    <iconify-icon icon="lucide:clock" class="text-2xl"></iconify-icon>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Kadaluarsa</p>
                    <p class="text-xl font-black text-amber-500 mt-0.5 tabular-nums truncate">
                        {{ number_format($stats['status_distribution']['expired']) }}</p>
                </div>
            </div>

            <!-- Pending/Unmapped -->
            <div
                class="premium-card p-5 xl:p-6 flex items-center gap-5 group hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-500 shrink-0">
                    <iconify-icon icon="lucide:alert-triangle" class="text-2xl"></iconify-icon>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Unmapped</p>
                    <p class="text-xl font-black text-rose-500 mt-0.5 tabular-nums truncate">
                        {{ number_format($stats['status_distribution']['pending']) }}</p>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Village Performance -->
            <div class="xl:col-span-2 space-y-6">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Performa Wilayah
                    </h3>
                    <a href="{{ route('citizens.index') }}"
                        class="text-[10px] font-black text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">Lihat
                        Semua Registry</a>
                </div>

                <div class="premium-card overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-black/[0.03] dark:border-white/[0.03]">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama
                                    Desa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kode
                                </th>
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                    Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.03] dark:divide-white/[0.03]">
                            @foreach ($stats['village_breakdown'] as $village)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary-acorn transition uppercase tracking-tight">{{ $village->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $village->code }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-xs font-black text-slate-900 dark:text-white tabular-nums">{{ number_format($village->verifications_count) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions & Feed -->
            <div class="space-y-8">
                <!-- Data Command Card -->
                <div class="bg-slate-900 rounded-2xl p-8 text-white relative overflow-hidden group shadow-2xl">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary-acorn/20 rounded-full blur-3xl"></div>

                    <div class="relative z-10 space-y-6">
                        <div>
                            <h3 class="text-lg font-black tracking-tight leading-none uppercase">Data Export</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3">Pelayanan Dokumen
                                Engine</p>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('reports.citizens') }}"
                                class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition duration-300 group/btn">
                                <span class="text-[10px] font-black uppercase tracking-widest">Download Registry</span>
                                <iconify-icon icon="lucide:arrow-right"
                                    class="text-xl text-primary-acorn group-hover/btn:translate-x-1 transition"></iconify-icon>
                            </a>
                            <a href="{{ route('reports.audit') }}"
                                class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition duration-300 group/btn">
                                <span class="text-[10px] font-black uppercase tracking-widest">Audit Timeline</span>
                                <iconify-icon icon="lucide:arrow-right"
                                    class="text-xl text-primary-acorn group-hover/btn:translate-x-1 transition"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider px-1">Aktivitas
                        Terkini</h3>
                    <div class="space-y-4">
                        @foreach ($stats['recent_requests'] as $req)
                            <div class="premium-card p-4 flex items-center gap-4 group">
                                <div
                                    class="h-10 w-10 rounded-lg bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-[10px] font-black text-slate-400 group-hover:text-primary-acorn transition">
                                    {{ strtoupper(substr($req->service_type, 0, 2)) }}
                                </div>
                                <div class="flex-grow">
                                    <p
                                        class="text-[11px] font-black text-slate-700 dark:text-slate-200 truncate uppercase tracking-tight">
                                        {{ $req->citizen->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        {{ $req->service_type }}</p>
                                </div>
                                <span
                                    class="text-[8px] font-black text-slate-300 uppercase whitespace-nowrap">{{ $req->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

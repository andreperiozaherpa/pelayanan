@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-10 lg:space-y-14">
        <!-- Sophisticated Header -->
        <div
            class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-6 border-b border-slate-200 dark:border-white/[0.03]">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span
                        class="px-3 py-1 bg-indigo-500/10 text-indigo-500 dark:text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-indigo-500/20">
                        Admin</span>
                </div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-none">
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-emerald-500">Dashboard</span>
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <div
                    class="px-6 py-4 bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-xl dark:shadow-2xl flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">System Frequency</p>
                        <p class="text-xs font-black text-slate-900 dark:text-white tabular-nums">
                            {{ now()->format('d M, H:i') }} WIB</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-500">
                        <svg class="w-6 h-6 animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Luxury Metrics Dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Total Verifications -->
            <div class="relative group">
                <div
                    class="absolute inset-0 bg-indigo-500/5 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-700">
                </div>
                <div
                    class="relative glass p-8 rounded-[2.5rem] hover:scale-[1.02] transition-all duration-500 overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div
                            class="p-4 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 text-indigo-500 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.041" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-black text-indigo-500/40 uppercase tracking-widest">Aggregated</span>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1">Total Verifikasi</p>
                    <p class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter tabular-nums">
                        {{ number_format($stats['total_verifications']) }}</p>
                </div>
            </div>

            <!-- Active Status -->
            <div class="relative group">
                <div
                    class="absolute inset-0 bg-emerald-500/5 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-700">
                </div>
                <div
                    class="relative glass p-8 rounded-[2.5rem] hover:scale-[1.02] transition-all duration-500 overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div class="p-4 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 text-emerald-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-black text-emerald-500/40 uppercase tracking-widest">Active
                            State</span>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1">Status Valid</p>
                    <p class="text-4xl font-black text-emerald-500 tracking-tighter tabular-nums">
                        {{ number_format($stats['status_distribution']['active']) }}</p>
                </div>
            </div>

            <!-- Expired Status -->
            <div class="relative group">
                <div
                    class="absolute inset-0 bg-amber-500/5 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-700">
                </div>
                <div
                    class="relative glass p-8 rounded-[2.5rem] hover:scale-[1.02] transition-all duration-500 overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div class="p-4 bg-amber-500/10 rounded-2xl border border-amber-500/20 text-amber-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest">Outdated</span>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1">Records Kadaluarsa</p>
                    <p class="text-4xl font-black text-amber-500 tracking-tighter tabular-nums">
                        {{ number_format($stats['status_distribution']['expired']) }}</p>
                </div>
            </div>

            <!-- Pending / Not Found -->
            <div class="relative group">
                <div
                    class="absolute inset-0 bg-rose-500/5 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-700">
                </div>
                <div
                    class="relative glass p-8 rounded-[2.5rem] hover:scale-[1.02] transition-all duration-500 overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div class="p-4 bg-rose-500/10 rounded-2xl border border-rose-500/20 text-rose-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-black text-rose-500/40 uppercase tracking-widest">Unmapped</span>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1">Belum Terdata</p>
                    <p class="text-4xl font-black text-rose-500 tracking-tighter tabular-nums">
                        {{ number_format($stats['status_distribution']['pending']) }}</p>
                </div>
            </div>
        </div>

        <!-- Global Analytics & Export -->
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-10 lg:gap-14 items-start">
            <!-- Village Performance Ranking -->
            <div class="xl:col-span-3">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-10 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic">Domain
                            Performance</h3>
                    </div>
                    <span
                        class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-[0.2em] px-5 py-2 bg-indigo-500/10 rounded-full border border-indigo-500/20">County
                        Overview</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    @foreach ($stats['village_breakdown'] as $village)
                        <div
                            class="glass p-8 rounded-[2.5rem] flex items-center justify-between group hover:border-indigo-500/30 transition-all duration-500 relative overflow-hidden">
                            <div
                                class="absolute -left-6 -bottom-6 w-24 h-24 bg-indigo-500/[0.03] rounded-full group-hover:scale-150 transition duration-1000">
                            </div>
                            <div class="flex flex-col relative z-10">
                                <span
                                    class="text-lg font-black text-slate-900 dark:text-white group-hover:text-indigo-500 transition duration-300">{{ $village->name }}</span>
                                <span
                                    class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase mt-2 tracking-widest italic">Region
                                    ID: {{ $village->code }}</span>
                            </div>
                            <div class="text-right relative z-10">
                                <span
                                    class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ number_format($village->verifications_count) }}</span>
                                <div class="flex items-center gap-2 mt-2 justify-end">
                                    <span
                                        class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic">Validations</span>
                                </div>
                                <div
                                    class="h-2 w-32 bg-slate-200 dark:bg-slate-800 rounded-full mt-4 overflow-hidden shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-full transition-all duration-1000"
                                        style="width: {{ min(100, ($village->verifications_count / max(1, $stats['total_verifications'])) * 300) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Enhanced Control Panel -->
            <div class="xl:col-span-2 space-y-10 lg:space-y-14">
                <!-- Premium Export Card -->
                <div
                    class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-900 rounded-[3rem] p-12 text-white shadow-3xl relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10">
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-6 mb-10">
                            <div
                                class="w-16 h-16 rounded-[2rem] bg-white/10 backdrop-blur-2xl border border-white/20 flex items-center justify-center shadow-2xl group-hover:rotate-6 transition duration-500">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black tracking-tight leading-none italic">Data Nexus</h3>
                                <p class="text-[10px] text-indigo-200 font-bold uppercase tracking-[0.3em] mt-2">Export
                                    Command Center</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <a href="{{ route('reports.citizens') }}"
                                class="w-full flex items-center justify-between p-6 bg-white/10 hover:bg-white/20 border border-white/20 rounded-[2.5rem] transition duration-500 group/btn shadow-xl backdrop-blur-xl">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-emerald-400/20 flex items-center justify-center text-emerald-300">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="font-black text-sm uppercase tracking-widest italic">Registry
                                        Artifact</span>
                                </div>
                                <svg class="w-6 h-6 text-white/60 group-hover/btn:translate-x-2 transition duration-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>

                            <a href="{{ route('reports.audit') }}"
                                class="w-full flex items-center justify-between p-6 bg-black/20 hover:bg-black/30 border border-white/5 rounded-[2.5rem] transition duration-500 group/btn backdrop-blur-xl">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-400/10 flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-4.514A9.01 9.01 0 0012 15c1.289 0 2.499.27 3.591.758m2.423-2.09c.853-1.42 1.353-3.041 1.353-4.773 0-5.523-3.483-10-7.778-10S3.778 4.477 3.778 10c0 1.732.5 3.353 1.353 4.773" />
                                        </svg>
                                    </div>
                                    <span class="font-black text-white/90 text-sm uppercase tracking-widest italic">Audit
                                        Spectrum</span>
                                </div>
                                <svg class="w-6 h-6 text-white/40 group-hover/btn:translate-x-2 transition duration-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Global Activity Feed -->
                <div class="glass rounded-[3rem] p-10 lg:p-12 shadow-2xl relative overflow-hidden">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight italic">Temporal Log
                        </h3>
                        <span
                            class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Real-time
                            Stream</span>
                    </div>
                    <div class="space-y-8">
                        @foreach ($stats['recent_requests'] as $req)
                            <div class="flex items-center gap-6 group">
                                <div class="relative">
                                    <div
                                        class="h-14 w-14 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/5 flex items-center justify-center text-[10px] font-black text-slate-500 group-hover:text-emerald-400 group-hover:border-emerald-500/20 transition-all duration-300">
                                        {{ $req->citizen->village->code ?? '?' }}
                                    </div>
                                    @if ($loop->first)
                                        <span
                                            class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-4 border-white dark:border-slate-900 animate-ping"></span>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <p
                                        class="text-sm font-black text-slate-900 dark:text-white group-hover:text-indigo-500 transition duration-300">
                                        {{ $req->service_type }}</p>
                                    <p
                                        class="text-[9px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest mt-1 italic">
                                        {{ $req->citizen->village->name ?? 'System' }}</p>
                                </div>
                                <span
                                    class="text-[10px] font-bold tabular-nums text-slate-400 dark:text-slate-600">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

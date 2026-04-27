@extends('layouts.app')

@section('title', 'Dashboard Desa')

@section('content')
    <div class="max-w-7xl mx-auto space-y-10 lg:space-y-14">
        <!-- Sophisticated Header -->
        <div
            class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-6 border-b border-slate-200 dark:border-slate-800/50">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span
                        class="px-3 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-emerald-500/20">Halaman</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Region</span>
                </div>
                <h1 class="text-4xl font-black text-cslate-900 dark:text-white tracking-tight leading-none italic">
                    Dashboard <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-indigo-500">Desa</span>
                </h1>
                <p class="text-xs text-slate-500 font-medium max-w-lg">Local administration panel for resident data
                    verification and poverty record management.</p>
            </div>

            <div
                class="px-6 py-4 bg-white/60 dark:bg-slate-800/40 backdrop-blur-xl rounded-[2rem] border border-white/50 dark:border-slate-700/50 shadow-xl flex items-center gap-6">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Operator
                        Profile</p>
                    <p class="text-xs font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                </div>
                <div
                    class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Total Verifications -->
            <div class="relative group h-full">
                <div
                    class="absolute inset-0 bg-indigo-500/10 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none">
                </div>
                <div
                    class="relative h-full bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-600 dark:to-indigo-800 rounded-[2.5rem] p-8 lg:p-10 border border-indigo-200 dark:border-white/5 shadow-xl overflow-hidden group-hover:scale-[1.02] transition duration-500">
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5 dark:opacity-10 pointer-events-none">
                    </div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <p
                                class="text-indigo-600/70 dark:text-indigo-100/60 text-[10px] font-black uppercase tracking-[0.3em] mb-6">
                                Total Verifikasi</p>
                            <div class="flex items-baseline gap-3">
                                <h3 class="text-5xl lg:text-6xl font-black tabular-nums text-indigo-900 dark:text-white">
                                    {{ number_format($stats['total_verifications']) }}</h3>
                                <span
                                    class="text-xs font-bold text-indigo-600 dark:text-indigo-200 uppercase tracking-widest">Records</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="absolute -right-8 -bottom-8 text-[12rem] opacity-5 group-hover:rotate-12 transition duration-700 pointer-events-none">
                        📊</div>
                </div>
            </div>

            <!-- Today's Service -->
            <div class="relative group h-full">
                <div
                    class="absolute inset-0 bg-emerald-500/10 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none">
                </div>
                <div
                    class="relative h-full bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-600 dark:to-emerald-800 rounded-[2.5rem] p-8 lg:p-10 border border-emerald-200 dark:border-white/5 shadow-xl overflow-hidden group-hover:scale-[1.02] transition duration-500">
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5 dark:opacity-10 pointer-events-none">
                    </div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <p
                                class="text-emerald-700/60 dark:text-emerald-100/60 text-[10px] font-black uppercase tracking-[0.3em] mb-6">
                                Layanan Hari Ini</p>
                            <div class="flex items-baseline gap-3">
                                <h3 class="text-5xl lg:text-6xl font-black tabular-nums text-emerald-900 dark:text-white">
                                    {{ number_format($stats['today_count']) }}</h3>
                                <span
                                    class="text-xs font-bold text-emerald-600 dark:text-emerald-200 uppercase tracking-widest">Entry</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="absolute -right-8 -bottom-8 text-[12rem] opacity-5 group-hover:rotate-12 transition duration-700 pointer-events-none">
                        ✨</div>
                </div>
            </div>

            <!-- Verification Anchor -->
            <a href="{{ route('dashboard.verify') }}"
                class="group relative flex flex-col items-center justify-center text-center p-8 lg:p-10 bg-white/40 dark:bg-white/[0.02] border-2 border-dashed border-slate-300 dark:border-white/10 rounded-[2.5rem] hover:bg-slate-50 dark:hover:bg-white/[0.05] hover:border-indigo-500/50 backdrop-blur-xl shadow-xl transition-all duration-500 overflow-hidden h-full">
                <div class="relative z-10">
                    <div
                        class="w-24 h-24 mx-auto bg-indigo-500/10 rounded-[2rem] flex items-center justify-center text-4xl mb-6 group-hover:scale-110 group-hover:bg-indigo-500/20 transition-all duration-500 shadow-xl border border-indigo-500/20">
                        🛰️
                    </div>
                    <h4 class="text-slate-900 dark:text-white font-black text-xl uppercase tracking-widest">Mulai Verifikasi
                    </h4>
                    <p class="text-slate-500 mt-2 font-medium">Validasi NIK & Lapor Layanan</p>
                </div>
                <div
                    class="absolute inset-0 bg-indigo-600/5 opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none">
                </div>
            </a>
        </div>

        <!-- Main Content Split -->
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-8 lg:gap-12 pb-12">
            <!-- Recent Activity -->
            <div
                class="xl:col-span-3 bg-white/60 dark:bg-slate-900/60 rounded-[3rem] border border-white/50 dark:border-white/5 p-8 lg:p-10 backdrop-blur-3xl shadow-2xl relative overflow-hidden">
                <div class="flex items-center justify-between mb-10 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Recent Intelligence
                        </h3>
                    </div>
                    <a href="{{ route('dashboard.history') }}"
                        class="px-5 py-2 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-indigo-500/20 hover:bg-indigo-500/20 transition duration-300">View
                        History →</a>
                </div>

                <div class="space-y-4 lg:space-y-6 relative z-10">
                    @forelse($stats['recent_requests'] as $req)
                        <div
                            class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 p-4 sm:p-6 bg-slate-50 dark:bg-slate-800/40 rounded-[2rem] border border-slate-200 dark:border-white/5 hover:border-emerald-500/30 transition-all duration-500 group shadow-sm">
                            <div
                                class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition duration-500 shadow-inner border border-emerald-500/10 shrink-0">
                                📄
                            </div>
                            <div class="flex-grow">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-4 mb-2">
                                    <h4
                                        class="text-slate-900 dark:text-white font-black text-sm uppercase tracking-widest group-hover:text-emerald-500 transition line-clamp-1">
                                        {{ $req->service_type }}</h4>
                                    <span
                                        class="text-[10px] font-black tabular-nums text-slate-400 shrink-0">{{ $req->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 mt-1">
                                    <p class="text-slate-500 text-[10px] font-black tracking-widest uppercase">NIK Ref:
                                        <span class="text-slate-700 dark:text-slate-300">{{ $req->citizen_nik }}</span>
                                    </p>
                                    <span
                                        class="hidden sm:inline w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                    <span
                                        class="px-3 py-1 bg-slate-200 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-[9px] font-black uppercase rounded-md border border-slate-300 dark:border-white/5">{{ $req->status }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-16 lg:py-20 bg-slate-50 dark:bg-slate-800/20 rounded-[2rem] border border-dashed border-slate-300 dark:border-white/5">
                            <div class="text-5xl mb-6 opacity-40 dark:opacity-20 grayscale">📭</div>
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Belum ada aktivitas
                                laporan baru.</p>
                        </div>
                    @endforelse
                </div>
                <div
                    class="absolute -right-20 -bottom-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-[80px] pointer-events-none">
                </div>
            </div>

            <!-- Supplemental Info -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Guidelines Card -->
                <div class="relative group">
                    <div
                        class="absolute inset-0 bg-amber-500/10 rounded-[3rem] blur-2xl group-hover:bg-amber-500/20 transition duration-500 pointer-events-none">
                    </div>
                    <div
                        class="relative bg-gradient-to-br from-amber-50 to-orange-100 dark:from-amber-600 dark:to-orange-800 rounded-[3rem] p-8 lg:p-10 border border-amber-200 dark:border-white/5 shadow-2xl overflow-hidden group">
                        <div
                            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5 dark:opacity-10 pointer-events-none">
                        </div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-amber-500/20 dark:bg-white/10 backdrop-blur-xl border border-amber-500/30 dark:border-white/20 flex items-center justify-center text-xl">
                                    🔔
                                </div>
                                <h3 class="text-2xl font-black text-amber-950 dark:text-white tracking-tight leading-none">
                                    Operational Directives</h3>
                            </div>
                            <ul class="space-y-6">
                                @foreach (['Validasi NIK wajib dilakukan via sistem.', 'Pastikan subjek memenuhi kriteria kemiskinan.', 'Entry data harus akurat dan dapat dipertanggungjawabkan.'] as $directive)
                                    <li class="flex gap-4 group/item">
                                        <span
                                            class="flex-shrink-0 w-6 h-6 bg-amber-500/20 dark:bg-white/20 rounded-full flex items-center justify-center text-[10px] text-amber-800 dark:text-white group-hover/item:scale-110 transition">✓</span>
                                        <span
                                            class="text-sm font-bold text-amber-900 dark:text-amber-50 leading-relaxed">{{ $directive }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Support Panel -->
                <div
                    class="bg-white/60 dark:bg-white/[0.02] border border-white/50 dark:border-white/5 rounded-[3rem] p-8 lg:p-10 backdrop-blur-3xl shadow-2xl relative overflow-hidden">
                    <div
                        class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-[50px] pointer-events-none">
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-4 tracking-tight relative z-10">
                        Technical Support Interface</h3>
                    <p class="text-slate-500 text-sm mb-8 leading-relaxed font-medium relative z-10">Jika menemukan
                        diskrepansi data atau anomali sistem, silakan hubungi Administration Center.</p>
                    <div
                        class="p-6 bg-white dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:border-indigo-500/30 transition duration-500 relative z-10 shadow-sm">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Status Tim
                                IT</span>
                            <span class="text-xs font-bold text-emerald-500 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Online / Ready
                            </span>
                        </div>
                        <button
                            class="px-6 py-3 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white text-xs font-black uppercase tracking-widest rounded-xl transition duration-300 w-full sm:w-auto">Contact</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

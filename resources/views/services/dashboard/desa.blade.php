@extends('layouts.app')

@section('title', 'Dashboard Desa')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-widest rounded-md border border-primary-acorn/20">Desa</span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ Auth::user()->village->name ?? 'Wilayah Lokal' }}</span>
            </div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                Panel Verifikasi Desa
            </h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                Administrasi lokal untuk verifikasi data penduduk dan pengelolaan catatan kemiskinan.
            </p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="premium-card px-5 py-3 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Operator</p>
                    <p class="text-[11px] font-black text-slate-700 dark:text-white uppercase">{{ Auth::user()->name }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-black/[0.03] dark:border-white/[0.03]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Verifications -->
        <div class="premium-card p-8 group hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Verifikasi</p>
                <div class="w-10 h-10 rounded-xl bg-primary-acorn/10 flex items-center justify-center text-primary-acorn">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black tabular-nums text-slate-800 dark:text-white">{{ number_format($stats['total_verifications']) }}</h3>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Data</span>
            </div>
        </div>

        <!-- Today's Service -->
        <div class="premium-card p-8 group hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Layanan Hari Ini</p>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black tabular-nums text-emerald-500">{{ number_format($stats['today_count']) }}</h3>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Entry</span>
            </div>
        </div>

        <!-- Verification Action -->
        <a href="{{ route('verification.index') }}" class="premium-card p-8 flex flex-col items-center justify-center text-center group border-dashed border-2 border-primary-acorn/20 bg-slate-50/30 hover:bg-slate-50 transition duration-300">
            <div class="w-14 h-14 rounded-2xl bg-primary-acorn flex items-center justify-center text-white shadow-lg shadow-primary-acorn/30 group-hover:scale-110 transition duration-500 mb-4">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h4 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Mulai Verifikasi Baru</h4>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Validasi NIK & Lapor Layanan</p>
        </a>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-8">
        <!-- Recent Intelligence -->
        <div class="xl:col-span-3 space-y-6">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Aktivitas Terkini</h3>
                <a href="{{ route('dashboard.history') }}" class="text-[10px] font-black text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">History →</a>
            </div>

            <div class="space-y-4">
                @forelse($stats['recent_requests'] as $req)
                    <div class="premium-card p-5 flex items-center gap-6 group">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-primary-acorn transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <h4 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-tight truncate">{{ $req->service_type }}</h4>
                                <span class="text-[9px] font-black tabular-nums text-slate-300 uppercase shrink-0">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">NIK: <span class="text-slate-600 dark:text-slate-300">{{ $req->citizen_nik }}</span></span>
                                <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-[8px] font-black text-slate-500 dark:text-slate-400 uppercase rounded border border-black/[0.03]">{{ $req->status }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="premium-card p-12 text-center border-dashed border-2 border-slate-200 dark:border-white/5">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada aktivitas laporan baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Guidelines -->
            <div class="premium-card p-8 bg-slate-900 text-white relative overflow-hidden group shadow-2xl">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary-acorn/20 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-primary-acorn">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest">Panduan Operasional</h3>
                    </div>
                    <ul class="space-y-6">
                        @foreach (['Validasi NIK wajib dilakukan via sistem.', 'Pastikan subjek memenuhi kriteria kemiskinan.', 'Entry data harus akurat dan nyata.'] as $directive)
                            <li class="flex gap-4 group/item">
                                <span class="flex-shrink-0 w-5 h-5 bg-white/10 rounded-full flex items-center justify-center text-[9px] text-primary-acorn font-black group-hover/item:scale-110 transition">✓</span>
                                <span class="text-xs font-bold text-slate-300 leading-relaxed">{{ $directive }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Support -->
            <div class="premium-card p-8 relative overflow-hidden">
                <div class="absolute -left-10 -top-10 w-40 h-40 bg-primary-acorn/5 rounded-full blur-[50px] pointer-events-none"></div>
                <h3 class="text-sm font-black text-slate-800 dark:text-white mb-4 uppercase tracking-widest relative z-10">Support Teknis</h3>
                <p class="text-xs text-slate-500 mb-8 leading-relaxed font-medium relative z-10">Jika menemukan diskrepansi data atau anomali sistem, silakan hubungi Administration Center.</p>
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-black/[0.03] dark:border-white/[0.03] relative z-10 group">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status Tim IT</p>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Online / Ready</span>
                        </div>
                    </div>
                    <button class="px-5 py-2 bg-white dark:bg-slate-800 hover:bg-primary-acorn hover:text-white text-slate-700 dark:text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition duration-300 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">Contact</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

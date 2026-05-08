@extends('layouts.app')

@section('title', 'Dashboard Desa')

@section('content')
<div class="space-y-8 pb-20">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-[0.2em] rounded-full border border-primary-acorn/20 shadow-sm">Wilayah Desa</span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ Auth::user()->village->name ?? 'Wilayah Lokal' }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase leading-none">
                Panel Kontrol Desa
            </h1>
            <p class="text-[11px] text-slate-500 font-bold tracking-tight mt-2 uppercase opacity-60">
                Administrasi lokal & Pengelolaan Data Kemiskinan Terpadu
            </p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="premium-card px-6 py-4 flex items-center gap-5 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl">
                <div class="text-right">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Otoritas Operator</p>
                    <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ Auth::user()->name }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 flex items-center justify-center text-slate-400 border border-black/[0.05] dark:border-white/[0.05] shadow-inner">
                    <iconify-icon icon="lucide:user-cog" class="text-xl"></iconify-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Total Verifications -->
        <div class="premium-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-acorn/5 rounded-full blur-2xl group-hover:bg-primary-acorn/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-8 relative z-10">
                <div class="p-3.5 rounded-2xl bg-primary-acorn/10 text-primary-acorn shadow-inner">
                    <iconify-icon icon="lucide:database" class="text-2xl"></iconify-icon>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Verifikasi</p>
            </div>
            <div class="flex items-baseline gap-2 relative z-10">
                <h3 class="text-5xl font-black tabular-nums text-slate-800 dark:text-white tracking-tighter">{{ number_format($stats['total_verifications']) }}</h3>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Arsip</span>
            </div>
        </div>

        <!-- Today's Service -->
        <div class="premium-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden border-emerald-500/10">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-8 relative z-10">
                <div class="p-3.5 rounded-2xl bg-emerald-500/10 text-emerald-500 shadow-inner">
                    <iconify-icon icon="lucide:zap" class="text-2xl"></iconify-icon>
                </div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Layanan Hari Ini</p>
            </div>
            <div class="flex items-baseline gap-2 relative z-10">
                <h3 class="text-5xl font-black tabular-nums text-emerald-500 tracking-tighter">{{ number_format($stats['today_count']) }}</h3>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Entry</span>
            </div>
        </div>

        <!-- Verification Action -->
        <a href="{{ route('verification.index') }}" class="premium-card p-8 flex flex-col items-center justify-center text-center group border-dashed border-2 border-primary-acorn/20 bg-slate-50/30 dark:bg-slate-900/10 hover:bg-white dark:hover:bg-slate-900 transition-all duration-500 shadow-xl shadow-primary-acorn/5">
            <div class="w-16 h-16 rounded-2xl bg-primary-acorn flex items-center justify-center text-white shadow-2xl shadow-primary-acorn/40 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 mb-5">
                <iconify-icon icon="lucide:fingerprint" class="text-3xl"></iconify-icon>
            </div>
            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Verifikasi Baru</h4>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-2 opacity-60">Validasi NIK & Lapor Layanan</p>
        </a>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-10">
        <!-- Recent Activity -->
        <div class="xl:col-span-3 space-y-8">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 bg-primary-acorn rounded-full"></div>
                    <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Log Aktivitas Terkini</h3>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('service.requests.index') }}" class="group flex items-center gap-2 text-[9px] font-black text-emerald-600 uppercase tracking-[0.2em] hover:gap-3 transition-all">
                        Antrean Permohonan
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/10 text-[8px]">{{ $stats['recent_requests']->count() }}</span>
                        <iconify-icon icon="lucide:arrow-right" class="text-sm"></iconify-icon>
                    </a>
                    <a href="{{ route('dashboard.history') }}" class="group flex items-center gap-2 text-[9px] font-black text-primary-acorn uppercase tracking-[0.2em] hover:gap-3 transition-all">
                        History Lengkap
                        <iconify-icon icon="lucide:arrow-right" class="text-sm"></iconify-icon>
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($stats['recent_requests'] as $req)
                    <div class="premium-card p-6 flex items-center gap-6 group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all border-transparent hover:border-black/[0.03] dark:hover:border-white/[0.03]">
                        <div class="w-14 h-14 bg-slate-50 dark:bg-slate-900 rounded-[1.25rem] flex items-center justify-center text-slate-400 group-hover:text-primary-acorn group-hover:bg-white dark:group-hover:bg-slate-800 transition-all shadow-sm group-hover:shadow-md border border-black/[0.02] dark:border-white/[0.02]">
                            <iconify-icon icon="lucide:file-text" class="text-2xl"></iconify-icon>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <h4 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider truncate">{{ $req->service_type }}</h4>
                                <span class="text-[9px] font-bold tabular-nums text-slate-400 uppercase tracking-tighter shrink-0">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 dark:bg-slate-800/80 rounded-lg border border-black/[0.03] dark:border-white/[0.03]">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">NIK</span>
                                    <span class="text-[9px] font-black text-slate-600 dark:text-slate-300 tracking-wider">{{ $req->citizen_nik }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span class="px-3 py-1 bg-emerald-500/10 text-[8px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest rounded-full border border-emerald-500/10">{{ $req->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="premium-card p-16 text-center border-dashed border-2 border-slate-100 dark:border-white/5 opacity-60">
                        <iconify-icon icon="lucide:inbox" class="text-4xl text-slate-300 mb-4"></iconify-icon>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Belum ada catatan aktivitas terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Actions & Intelligence -->
        <div class="xl:col-span-2 space-y-10">
            <!-- Guidelines -->
            <div class="premium-card p-10 bg-slate-900 text-white relative overflow-hidden group shadow-2xl border-none">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary-acorn/20 rounded-full blur-[60px] group-hover:bg-primary-acorn/30 transition-colors"></div>
                <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-[60px]"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-primary-acorn border border-white/10 shadow-xl">
                            <iconify-icon icon="lucide:shield-check" class="text-2xl"></iconify-icon>
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-[0.2em]">Protokol Validasi</h3>
                            <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-1">Standar Operasional Desa</p>
                        </div>
                    </div>
                    <ul class="space-y-8">
                        @foreach (['Wajib verifikasi NIK melalui sistem pusat.', 'Subjek harus terdaftar dalam DTKS/P3KE.', 'Entry data hasil verifikasi harus faktual.'] as $directive)
                            <li class="flex gap-5 group/item">
                                <div class="flex-shrink-0 w-6 h-6 bg-primary-acorn/20 rounded-lg flex items-center justify-center group-hover/item:bg-primary-acorn group-hover/item:rotate-12 transition-all duration-300">
                                    <iconify-icon icon="lucide:check" class="text-xs text-primary-acorn group-hover/item:text-white font-black"></iconify-icon>
                                </div>
                                <span class="text-[11px] font-bold text-slate-300 leading-relaxed group-hover/item:text-white transition-colors">{{ $directive }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Technical Intelligence -->
            <div class="premium-card p-10 relative overflow-hidden group border-black/[0.02] dark:border-white/[0.02]">
                <div class="absolute -left-10 -top-10 w-40 h-40 bg-primary-acorn/5 rounded-full blur-[50px] pointer-events-none group-hover:bg-primary-acorn/10 transition-colors"></div>
                
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white mb-6 uppercase tracking-[0.15em] relative z-10">Inteligensi Sistem</h3>
                <p class="text-[11px] text-slate-500 mb-10 leading-relaxed font-bold uppercase opacity-70 relative z-10">Hubungi Administration Center jika mendeteksi anomali sinkronisasi data.</p>
                
                <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-[1.5rem] border border-black/[0.03] dark:border-white/[0.03] relative z-10 group/status transition-all hover:shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none mb-1.5">Network Status</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">Secure Online</span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                            <iconify-icon icon="lucide:globe" class="text-lg"></iconify-icon>
                        </div>
                    </div>
                    <button class="w-full py-4 bg-white dark:bg-slate-800 hover:bg-primary-acorn hover:text-white text-slate-700 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl transition-all duration-300 border border-black/[0.05] dark:border-white/[0.05] shadow-sm hover:shadow-lg hover:shadow-primary-acorn/20">Akses Support</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

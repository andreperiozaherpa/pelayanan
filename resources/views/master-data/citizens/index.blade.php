@extends('layouts.app')

@section('title', 'Master Data Warga')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Master Data Warga</h1>
            <p class="text-slate-500 mt-1 font-medium">Pengelolaan data kependudukan dan status layanan kesejahteraan.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('citizens.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Warga
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-3xl p-4 shadow-xl mb-8">
        <form method="GET" action="{{ route('citizens.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan NIK atau Nama Lengkap..." 
                    class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-medium transition-shadow dark:text-white placeholder-slate-400" />
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-600/20 transition-all hover:shadow-indigo-600/40">
                Pencarian
            </button>
            @if($search)
            <a href="{{ route('citizens.index') }}" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-2xl font-bold transition-all text-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Table Details -->
    <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700/50">
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">NIK / Nama Lengkap</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest hidden md:table-cell">Kontak & Wilayah</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Status SKTM</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                    @forelse($citizens as $citizen)
                        @php
                            $record = $citizen->povertyRecords->first();
                            $status = $record ? $record->status : 'BELUM TERDATA';
                            
                            // Re-evaluate EXPIRED dynamically just in case database hasn't been updated
                            if ($record && $record->status === 'ACTIVE' && \Carbon\Carbon::parse($record->valid_until)->isPast()) {
                                $status = 'EXPIRED';
                            }
                        @endphp
                        <tr class="hover:bg-white/60 dark:hover:bg-slate-800/60 transition-colors group">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="font-black text-slate-900 dark:text-white" title="{{ $citizen->nik }}">{{ substr($citizen->nik, 0, 6) }}********{{ substr($citizen->nik, -2) }}</p>
                                <p class="text-sm font-medium text-slate-500">{{ $citizen->nama_lengkap }}</p>
                            </td>
                            <td class="py-4 px-6 hidden md:table-cell">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $citizen->kontak ?: '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $citizen->village->name ?? 'Unknown' }}</p>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($status === 'ACTIVE')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div> Aktif
                                    </span>
                                @elseif($status === 'EXPIRED')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-amber-500"></div> Kedaluwarsa
                                    </span>
                                @elseif($status === 'PENDING_REVIEW')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-indigo-500"></div> Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                                        <div class="h-1.5 w-1.5 rounded-full bg-slate-500"></div> Belum Terdata
                                    </span>
                                @endif
                                
                                @if($record)
                                    <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider">Masa Berlaku:</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 font-bold">{{ \Carbon\Carbon::parse($record->valid_from)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($record->valid_until)->format('d/m/Y') }}</p>
                                    @if($record->income_range)
                                        <p class="text-[10px] text-slate-500 mt-0.5 truncate max-w-[150px]">Income: {{ $record->income_range }}</p>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('citizens.edit', $citizen->nik) }}" class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl transition border border-slate-200/50 dark:border-white/5" title="Edit Data">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 mb-4">
                                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Tidak Ada Data Warga</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Silangkan tambah data baru menggunakan tombol di pojok kanan atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($citizens->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/30">
                {{ $citizens->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

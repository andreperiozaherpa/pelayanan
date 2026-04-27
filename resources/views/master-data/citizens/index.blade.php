@extends('layouts.app')

@section('title', 'Master Data Warga')

@section('content')
    <div class="space-y-6">
        <!-- Premium Style Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Master Data Warga</h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight">Pengelolaan data kependudukan dan status
                    pelayanan dokumen.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('citizens.create') }}"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                    <iconify-icon icon="lucide:plus" class="text-lg"></iconify-icon>
                    Tambah Warga
                </a>
            </div>
        </div>

        <!-- Premium Style Filters -->
        <div class="premium-card p-4">
            <form method="GET" action="{{ route('citizens.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <iconify-icon icon="lucide:search" class="text-slate-400 text-lg"></iconify-icon>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK atau Nama..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400 uppercase tracking-wider" />
                </div>
                <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    Cari Data
                </button>
                @if ($search)
                    <a href="{{ route('citizens.index') }}"
                        class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 px-6 py-2.5 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Premium Style Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identitas
                                Warga</th>
                            <th
                                class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">
                                Wilayah</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status
                                Pelayanan</th>
                            <th
                                class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                        @forelse($citizens as $citizen)
                            @php
                                $record = $citizen->povertyRecords->first();
                                $status = $record ? $record->status : 'BELUM TERDATA';
                                if (
                                    $record &&
                                    $record->status === 'ACTIVE' &&
                                    \Carbon\Carbon::parse($record->valid_until)->isPast()
                                ) {
                                    $status = 'EXPIRED';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                                <td class="py-4 px-6">
                                    <p
                                        class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                        {{ substr($citizen->nik, 0, 6) }}********{{ substr($citizen->nik, -2) }}</p>
                                    <p
                                        class="text-[11px] font-bold text-slate-500 mt-0.5 group-hover:text-primary-acorn transition uppercase">
                                        {{ $citizen->nama_lengkap }}</p>
                                </td>
                                <td class="py-4 px-6 hidden md:table-cell">
                                    <p
                                        class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ $citizen->village->name ?? '-' }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5 tracking-widest">
                                        {{ $citizen->village->code ?? 'N/A' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($status === 'ACTIVE')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/10 uppercase tracking-widest">
                                            Aktif
                                        </span>
                                    @elseif($status === 'EXPIRED')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/10 uppercase tracking-widest">
                                            Expired
                                        </span>
                                    @elseif($status === 'PENDING_REVIEW')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-primary-acorn/10 text-primary-acorn border border-primary-acorn/10 uppercase tracking-widest">
                                            Pending
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border border-black/5 uppercase tracking-widest">
                                            Unmapped
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('citizens.edit', $citizen->nik) }}"
                                            class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition"
                                            title="Edit Data">
                                            <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <div
                                        class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 border border-black/5 mb-4">
                                        <iconify-icon icon="lucide:user-x" class="text-2xl text-slate-300"></iconify-icon>
                                    </div>
                                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">
                                        Data Tidak Ditemukan</h3>
                                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-widest">Silahkan
                                        tambah data baru atau ubah kata kunci pencarian.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($citizens->hasPages())
                <div
                    class="px-6 py-4 border-t border-black/[0.02] dark:border-white/[0.02] bg-slate-50/50 dark:bg-slate-800/30">
                    {{ $citizens->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

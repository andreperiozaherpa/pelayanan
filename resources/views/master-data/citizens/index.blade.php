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
                <x-button href="{{ route('citizens.create') }}" icon="lucide:plus">
                    Tambah Warga
                </x-button>
            </div>
        </div>

        <x-datatable 
            :records="$citizens" 
            search-placeholder="Cari NIK atau Nama..."
            :search-value="$search"
        >
            <x-slot:thead>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identitas Warga</th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Wilayah</th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Pelayanan</th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
            </x-slot:thead>

            @forelse($citizens as $citizen)
                @php
                    $record = $citizen->povertyRecords->first();
                    $status = $record ? $record->status : 'BELUM TERDATA';
                    if ($record && $record->status === 'ACTIVE' && \Carbon\Carbon::parse($record->valid_until)->isPast()) {
                        $status = 'EXPIRED';
                    }
                @endphp
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                    <td class="py-4 px-6">
                        <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">
                            {{ substr($citizen->nik, 0, 6) }}********{{ substr($citizen->nik, -2) }}</p>
                        <p class="text-[11px] font-bold text-slate-500 mt-0.5 group-hover:text-primary-acorn transition uppercase">
                            {{ $citizen->nama_lengkap }}</p>
                    </td>
                    <td class="py-4 px-6 hidden md:table-cell">
                        <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            {{ $citizen->village->name ?? '-' }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5 tracking-widest">
                            {{ $citizen->village->code ?? 'N/A' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        @if ($status === 'ACTIVE')
                            <x-badge variant="success" label="Aktif" />
                        @elseif($status === 'EXPIRED')
                            <x-badge variant="danger" label="Expired" />
                        @elseif($status === 'PENDING_REVIEW')
                            <x-badge variant="warning" label="Pending" />
                        @else
                            <x-badge variant="slate" label="Unmapped" />
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
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 border border-black/5 mb-4">
                            <iconify-icon icon="lucide:user-x" class="text-2xl text-slate-300"></iconify-icon>
                        </div>
                        <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Data Tidak Ditemukan</h3>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-widest">Silahkan tambah data baru atau ubah kata kunci pencarian.</p>
                    </td>
                </tr>
            @endforelse
        </x-datatable>
    </div>
@endsection

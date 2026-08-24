@extends('layouts.app')

@section('title', 'Daftar Loket MPP')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Daftar Loket</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola daftar loket (counter) yang tersedia di MPP.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('counters.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                <iconify-icon icon="lucide:plus-circle" class="text-lg"></iconify-icon>
                Tambah Loket
            </a>
        </div>
    </div>

    <x-datatable
        :records="$counters"
        search-placeholder="Cari kode, nama, atau deskripsi..."
        :search-value="request('search')"
    >
        <x-slot:thead>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kode</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Loket</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Instansi</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Petugas</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
        </x-slot:thead>

        @forelse($counters as $counter)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                <td class="py-4 px-6">
                    <span class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-wider rounded-lg border border-primary-acorn/20">
                        {{ $counter->code }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <span class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">{{ $counter->name }}</span>
                </td>
                <td class="py-4 px-6">
                    <p class="text-[10px] font-medium text-slate-600 dark:text-slate-300">{{ $counter->gerai?->opd?->name ?? '-' }}</p>
                </td>
                <td class="py-4 px-6 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-acorn/10 text-primary-acorn text-[11px] font-black">{{ $counter->users_count }}</span>
                </td>
                <td class="py-4 px-6">
                    @if($counter->is_active)
                        <x-badge variant="success" label="Aktif" />
                    @else
                        <x-badge variant="danger" label="Nonaktif" />
                    @endif
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <a href="{{ route('counters.edit', $counter) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Edit Loket">
                            <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                        </a>
                        <form method="POST" action="{{ route('counters.destroy', $counter) }}" onsubmit="return confirm('Hapus loket {{ $counter->name }}?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition" title="Hapus Loket">
                                <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="py-12 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <iconify-icon icon="lucide:columns" class="text-3xl text-slate-300"></iconify-icon>
                        <p class="text-sm font-medium text-slate-400">Belum ada loket</p>
                        <a href="{{ route('counters.create') }}" class="text-xs font-bold text-primary-acorn hover:underline">Tambah Loket Baru</a>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-datatable>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Manajemen Zonasi Wilayah')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Zonasi Fungsi Wilayah</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola zonasi tata ruang fungsional (industri, pariwisata, permukiman, dll).</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('map-zones.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                Tambah Zona Fungsi
            </a>
        </div>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Zona</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tipe Kawasan</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Wilayah Administrasi</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Warna Peta</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                    @forelse($zones as $zone)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                            <td class="py-4 px-6">
                                <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider group-hover:text-primary-acorn transition">{{ $zone->name }}</p>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border border-slate-300/30"
                                    style="background-color: {{ $zone->type->color }}15; color: {{ $zone->type->color }}">
                                    {{ $zone->type->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-black/5 uppercase tracking-widest">
                                    {{ $zone->region->name ?? 'Kabupaten' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <span class="w-3.5 h-3.5 rounded border border-white/20 shadow-sm" style="background-color: {{ $zone->type->color }}"></span>
                                    <span class="text-[9px] font-mono text-slate-500 uppercase">{{ $zone->type->color }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider truncate max-w-xs">{{ $zone->description ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('map-zones.edit', $zone->id) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Edit Zona">
                                        <iconify-icon icon="lucide:edit-3" class="text-sm"></iconify-icon>
                                    </a>
                                    <form action="{{ route('map-zones.destroy', $zone->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus zona ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition" title="Delete Zona">
                                            <iconify-icon icon="lucide:trash-2" class="text-sm"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Data Tidak Ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($zones->hasPages())
        <div class="px-6 py-4 border-t border-black/[0.02] dark:border-white/[0.02] bg-slate-50/50 dark:bg-slate-800/30">
            {{ $zones->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

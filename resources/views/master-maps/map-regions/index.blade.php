@extends('layouts.app')

@section('title', 'Manajemen Batas Wilayah')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Batas Wilayah</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola batas wilayah administrasi Kabupaten, Kecamatan, dan Tiyuh/Desa.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('map-regions.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                Tambah Batas Wilayah
            </a>
        </div>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Wilayah</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tingkat</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kode</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Warna Peta</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">GeoJSON</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                    @forelse($regions as $reg)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                            <td class="py-4 px-6">
                                <div>
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider group-hover:text-primary-acorn transition">{{ $reg->name }}</p>
                                    @if($reg->parent)
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Induk: {{ $reg->parent->name }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border
                                    @if($reg->level === 'kabupaten') bg-slate-900/10 text-slate-800 dark:bg-slate-100/10 dark:text-slate-200 border-slate-300/30
                                    @elseif($reg->level === 'kecamatan') bg-blue-500/10 text-blue-500 border-blue-500/10
                                    @else bg-purple-500/10 text-purple-500 border-purple-500/10 @endif">
                                    {{ $reg->level }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-black/5 uppercase tracking-widest">
                                    {{ $reg->code ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($reg->color)
                                    <div class="flex items-center justify-center gap-1.5">
                                        <span class="w-3.5 h-3.5 rounded border border-white/20 shadow-sm" style="background-color: {{ $reg->color }}"></span>
                                        <span class="text-[9px] font-mono text-slate-500 uppercase">{{ $reg->color }}</span>
                                    </div>
                                @else
                                    <span class="text-[9px] text-slate-400 uppercase font-black">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($reg->geojson)
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black bg-emerald-500/10 text-emerald-500 border border-emerald-500/10 uppercase tracking-widest">
                                        Ada Poligon
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black bg-rose-500/10 text-rose-500 border border-rose-500/10 uppercase tracking-widest">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('map-regions.edit', $reg->id) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Edit Batas Wilayah">
                                        <iconify-icon icon="lucide:edit-3" class="text-sm"></iconify-icon>
                                    </a>
                                    <form action="{{ route('map-regions.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus batas wilayah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition" title="Delete Batas Wilayah">
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
        @if($regions->hasPages())
        <div class="px-6 py-4 border-t border-black/[0.02] dark:border-white/[0.02] bg-slate-50/50 dark:bg-slate-800/30">
            {{ $regions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

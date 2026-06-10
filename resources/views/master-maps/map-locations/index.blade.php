@extends('layouts.app')

@section('title', 'Manajemen Titik Lokasi POI')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Titik Lokasi (POI)</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola titik lokasi fasilitas publik, rumah ibadah, kantor pemerintahan, sekolah, dll.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('map-locations.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                Tambah Titik Lokasi
            </a>
        </div>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lokasi</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kategori</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Koordinat</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Wilayah</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Foto</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Restriksi</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                    @forelse($locations as $loc)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                            <td class="py-4 px-6">
                                <div>
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider group-hover:text-primary-acorn transition">{{ $loc->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $loc->address ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest inline-flex items-center gap-1.5 border"
                                    style="background-color: {{ $loc->category->color }}10; color: {{ $loc->category->color }}; border-color: {{ $loc->category->color }}20;">
                                    <iconify-icon icon="{{ $loc->category->icon }}"></iconify-icon>
                                    {{ $loc->category->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center font-mono text-[9px] text-slate-500">
                                {{ number_format($loc->latitude, 5) }}, {{ number_format($loc->longitude, 5) }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-black/5 uppercase tracking-widest">
                                    {{ $loc->region->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($loc->photo)
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black bg-emerald-500/10 text-emerald-500 border border-emerald-500/10 uppercase tracking-widest">
                                        Ada Foto
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black bg-slate-500/10 text-slate-400 border border-black/5 uppercase tracking-widest">
                                        Tidak Ada
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($loc->restrictions()->exists())
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black bg-rose-500/10 text-rose-500 border border-rose-500/10 uppercase tracking-widest">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black bg-slate-500/10 text-slate-400 border border-black/5 uppercase tracking-widest">
                                        Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('map-locations.edit', $loc->id) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Edit Lokasi">
                                        <iconify-icon icon="lucide:edit-3" class="text-sm"></iconify-icon>
                                    </a>
                                    <form action="{{ route('map-locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition" title="Delete Lokasi">
                                            <iconify-icon icon="lucide:trash-2" class="text-sm"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Data Tidak Ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($locations->hasPages())
        <div class="px-6 py-4 border-t border-black/[0.02] dark:border-white/[0.02] bg-slate-50/50 dark:bg-slate-800/30">
            {{ $locations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

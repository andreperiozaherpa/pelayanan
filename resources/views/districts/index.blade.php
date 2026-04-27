@extends('layouts.app')

@section('title', 'Manajemen Kecamatan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen Kecamatan</h1>
            <p class="text-slate-500 mt-1 font-medium">Kelola data master kecamatan dan penetapan wilayah kabupaten.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('districts.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Kecamatan
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <form action="{{ route('districts.index') }}" method="GET" class="relative group">
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full pl-12 pr-4 py-4 bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all font-bold dark:text-white"
                placeholder="Cari berdasarkan nama kecamatan, kode, atau kabupaten..." />
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700/50">
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Kecamatan</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Kode</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Desa Terdaftar</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Kabupaten</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                    @forelse($districts as $district)
                        <tr class="hover:bg-white/60 dark:hover:bg-slate-800/60 transition-colors group">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center font-black">
                                        {{ substr($district->name, 0, 1) }}
                                    </div>
                                    <p class="font-black text-slate-900 dark:text-white">{{ $district->name }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap font-bold text-slate-500 text-sm">
                                {{ $district->code }}
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black bg-indigo-500/10 text-indigo-500 border border-indigo-500/20 uppercase tracking-widest">
                                    {{ $district->villages_count }} Desa
                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="text-sm font-bold text-slate-600 dark:text-slate-400">{{ $district->regency_name }}</p>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('districts.edit', $district->id) }}" class="p-2 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl transition border border-slate-200 dark:border-white/5" title="Edit Kecamatan">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('districts.destroy', $district->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kecamatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl transition border border-rose-500/20" title="Delete Kecamatan">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500 font-medium tracking-tight">Belum ada data kecamatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($districts->hasPages())
        <div class="p-6 border-t border-slate-200 dark:border-slate-700/50">
            {{ $districts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

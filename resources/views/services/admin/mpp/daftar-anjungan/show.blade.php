@extends('layouts.app')

@section('title', 'Detail Anjungan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('anjungans.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-acorn transition shadow-sm">
            <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
            <span>Kembali</span>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">{{ $anjungan->name }}</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">{{ $anjungan->location }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="premium-card p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                        <iconify-icon icon="lucide:monitor" class="text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kode Anjungan</p>
                        <p class="text-lg font-black text-slate-800 dark:text-white">{{ $anjungan->code }}</p>
                    </div>
                </div>

                <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi</p>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $anjungan->location ?? '-' }}</p>
                </div>

                <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    @if($anjungan->is_active)
                        <x-badge variant="success" label="Aktif" />
                    @else
                        <x-badge variant="danger" label="Nonaktif" />
                    @endif
                </div>

                <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Jumlah Layanan</p>
                    <p class="text-2xl font-black text-primary-acorn">{{ $services->total() }}</p>
                </div>

                <a href="{{ route('anjungans.edit', $anjungan) }}"
                    class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary-acorn hover:bg-primary-acorn/90 text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                    <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                    Edit Anjungan
                </a>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="premium-card p-0 overflow-hidden">
                <div class="px-6 py-4 border-b border-black/[0.03] dark:border-white/[0.03] flex items-center justify-between">
                    <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">Daftar Pelayanan</h2>
                    <a href="{{ route('mpp-services.create', ['anjungan_id' => $anjungan->id]) }}"
                        class="flex items-center gap-1.5 px-4 py-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white rounded-xl font-black text-[9px] uppercase tracking-widest transition-all shadow-sm">
                        <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                        Tambah Pelayanan
                    </a>
                </div>

                @if($services->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-slate-800/20">
                                    <th class="py-3 px-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">Nama Pelayanan</th>
                                    <th class="py-3 px-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">Slug</th>
                                    <th class="py-3 px-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">Status</th>
                                    <th class="py-3 px-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition border-b border-black/[0.02] dark:border-white/[0.02]">
                                        <td class="py-4 px-6">
                                            <p class="text-[11px] font-black text-slate-800 dark:text-white">{{ $service->name }}</p>
                                        </td>
                                        <td class="py-4 px-6 text-[10px] font-medium text-slate-500">{{ $service->slug }}</td>
                                        <td class="py-4 px-6">
                                            @if($service->is_active)
                                                <x-badge variant="success" label="Aktif" />
                                            @else
                                                <x-badge variant="danger" label="Nonaktif" />
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <a href="{{ route('mpp-services.edit', $service) }}" class="p-2 text-slate-400 hover:text-primary-acorn rounded-lg transition inline-block" title="Edit">
                                                <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($services->hasPages())
                        <div class="px-6 py-3 border-t border-black/[0.03] dark:border-white/[0.03]">
                            {{ $services->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <iconify-icon icon="lucide:inbox" class="text-3xl text-slate-300"></iconify-icon>
                            <p class="text-sm font-medium text-slate-400">Belum ada pelayanan untuk anjungan ini</p>
                            <a href="{{ route('mpp-services.create', ['anjungan_id' => $anjungan->id]) }}"
                                class="text-xs font-bold text-primary-acorn hover:underline">Tambah Pelayanan</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

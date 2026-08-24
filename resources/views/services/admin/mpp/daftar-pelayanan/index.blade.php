@extends('layouts.app')

@section('title', 'Manajemen Pelayanan MPP')

@section('content')
    <div class="space-y-6">
        <!-- Premium Style Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Daftar Pelayanan MPP
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola jenis-jenis pelayanan publik MPP dan
                    konfigurasikan formulir permohonannya secara dinamis.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('mpp-services.create') }}"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                    <iconify-icon icon="lucide:plus-circle" class="text-lg"></iconify-icon>
                    Buat Pelayanan Baru
                </a>
            </div>
        </div>

        <x-datatable :records="$services" search-placeholder="Cari nama pelayanan..." :search-value="request('search')">
            <x-slot:thead>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Logo & Nama Pelayanan
                </th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Instansi</th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi</th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah Kolom Form</th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
            </x-slot:thead>

            @forelse($services as $service)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            @if ($service->logo)
                                <div
                                    class="h-10 w-10 rounded-xl border border-black/5 bg-slate-100 overflow-hidden flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $service->logo) }}" alt="Logo"
                                        class="object-cover h-full w-full">
                                </div>
                            @else
                                <div
                                    class="h-10 w-10 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center text-[12px] font-black uppercase">
                                    <iconify-icon icon="lucide:file-text" class="text-lg"></iconify-icon>
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                        {{ $service->name }}</p>
                                </div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">{{ $service->slug }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        @if ($service->opd)
                            <span
                                class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-700/30">
                                {{ $service->opd->code }}
                            </span>
                        @else
                            <span class="text-[9px] font-bold text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-[10px] font-medium text-slate-600 dark:text-slate-300 max-w-xs truncate">
                            {{ $service->description ?? '-' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <span
                            class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-black/5">
                            {{ count($service->fields ?? []) }} Kolom
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        @if ($service->is_active)
                            <x-badge variant="success" label="Aktif" />
                        @else
                            <x-badge variant="danger" label="Nonaktif" />
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('mpp-services.edit', $service) }}"
                                class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition"
                                title="Edit Pelayanan">
                                <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                            </a>
                            <form action="{{ route('mpp-services.destroy', $service) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelayanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition"
                                    title="Hapus Pelayanan">
                                    <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Belum ada pelayanan
                            dinamis yang dibuat.</p>
                    </td>
                </tr>
            @endforelse
        </x-datatable>
    </div>
@endsection

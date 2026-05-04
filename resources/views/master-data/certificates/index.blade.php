@extends('layouts.app')

@section('title', 'Manajemen Sertifikat TTE')

@section('content')
<div class="space-y-6">
    <!-- Premium Style Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Sertifikat Elektronik</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola dan terbitkan Sertifikat TTE Kepala Desa.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Gagal!</span> {{ session('error') }}
        </div>
    @endif

    <x-datatable :records="$leaders" search-placeholder="Cari nama pejabat, NIP, atau desa...">
        <x-slot name="thead">
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pejabat / Penandatangan</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Instansi / Desa</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status TTE</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Masa Berlaku</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
        </x-slot>

        @forelse($leaders as $leader)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center text-[11px] font-black uppercase">
                            {{ substr($leader->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider group-hover:text-primary-acorn transition">
                                {{ $leader->name }}
                            </p>
                            <p class="text-[9px] {{ $leader->type === 'Village' ? 'text-blue-500' : 'text-purple-500' }} font-black uppercase tracking-widest">
                                {{ $leader->unit_type }}
                            </p>
                            @if($leader->nip)
                                <p class="text-[8px] text-slate-400 font-bold tracking-tighter">NIP. {{ $leader->nip }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6 text-center">
                    <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                        {{ $leader->unit_name }}
                    </p>
                </td>
                <td class="py-4 px-6 text-center">
                    @if(!$leader->user)
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 uppercase tracking-widest">
                            Belum Ada Akun
                        </span>
                    @elseif($leader->certificate && \Carbon\Carbon::parse($leader->certificate['valid_until'])->isFuture())
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 uppercase tracking-widest">
                            Aktif
                        </span>
                    @elseif($leader->certificate)
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 uppercase tracking-widest">
                            Kedaluwarsa
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 uppercase tracking-widest">
                            Belum Ada TTE
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6 text-center">
                    @if($leader->certificate)
                        <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            s/d {{ \Carbon\Carbon::parse($leader->certificate['valid_until'])->format('d M Y') }}
                        </p>
                    @else
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">-</p>
                    @endif
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        @if($leader->user)
                            <form action="{{ route('admin.certificates.generate', $leader->user_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin men-generate ulang sertifikat untuk pejabat ini?')">
                                @csrf
                                <button type="submit" class="bg-primary-acorn hover:bg-primary-acorn/90 text-white px-4 py-2 rounded-lg font-black text-[9px] uppercase tracking-widest transition shadow-lg shadow-primary-acorn/20">
                                    {{ $leader->certificate ? 'Perbarui TTE' : 'Generate TTE' }}
                                </button>
                            </form>
                        @else
                            <button disabled title="Tautkan Pejabat ini ke Akun User terlebih dahulu" class="bg-slate-200 text-slate-400 cursor-not-allowed px-4 py-2 rounded-lg font-black text-[9px] uppercase tracking-widest transition">
                                Generate TTE
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-16 text-center">
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Data Pejabat Tidak Ditemukan.</p>
                </td>
            </tr>
        @endforelse
    </x-datatable>
</div>
@endsection

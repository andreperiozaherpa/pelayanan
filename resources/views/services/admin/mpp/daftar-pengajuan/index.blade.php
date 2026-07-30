@extends('layouts.app')

@section('title', 'Pengajuan Permohonan MPP')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span
                        class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-[0.2em] rounded-full border border-primary-acorn/20 shadow-sm">
                        Mal Pelayanan Publik
                    </span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Layanan Dinamis</span>
                </div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase leading-none">
                    Daftar Pengajuan MPP
                </h1>
                <p class="text-[11px] text-slate-500 font-bold tracking-tight mt-2 uppercase opacity-60">
                    Rekap permohonan layanan dinamis yang diajukan melalui formulir MPP
                </p>
            </div>

            <div class="flex items-center gap-4">
                <div
                    class="premium-card px-6 py-4 flex items-center gap-5 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl border-primary-acorn/10">
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Pengajuan MPP</p>
                        <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">
                            {{ $mppRequests->total() }} Data
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center border border-primary-acorn/10 shadow-inner">
                        <iconify-icon icon="lucide:file-text" class="text-xl"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>

        <div class="premium-card p-6">
            <form method="GET" action="{{ route('mpp-requests.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Cari Nama Layanan</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama layanan..."
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all uppercase tracking-wider">
                        <iconify-icon icon="lucide:search"
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></iconify-icon>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Filter Status</label>
                    <select name="status"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all uppercase tracking-wider appearance-none">
                        <option value="">Semua Status</option>
                        <option value="PENDING" @selected($status == 'PENDING')>Pending</option>
                        <option value="PROCESSED" @selected($status == 'PROCESSED')>Diproses</option>
                        <option value="COMPLETED" @selected($status == 'COMPLETED')>Selesai</option>
                        <option value="REJECTED" @selected($status == 'REJECTED')>Ditolak</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Filter Layanan</label>
                    <select name="mpp_service_id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all uppercase tracking-wider appearance-none">
                        <option value="">Semua Layanan</option>
                        @foreach ($mppServices as $svc)
                            <option value="{{ $svc->id }}" @selected($mppServiceId == $svc->id)>{{ $svc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col justify-end gap-2">
                    <div class="flex gap-2">
                        <a href="{{ route('mpp-requests.index') }}"
                            class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-center">
                            Reset
                        </a>
                        <button type="submit"
                            class="flex-1 bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-md flex items-center justify-center gap-1.5">
                            <iconify-icon icon="lucide:filter" class="text-sm"></iconify-icon>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="premium-card p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/20 border-b border-black/[0.03] dark:border-white/[0.03]">
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">No</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Layanan</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Pemohon</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Status</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Tanggal</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mppRequests as $request)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition border-b border-black/[0.02] dark:border-white/[0.02]">
                                <td class="py-4 px-6 text-[11px] font-bold text-slate-500">{{ $loop->iteration }}</td>
                                <td class="py-4 px-6">
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                        {{ $request->mppService?->name ?? '-' }}
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-[11px] font-medium text-slate-600 dark:text-slate-300">
                                        {{ $request->applicant_name }}
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusBadge = match ($request->status) {
                                            'PENDING' => ['warning', 'Pending'],
                                            'PROCESSED' => ['info', 'Diproses'],
                                            'COMPLETED' => ['success', 'Selesai'],
                                            'REJECTED' => ['danger', 'Ditolak'],
                                            default => ['secondary', $request->status],
                                        };
                                    @endphp
                                    <x-badge variant="{{ $statusBadge[0] }}" label="{{ $statusBadge[1] }}" />
                                </td>
                                <td class="py-4 px-6 text-[11px] font-medium text-slate-500">
                                    {{ $request->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('mpp-requests.show', $request) }}" class="p-2 text-slate-400 hover:text-primary-acorn rounded-lg transition inline-block" title="Detail">
                                        <iconify-icon icon="lucide:eye" class="text-lg"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <iconify-icon icon="lucide:inbox" class="text-4xl text-slate-300"></iconify-icon>
                                        <p class="text-sm font-medium text-slate-400">Belum ada pengajuan MPP</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($mppRequests->hasPages())
                <div class="px-6 py-4 border-t border-black/[0.03] dark:border-white/[0.03]">
                    {{ $mppRequests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

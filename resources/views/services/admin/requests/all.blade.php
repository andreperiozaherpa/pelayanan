@extends('layouts.app')

@section('title', 'Daftar Pengajuan Layanan')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span
                        class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-[0.2em] rounded-full border border-primary-acorn/20 shadow-sm">
                        Pelayanan
                    </span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Rekap Pengajuan</span>
                </div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase leading-none">
                    Seluruh Pengajuan Layanan
                </h1>
                <p class="text-[11px] text-slate-500 font-bold tracking-tight mt-2 uppercase opacity-60">
                    Rekap permohonan layanan yang diajukan oleh warga dari seluruh desa
                </p>
            </div>

            <div class="flex items-center gap-4">
                <div
                    class="premium-card px-6 py-4 flex items-center gap-5 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl border-primary-acorn/10">
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Pengajuan</p>
                        <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">
                            {{ $requests->total() }} Data
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
            <form method="GET" action="{{ route('services.requests.all') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Cari Nama / NIK</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau NIK..."
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
                        <option value="APPROVED" @selected($status == 'APPROVED')>Disetujui</option>
                        <option value="REJECTED" @selected($status == 'REJECTED')>Ditolak</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Filter Jenis Layanan</label>
                    <select name="type"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all uppercase tracking-wider appearance-none">
                        <option value="">Semua Layanan</option>
                        @foreach (\App\Enums\ServiceType::cases() as $serviceType)
                            <option value="{{ $serviceType->value }}" @selected($type == $serviceType->value)>{{ $serviceType->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Filter Desa</label>
                    <select name="village_id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all uppercase tracking-wider appearance-none">
                        <option value="">Semua Desa</option>
                        @foreach ($villages as $village)
                            <option value="{{ $village->id }}" @selected((string) $villageId === (string) $village->id)>{{ $village->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-4 flex justify-end gap-2">
                    <a href="{{ route('services.requests.all') }}"
                        class="px-4 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-center">
                        Reset
                    </a>
                    <button type="submit"
                        class="bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3 px-6 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-md flex items-center justify-center gap-1.5">
                        <iconify-icon icon="lucide:filter" class="text-sm"></iconify-icon>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="premium-card p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/20 border-b border-black/[0.03] dark:border-white/[0.03]">
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">No</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">NIK</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Nama Warga</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Jenis Layanan</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Desa</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Status</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition border-b border-black/[0.02] dark:border-white/[0.02]">
                                <td class="py-4 px-6 text-[11px] font-bold text-slate-500">{{ $loop->iteration }}</td>
                                <td class="py-4 px-6">
                                    <span class="text-[11px] font-black text-slate-500 tabular-nums tracking-wider">{{ $request->citizen_nik }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                        {{ $request->citizen?->nama_lengkap ?? '-' }}
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-[11px] font-medium text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                        {{ $request->service_type->value }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">
                                        {{ $request->citizen?->village?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusBadge = match ($request->status) {
                                            'APPROVED' => ['success', 'Disetujui'],
                                            'REJECTED' => ['danger', 'Ditolak'],
                                            'PENDING' => ['warning', 'Pending'],
                                            default => ['secondary', $request->status],
                                        };
                                    @endphp
                                    <x-badge variant="{{ $statusBadge[0] }}" label="{{ $statusBadge[1] }}" />
                                </td>
                                <td class="py-4 px-6 text-[11px] font-medium text-slate-500">
                                    {{ $request->created_at->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <iconify-icon icon="lucide:inbox" class="text-4xl text-slate-300"></iconify-icon>
                                        <p class="text-sm font-medium text-slate-400">Belum ada pengajuan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($requests->hasPages())
                <div class="px-6 py-4 border-t border-black/[0.03] dark:border-white/[0.03]">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

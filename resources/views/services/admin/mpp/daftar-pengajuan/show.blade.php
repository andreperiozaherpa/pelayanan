@extends('layouts.app')

@section('title', 'Detail Pengajuan MPP')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('mpp-requests.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-acorn transition shadow-sm">
            <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
            <span>Kembali</span>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Detail Pengajuan</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">{{ $mppServiceRequest->mppService?->name ?? '-' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="premium-card p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                        <iconify-icon icon="lucide:file-text" class="text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Layanan</p>
                        <p class="text-sm font-black text-slate-800 dark:text-white">{{ $mppServiceRequest->mppService?->name ?? '-' }}</p>
                    </div>
                </div>

                @if ($mppServiceRequest->nomor_antrian)
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-primary-acorn/10 border border-primary-acorn/20 px-4 py-3">
                        <div>
                            <p class="text-[9px] font-black text-primary-acorn uppercase tracking-widest mb-0.5">Nomor Antrian</p>
                            <p class="text-2xl font-black text-primary-acorn tracking-widest">{{ $mppServiceRequest->nomor_antrian }}</p>
                        </div>
                        <iconify-icon icon="lucide:ticket" class="text-3xl text-primary-acorn/70"></iconify-icon>
                    </div>
                @endif

                <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    @php
                        $badge = match ($mppServiceRequest->status) {
                            'PENDING' => ['warning', 'Pending'],
                            'PROCESSED' => ['info', 'Diproses'],
                            'COMPLETED' => ['success', 'Selesai'],
                            'REJECTED' => ['danger', 'Ditolak'],
                            default => ['secondary', $mppServiceRequest->status],
                        };
                    @endphp
                    <x-badge variant="{{ $badge[0] }}" label="{{ $badge[1] }}" />
                </div>

                <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Pengajuan</p>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $mppServiceRequest->created_at->format('d M Y H:i') }}</p>
                </div>

                <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Petugas FO</p>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $mppServiceRequest->frontOfficeUser?->name ?? '-' }}</p>
                </div>

                @if($mppServiceRequest->notes)
                    <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Catatan</p>
                        <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $mppServiceRequest->notes }}</p>
                    </div>
                @endif

                @if($mppServiceRequest->queue)
                    <div class="border-t border-black/[0.03] dark:border-white/[0.03] pt-4 space-y-2.5">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Durasi Proses</p>

                        <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] p-3">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Front Office</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $mppServiceRequest->queue->durasi_fo ?? '-' }}</p>
                            <p class="text-[10px] font-medium text-slate-400">
                                {{ $mppServiceRequest->queue->fo_called_at?->format('H:i') ?? '-' }} → {{ $mppServiceRequest->queue->fo_finished_at?->format('H:i') ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] p-3">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Gerai</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $mppServiceRequest->queue->durasi_gerai ?? '-' }}</p>
                            <p class="text-[10px] font-medium text-slate-400">
                                {{ $mppServiceRequest->queue->gerai_called_at?->format('H:i') ?? '-' }} → {{ $mppServiceRequest->queue->done_at?->format('H:i') ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-primary-acorn/10 border border-primary-acorn/20 p-3">
                            <p class="text-[9px] font-black text-primary-acorn uppercase tracking-widest mb-0.5">Total</p>
                            <p class="text-sm font-black text-primary-acorn">{{ $mppServiceRequest->queue->durasi_total ?? '-' }}</p>
                            <p class="text-[10px] font-medium text-primary-acorn/60">
                                {{ $mppServiceRequest->created_at->format('H:i') }} → {{ $mppServiceRequest->queue->done_at?->format('H:i') ?? '-' }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="premium-card p-0 overflow-hidden">
                <div class="px-6 py-4 border-b border-black/[0.03] dark:border-white/[0.03]">
                    <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">Data Permohonan</h2>
                </div>
                <div class="p-6 space-y-5">
                    @forelse(($mppServiceRequest->submitted_form_data ?? []) as $fieldName => $data)
                        @php
                            $fieldDef = collect($mppServiceRequest->mppService?->fields ?? [])
                                ->firstWhere('name', $fieldName);
                            $fieldOptions = $fieldDef['options'] ?? [];
                            $checkedValues = (array) ($data['value'] ?? []);
                        @endphp
                        <div class="pb-4 border-b border-black/[0.02] dark:border-white/[0.02] last:border-0">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $data['label'] ?? $fieldName }}</p>
                            @if(($data['type'] ?? 'text') === 'file' && !empty($data['value']))
                                <a href="{{ asset('storage/' . $data['value']) }}" target="_blank" class="text-[11px] font-bold text-primary-acorn hover:underline">
                                    <iconify-icon icon="lucide:file" class="inline-block mr-1"></iconify-icon>
                                    {{ $data['original_name'] ?? 'Lihat Berkas' }}
                                </a>
                            @elseif(($data['type'] ?? 'text') === 'checkbox' && !empty($fieldOptions))
                                <div class="space-y-1.5 mt-1">
                                    @foreach($fieldOptions as $opt)
                                        <div class="flex items-center gap-2">
                                            <span class="w-4 h-4 rounded border flex items-center justify-center text-[10px] {{ in_array($opt, $checkedValues, true) ? 'bg-primary-acorn text-white border-primary-acorn' : 'border-slate-300 dark:border-slate-600 text-transparent' }}">
                                                <iconify-icon icon="lucide:check"></iconify-icon>
                                            </span>
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ $opt }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(is_array($data['value'] ?? null))
                                <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ implode(', ', $data['value']) }}</p>
                            @else
                                <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ $data['value'] ?? '-' }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-[10px] font-bold text-slate-400 text-center py-8">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

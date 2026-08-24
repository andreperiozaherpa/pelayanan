@extends('layouts.app')

@section('title', 'Detail Survei — ' . $opd->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('skm.index') }}" class="inline-flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-primary-acorn transition mb-2">
                <iconify-icon icon="lucide:arrow-left" class="text-base"></iconify-icon>
                Kembali
            </a>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                <span class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-wider rounded-lg border border-primary-acorn/20 mr-2">{{ $opd->code }}</span>
                {{ $opd->name }}
            </h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Periode: {{ $from ? \Carbon\Carbon::parse($from)->translatedFormat('d F Y') : 'Awal' }} — {{ $to ? \Carbon\Carbon::parse($to)->translatedFormat('d F Y') : 'Sekarang' }}</p>
        </div>
        <a href="{{ route('skm.export', ['opd_id' => $opd->id] + array_filter(['period' => $period])) }}"
            class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
            <iconify-icon icon="lucide:download" class="text-lg"></iconify-icon>
            Ekspor CSV
        </a>
    </div>

    {{-- Ringkasan Mutu --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Responden</p>
            <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $rekap['total_responden'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">IKM Komposit</p>
            <p class="text-3xl font-black text-primary-acorn">{{ $rekap['ikm'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Mutu Pelayanan</p>
            <div class="flex items-center gap-3 mt-1">
                <span class="w-12 h-12 rounded-xl {{ $rekap['mutu']['nilai'] === 'A' ? 'bg-emerald-500' : ($rekap['mutu']['nilai'] === 'B' ? 'bg-amber-500' : ($rekap['mutu']['nilai'] === 'C' ? 'bg-orange-500' : 'bg-rose-500')) }} text-white flex items-center justify-center text-2xl font-black">{{ $rekap['mutu']['nilai'] }}</span>
                <div>
                    <p class="text-sm font-black text-slate-800 dark:text-white">{{ $rekap['mutu']['kinerja'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400">{{ $rekap['mutu']['kepuasan'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Rincian Per Unsur --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
            <iconify-icon icon="lucide:list-checks" class="text-lg text-primary-acorn"></iconify-icon>
            <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Nilai Per Unsur</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Unsur</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">NRR</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">IKM</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Prioritas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @foreach ($rekap['prioritas'] as $index => $u)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-[9px] font-black uppercase tracking-wider rounded-lg mr-2">{{ $u['kode'] }}</span>
                                <span class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase tracking-wider">{{ $u['nama'] }}</span>
                            </td>
                            <td class="py-4 px-6 text-center text-[11px] font-black text-slate-600 dark:text-slate-300">{{ $u['nrr'] }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-[13px] font-black {{ $u['ikm'] >= 76.61 ? 'text-emerald-500' : ($u['ikm'] >= 65 ? 'text-amber-500' : 'text-rose-500') }}">{{ $u['ikm'] }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $index === 0 ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20' : ($index === 1 ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : ($index === 2 ? 'bg-blue-500/10 text-blue-500 border border-blue-500/20' : 'bg-slate-100 text-slate-400 border border-slate-200')) }}">
                                    {{ $index === 0 ? 'Perbaikan Utama' : ($index === 1 ? 'Prioritas 2' : ($index === 2 ? 'Prioritas 3' : '-')) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Daftar Responden --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
            <iconify-icon icon="lucide:users" class="text-lg text-primary-acorn"></iconify-icon>
            <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Responden ({{ $responses->total() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Responden</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Usia</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sumber</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nilai IKM</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse ($responses as $response)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-4 px-6">
                                <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">{{ $response->nama ?: 'Anonim' }}</p>
                                <p class="text-[10px] font-medium text-slate-400">{{ collect([$response->pendidikan, $response->pekerjaan])->filter()->implode(' · ') ?: 'Profil tidak diisi' }}</p>
                            </td>
                            <td class="py-4 px-6 text-[11px] font-bold text-slate-500">{{ $response->umur ?: '-' }}</td>
                            <td class="py-4 px-6">
                                <x-badge variant="{{ $response->source === 'api' ? 'info' : 'success' }}" :label="$response->source === 'api' ? 'API' : 'Web'" />
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-[13px] font-black text-primary-acorn">{{ round(collect(\App\Services\SkmService::unsur())->pluck('key')->map(fn ($k) => $response->{$k})->avg() * 25, 2) }}</span>
                            </td>
                            <td class="py-4 px-6 text-right text-[10px] font-bold text-slate-400">{{ $response->created_at->translatedFormat('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <iconify-icon icon="lucide:users" class="text-3xl text-slate-300"></iconify-icon>
                                    <p class="text-sm font-medium text-slate-400">Belum ada responden pada periode ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($responses->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                {{ $responses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

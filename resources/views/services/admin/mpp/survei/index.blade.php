@extends('layouts.app')

@section('title', 'Laporan Survei Kepuasan Masyarakat')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Laporan Survei Kepuasan Masyarakat</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Rekap SKM per gerai sesuai Permen PAN-RB No. 14 Tahun 2017.</p>
        </div>
        <a href="{{ route('skm.export', array_filter(request()->only(['period', 'opd_id']))) }}"
            class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
            <iconify-icon icon="lucide:download" class="text-lg"></iconify-icon>
            Ekspor CSV
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('skm.index') }}"
        class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 flex flex-col md:flex-row items-end gap-4">
        <div class="flex-1">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Instansi</label>
            <select name="opd_id" class="w-full rounded-xl border-slate-200 text-sm font-semibold text-slate-700">
                <option value="">Semua Instansi</option>
                @foreach ($opds as $g)
                    <option value="{{ $g->id }}" @selected($opdId == $g->id)>{{ $g->code }} — {{ $g->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Periode</label>
            <select name="period" class="w-full rounded-xl border-slate-200 text-sm font-semibold text-slate-700">
                @foreach (['' => 'Semua Waktu', 'today' => 'Hari Ini', 'this_month' => 'Bulan Ini', 'last_month' => 'Bulan Lalu', 'this_quarter' => 'Triwulan Ini', 'this_semester' => 'Semester Ini', 'this_year' => 'Tahun Ini'] as $val => $label)
                    <option value="{{ $val }}" @selected($period === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="flex items-center justify-center gap-2 bg-slate-800 dark:bg-slate-700 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
            <iconify-icon icon="lucide:filter" class="text-lg"></iconify-icon>
            Terapkan
        </button>
    </form>

    @if ($from || $to)
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
            <iconify-icon icon="lucide:calendar" class="text-base text-primary-acorn"></iconify-icon>
            Periode: {{ $from ? \Carbon\Carbon::parse($from)->translatedFormat('d F Y') : 'Awal' }} — {{ $to ? \Carbon\Carbon::parse($to)->translatedFormat('d F Y') : 'Sekarang' }}
        </div>
    @endif

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Responden</p>
            <p class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($totalResponden) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Instansi Dinilai</p>
            <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $rows->count() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">IKM Tertinggi</p>
            <p class="text-3xl font-black text-primary-acorn">{{ $rows->first()['rekap']['ikm'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Tabel Rekap per Gerai --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Instansi</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Responden</th>
                        @foreach (\App\Services\SkmService::unsur() as $u)
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">{{ $u['kode'] }}</th>
                        @endforeach
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">IKM</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Mutu</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-wider rounded-lg border border-primary-acorn/20 mr-2">{{ $row['opd']->code }}</span>
                                <a href="{{ route('skm.show', $row['opd']) }}" class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider hover:text-primary-acorn transition">{{ $row['opd']->name }}</a>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 text-[11px] font-black">{{ $row['rekap']['total_responden'] }}</span>
                            </td>
                            @foreach ($row['rekap']['unsur'] as $u)
                                <td class="py-4 px-6 text-center text-[11px] font-black text-slate-600 dark:text-slate-300">{{ $u['ikm'] }}</td>
                            @endforeach
                            <td class="py-4 px-6 text-center">
                                <span class="text-sm font-black text-primary-acorn">{{ $row['rekap']['ikm'] }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <x-badge variant="{{ $row['rekap']['mutu']['nilai'] === 'A' ? 'success' : ($row['rekap']['mutu']['nilai'] === 'B' ? 'warning' : 'danger') }}"
                                    :label="$row['rekap']['mutu']['nilai'] . ' · ' . $row['rekap']['mutu']['kinerja']" />
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('skm.show', $row['opd']) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Detail Laporan">
                                    <iconify-icon icon="lucide:eye" class="text-lg"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 8 + count(\App\Services\SkmService::unsur()) }}" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <iconify-icon icon="lucide:clipboard-check" class="text-3xl text-slate-300"></iconify-icon>
                                    <p class="text-sm font-medium text-slate-400">Belum ada data survei pada periode ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

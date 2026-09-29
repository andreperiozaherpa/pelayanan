@extends('layouts.app')

@section('title', 'Kiosk & Token MPP')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 dark:text-white uppercase">Kiosk & Token</h1>
        <p class="text-xs text-slate-500">Kelola perangkat yang dapat menerbitkan nomor antrian.</p>
    </div>

    @if(session('kiosk_token'))
        <div class="rounded-xl border border-amber-300 bg-amber-50 p-5 text-sm text-amber-900">
            <p class="font-black">Token untuk {{ session('kiosk_name') }}</p>
            <p class="mt-1">Salin sekarang. Token tidak ditampilkan lagi setelah halaman ini ditutup.</p>
            <code class="mt-3 block break-all rounded-lg bg-white p-3 text-xs">{{ session('kiosk_token') }}</code>
        </div>
    @endif

    <form method="POST" action="{{ route('mpp-kiosks.store') }}" class="flex gap-3 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-800">
        @csrf
        <input name="name" required maxlength="100" value="{{ old('name') }}" placeholder="Contoh: Kiosk Lantai 1" class="flex-1 rounded-lg border-slate-300 text-sm dark:bg-slate-900" />
        <button class="rounded-lg bg-primary-acorn px-4 py-2 text-xs font-black uppercase text-white">Buat Token</button>
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-slate-800">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-900"><tr><th class="p-4">Kiosk</th><th class="p-4">Terakhir aktif</th><th class="p-4">Status</th><th class="p-4 text-right">Aksi</th></tr></thead>
            <tbody>
            @forelse($devices as $device)
                <tr class="border-t border-slate-100 dark:border-slate-700"><td class="p-4 font-bold">{{ $device->name }}</td><td class="p-4">{{ $device->last_seen_at?->format('d M Y H:i') ?? '-' }}</td><td class="p-4">{{ $device->is_active ? 'Aktif' : 'Nonaktif' }}</td><td class="p-4 text-right"><form class="inline" method="POST" action="{{ route('mpp-kiosks.rotate', $device) }}">@csrf <button class="text-primary-acorn">Rotasi token</button></form><form class="ml-3 inline" method="POST" action="{{ route('mpp-kiosks.toggle', $device) }}">@csrf <button class="text-red-600">{{ $device->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form></td></tr>
            @empty
                <tr><td colspan="4" class="p-8 text-center text-slate-500">Belum ada kiosk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

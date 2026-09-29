@extends('layouts.public')
@section('content')
<main class="mx-auto max-w-4xl px-5 py-12"><h1 class="text-3xl font-black text-slate-800">Pendaftaran Layanan MPP</h1><p class="mt-2 text-slate-600">Daftar dari rumah, lalu pindai barcode di kiosk untuk mengambil nomor antrean.</p><div class="mt-8 grid gap-4 md:grid-cols-2">@forelse($services as $service)<a class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" href="{{ route('public.mpp-registration.create', $service) }}"><h2 class="font-bold">{{ $service->name }}</h2><p class="mt-2 text-sm text-slate-500">{{ $service->description }}</p><span class="mt-4 inline-block text-sm font-bold text-primary-acorn">Daftar sekarang →</span></a>@empty<p>Belum ada layanan publik yang aktif.</p>@endforelse</div></main>
@endsection

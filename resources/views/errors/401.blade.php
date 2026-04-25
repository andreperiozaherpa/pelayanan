@extends('errors.layout')

@section('title', 'Unauthorized')
@section('code', '401')
@section('icon_color', 'text-amber-500 shadow-amber-500/20')

@section('icon')
<svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
</svg>
@endsection

@section('message')
    Maaf, Anda tidak dapat mengakses halaman ini sebelum melakukan identifikasi diri (Login). Sesi Anda mungkin telah berakhir atau kredensial yang diberikan tidak valid.
@endsection

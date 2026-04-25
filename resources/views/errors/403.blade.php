@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('code', '403')
@section('icon_color', 'text-rose-500 shadow-rose-500/20')

@section('icon')
<svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
@endsection

@section('message')
    {!! $exception->getMessage() ?: 'Anda tidak diizinkan mengakses direktori atau fungsionalitas ini karena batasan otoritas Hak Akses (Role/Privilege).' !!}
@endsection

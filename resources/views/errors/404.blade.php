@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')
@section('code', '404')
@section('icon_color', 'text-indigo-500 shadow-indigo-500/20')

@section('icon')
<svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
@endsection

@section('message')
    Kami telah menelusuri seluruh sistem, namun halaman atau modul data yang Anda minta tidak dapat ditemukan. Mungkin URL telah diubah atau data telah dihapus.
@endsection

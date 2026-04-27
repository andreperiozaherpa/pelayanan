@extends('layouts.app')

@section('title', 'Perbarui Data Warga')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('citizens.index') }}" class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Perbarui Data: {{ $citizen->nik }}</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Ubah Data Induk atau Tinjau Status SKTM.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="premium-card p-6 sm:p-10 relative overflow-hidden">
        <form action="{{ route('citizens.update', $citizen->nik) }}" method="POST">
            @method('PUT')
            @include('master-data.citizens.form')
            
            <div class="mt-10 pt-6 border-t border-black/[0.03] dark:border-white/[0.03] flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[9px] text-slate-400 font-black tracking-widest uppercase italic">Terakhir Diperbarui: {{ $citizen->updated_at->diffForHumans() }}</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('citizens.index') }}" class="px-6 py-2.5 rounded-xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-acorn hover:bg-primary-acorn/90 text-white px-8 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

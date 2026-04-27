@extends('layouts.app')

@section('title', 'Perbarui Data Warga')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('citizens.index') }}" class="p-2 bg-white/40 hover:bg-white/80 dark:bg-slate-800/40 dark:hover:bg-slate-700/80 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-xl text-slate-500 hover:text-slate-900 dark:hover:text-white transition shadow-sm">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Perbarui Data: {{ $citizen->nik }}</h1>
            <p class="text-slate-500 mt-1 font-medium">Ubah Data Induk atau Tinjau Status SKTM.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white/60 dark:bg-slate-800/40 backdrop-blur-3xl border border-white/50 dark:border-slate-700/50 rounded-[2rem] shadow-2xl p-6 sm:p-10 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-indigo-500/10 blur-3xl rounded-full pointer-events-none"></div>

        <form action="{{ route('citizens.update', $citizen->nik) }}" method="POST">
            @method('PUT')
            @include('master-data.citizens.form')
            
            <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-700/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500 font-bold tracking-wide uppercase">Terakhir Diperbarui: {{ $citizen->updated_at->diffForHumans() }}</p>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <a href="{{ route('citizens.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-xl shadow-indigo-500/20 transition-all hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

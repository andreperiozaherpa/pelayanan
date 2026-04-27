@extends('layouts.app')

@section('title', 'Tambah Kecamatan Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('districts.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 dark:hover:text-white transition mb-6 group">
        <svg class="h-5 w-5 transform group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="font-bold text-sm text-slate-600 dark:text-slate-400">Kembali ke Daftar</span>
    </a>

    <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-[2.5rem] shadow-2xl overflow-hidden">
        <div class="p-8 sm:p-12">
            <div class="mb-10">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Tambah Kecamatan</h1>
                <p class="text-slate-500 mt-2 font-medium">Input informasi kecamatan baru dan kabupaten terkait.</p>
            </div>

            <form action="{{ route('districts.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Kecamatan</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold transition-all dark:text-white"
                            placeholder="Contoh: Kecamatan Makmur" />
                    </div>

                    <!-- Code -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kode Kecamatan</label>
                        <input type="text" name="code" value="{{ old('code') }}" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold transition-all dark:text-white"
                            placeholder="Contoh: 32.04.01" />
                    </div>

                    <!-- Regency Name -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Kabupaten</label>
                        <input type="text" name="regency_name" value="{{ old('regency_name') }}" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold transition-all dark:text-white"
                            placeholder="Contoh: Kabupaten Bandung" />
                    </div>
                </div>

                <div class="pt-10">
                    <button type="submit" class="w-full py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black uppercase tracking-[0.2em] shadow-2xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Simpan Kecamatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Data OPD')

@section('content')
<div class="space-y-6">
    <!-- Header & Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('opds.index') }}" class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Edit OPD</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui informasi Organisasi Perangkat Daerah <strong>{{ $opd->name }}</strong>.</p>
        </div>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="p-8 sm:p-10">
            <form action="{{ route('opds.update', $opd->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama OPD</label>
                        <input type="text" name="name" value="{{ old('name', $opd->name) }}" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider @error('name') border-red-500 @enderror" />
                        @error('name')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Kode OPD</label>
                        <input type="text" name="code" value="{{ old('code', $opd->code) }}" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider @error('code') border-red-500 @enderror" />
                        @error('code')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Deskripsi / Keterangan</label>
                        <textarea name="description" rows="4"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $opd->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-black/[0.03] dark:border-white/[0.03] flex justify-end gap-3">
                    <a href="{{ route('opds.index') }}" class="px-6 py-2.5 rounded-xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-acorn hover:bg-primary-acorn/90 text-white px-8 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        Perbarui OPD
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tambah Gerai MPP')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('gerais.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-acorn transition shadow-sm">
            <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
            <span>Kembali</span>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Tambah Gerai</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Tambahkan gerai layanan baru untuk MPP.</p>
        </div>
    </div>

    <div class="premium-card">
        <div class="p-8 sm:p-12">
            <form method="POST" action="{{ route('gerais.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Instansi <span class="text-red-500">*</span></label>
                    <select name="opd_id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all appearance-none @error('opd_id') ring-2 ring-red-500 @enderror">
                        <option value="">-- Pilih Instansi --</option>
                        @foreach($opds as $opd)
                            <option value="{{ $opd->id }}" {{ old('opd_id') == $opd->id ? 'selected' : '' }}>
                                {{ $opd->code }} - {{ $opd->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('opd_id')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kode Gerai <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all uppercase tracking-wider @error('code') ring-2 ring-red-500 @enderror"
                        placeholder="Contoh: A, B, C..." required>
                    @error('code')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama Gerai <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all @error('name') ring-2 ring-red-500 @enderror"
                        placeholder="Nama gerai" required>
                    @error('name')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all @error('location') ring-2 ring-red-500 @enderror"
                        placeholder="Lokasi gerai (opsional)">
                    @error('location')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Logo Gerai</label>
                    @include('cms.partials.dropzone-upload', [
                        'id' => 'gerai-logo',
                        'name' => 'logo',
                        'label' => '',
                        'value' => old('logo') ?? '',
                        'url' => route('gerais.upload-logo'),
                        'placeholder' => 'Klik atau seret logo di sini (maks 2MB, JPG/PNG/SVG)',
                    ])
                    @error('logo')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-4 h-4 rounded border-slate-300 text-primary-acorn focus:ring-primary-acorn/30">
                    <label for="is_active" class="text-[11px] font-bold text-slate-600 dark:text-slate-300">Aktif</label>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="px-8 py-3 bg-primary-acorn hover:bg-primary-acorn/90 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        <iconify-icon icon="lucide:save" class="text-lg inline-block mr-1.5"></iconify-icon>
                        Simpan
                    </button>
                    <a href="{{ route('gerais.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

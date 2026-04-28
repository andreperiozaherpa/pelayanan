@extends('layouts.app')

@php
    $isEdit = isset($citizen);
    $title = $isEdit ? 'Perbarui Data Warga' : 'Tambah Data Warga';
    $subtitle = $isEdit ? "Perbarui Data Induk atau Tinjau Status SKTM untuk NIK: {$citizen->nik}" : 'Registrasi Data Induk dan Status Kemiskinan.';
@endphp

@section('title', $title)

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
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">{{ $isEdit ? "Data: $citizen->nik" : 'Tambah Warga Baru' }}</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">{{ $subtitle }}</p>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="premium-card p-6 sm:p-10 relative overflow-hidden">
        <form action="{{ $isEdit ? route('citizens.update', $citizen->nik) : route('citizens.store') }}" method="POST">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Left Column: Citizen Master Data -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-black/[0.03] dark:border-white/[0.03]">
                        <h3 class="text-sm font-black text-slate-800 dark:text-white flex items-center gap-2 uppercase tracking-widest">
                            <div class="h-6 w-6 rounded-lg bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                                <iconify-icon icon="lucide:user" class="text-sm"></iconify-icon>
                            </div>
                            Data Induk Warga
                        </h3>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-tight">Informasi utama kependudukan yang merujuk pada e-KTP.</p>
                    </div>

                    <div class="space-y-4">
                        <x-input 
                            label="NIK (16 Digit)" 
                            name="nik" 
                            :value="old('nik', $citizen->nik ?? '')" 
                            required 
                            maxlength="16" 
                            pattern="\d{16}"
                            placeholder="Contoh: 3201020304050001" 
                            :readonly="$isEdit"
                        />

                        <x-input 
                            label="Nama Lengkap" 
                            name="nama_lengkap" 
                            :value="old('nama_lengkap', $citizen->nama_lengkap ?? '')" 
                            required 
                            placeholder="NAMA SESUAI KTP" 
                        />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-input 
                                label="Tanggal Lahir" 
                                name="tgl_lahir" 
                                type="date" 
                                :value="old('tgl_lahir', isset($citizen) ? $citizen->tgl_lahir->format('Y-m-d') : '')" 
                                required 
                            />
                            
                            <x-input 
                                label="No. Telepon / WA" 
                                name="kontak" 
                                :value="old('kontak', $citizen->kontak ?? '')" 
                                placeholder="08XXXXXXXXX" 
                            />
                        </div>

                        <x-textarea 
                            label="Alamat Lengkap" 
                            name="alamat_desa" 
                            :value="old('alamat_desa', $citizen->alamat_desa ?? '')" 
                            required 
                            placeholder="JL. CONTOH RT/RW, DUSUN" 
                        />

                        @if(Auth::user()->role->slug === 'superadmin')
                            <x-select label="Wilayah Desa" name="desa_id" required>
                                <option value="">-- PILIH WILAYAH DESA --</option>
                                @foreach($villages as $village)
                                    <option value="{{ $village->id }}" {{ old('desa_id', $citizen->desa_id ?? '') == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                                @endforeach
                            </x-select>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Poverty Record Management -->
                @php
                    $hasRecord = old('has_poverty_record', isset($povertyRecord)) ? 'true' : 'false';
                @endphp
                <div class="space-y-6" x-data="{ hasPovertyData: {{ $hasRecord }} }">
                    <div class="pb-4 border-b border-black/[0.03] dark:border-white/[0.03] flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white flex items-center gap-2 uppercase tracking-widest">
                                <div class="h-6 w-6 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center">
                                    <iconify-icon icon="lucide:file-text" class="text-sm"></iconify-icon>
                                </div>
                                Data Kemiskinan (SKTM)
                            </h3>
                            <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-tight">Status kesejahteraan yang memerlukan bukti lampiran.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="has_poverty_record" x-model="hasPovertyData" class="sr-only peer" value="1">
                            <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-acorn"></div>
                        </label>
                    </div>

                    <div x-show="hasPovertyData" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-4">
                        
                        <div class="p-4 bg-primary-acorn/[0.03] border border-primary-acorn/10 rounded-2xl flex items-start gap-3">
                            <iconify-icon icon="lucide:info" class="text-primary-acorn mt-0.5 shrink-0 text-sm"></iconify-icon>
                            <p class="text-[10px] text-slate-600 dark:text-slate-400 font-bold uppercase tracking-tight leading-relaxed">Sistem secara otomatis akan menghitung masa berlaku SKTM menjadi **3 Bulan** dari Tanggal Penetapan sesuai regulasi.</p>
                        </div>

                        <div class="space-y-4">
                            <x-select label="Status Penetapan" name="poverty_status" x-bind:required="hasPovertyData">
                                <option value="" disabled selected>-- PILIH STATUS --</option>
                                <option value="ACTIVE" {{ old('poverty_status', $povertyRecord->status ?? '') === 'ACTIVE' ? 'selected' : '' }}>AKTIF MEMBUTUHKAN BANTUAN</option>
                                <option value="PENDING_REVIEW" {{ old('poverty_status', $povertyRecord->status ?? '') === 'PENDING_REVIEW' ? 'selected' : '' }}>MENUNGGU TINJAUAN LAPANGAN</option>
                                <option value="EXPIRED" {{ old('poverty_status', $povertyRecord->status ?? '') === 'EXPIRED' ? 'selected' : '' }}>KEDALUWARSA / DICABUT</option>
                            </x-select>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-input 
                                    label="Tgl. Penetapan" 
                                    name="valid_from" 
                                    type="date" 
                                    :value="old('valid_from', isset($povertyRecord) ? $povertyRecord->valid_from->format('Y-m-d') : '')" 
                                    x-bind:required="hasPovertyData" 
                                />
                                
                                <x-select label="Rentang Pendapatan" name="income_range" x-bind:required="hasPovertyData">
                                    <option value="" disabled selected>-- PILIH RENTANG --</option>
                                    <option value="< Rp 500.000" {{ old('income_range', $povertyRecord->income_range ?? '') === '< Rp 500.000' ? 'selected' : '' }}>< RP 500.000 / BLN</option>
                                    <option value="Rp 500.000 - Rp 1.000.000" {{ old('income_range', $povertyRecord->income_range ?? '') === 'Rp 500.000 - Rp 1.000.000' ? 'selected' : '' }}>RP 500.000 - RP 1.000.000</option>
                                    <option value="Rp 1.000.000 - Rp 2.000.000" {{ old('income_range', $povertyRecord->income_range ?? '') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' }}>RP 1.000.000 - RP 2.000.000</option>
                                </x-select>
                            </div>

                            <x-input 
                                label="Sumber Berkas/Data" 
                                name="source" 
                                :value="old('source', $povertyRecord->source ?? 'Verifikasi Lapangan Desa')" 
                                x-bind:required="hasPovertyData" 
                                placeholder="MISAL: SURAT PENGANTAR RT/RW" 
                            />
                        </div>
                    </div>
                    
                    <div x-show="!hasPovertyData" class="flex flex-col items-center justify-center py-20 text-center bg-slate-50 dark:bg-slate-800/30 rounded-[2rem] border border-dashed border-black/[0.05] dark:border-white/[0.05]">
                        <div class="h-12 w-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                            <iconify-icon icon="lucide:info" class="text-slate-300 text-2xl"></iconify-icon>
                        </div>
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pencatatan SKTM Off</h4>
                        <p class="text-[9px] text-slate-400 mt-1 uppercase font-bold tracking-tight max-w-[200px]">Aktifkan toggle untuk input data kesejahteraan warga.</p>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-10 pt-6 border-t border-black/[0.03] dark:border-white/[0.03] flex flex-col sm:flex-row items-center justify-between gap-4">
                @if($isEdit)
                    <p class="text-[9px] text-slate-400 font-black tracking-widest uppercase italic">Terakhir Diperbarui: {{ $citizen->updated_at->diffForHumans() }}</p>
                @else
                    <p class="text-[9px] text-slate-400 font-black tracking-widest uppercase italic">Pastikan data yang diinput sudah sesuai dengan KTP.</p>
                @endif
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('citizens.index') }}" class="px-6 py-2.5 rounded-xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-acorn hover:bg-primary-acorn/90 text-white px-8 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Daftarkan Warga' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

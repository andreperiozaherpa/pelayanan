@csrf

<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    <!-- Left Column: Citizen Master Data -->
    <div class="space-y-6">
        <div class="pb-4 border-b border-black/[0.03] dark:border-white/[0.03]">
            <h3 class="text-sm font-black text-slate-800 dark:text-white flex items-center gap-2 uppercase tracking-widest">
                <div class="h-6 w-6 rounded-lg bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
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
            />

            <x-input 
                label="Nama Lengkap" 
                name="nama_lengkap" 
                :value="old('nama_lengkap', $citizen->nama_lengkap ?? '')" 
                required 
                placeholder="NAMA SESUAI KTP" 
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    Data Kemiskinan (SKTM)
                </h3>
                <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-tight">Status kesejahteraan yang memerlukan bukti lampiran.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="has_poverty_record" x-model="hasPovertyData" class="sr-only peer">
                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-acorn"></div>
            </label>
        </div>

        <div x-show="hasPovertyData" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-4">
            
            <div class="p-4 bg-primary-acorn/[0.03] border border-primary-acorn/10 rounded-2xl flex items-start gap-3">
                <svg class="h-4 w-4 text-primary-acorn mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-[10px] text-slate-600 dark:text-slate-400 font-bold uppercase tracking-tight leading-relaxed">Sistem secara otomatis akan menghitung masa berlaku SKTM menjadi **3 Bulan** dari Tanggal Penetapan sesuai regulasi yang berlaku.</p>
            </div>

            <div class="space-y-4">
                <x-select label="Status Penetapan" name="poverty_status" :required="true" x-bind:required="hasPovertyData">
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
                <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pencatatan SKTM Off</h4>
            <p class="text-[9px] text-slate-400 mt-1 uppercase font-bold tracking-tight max-w-[200px]">Aktifkan toggle untuk input data kesejahteraan warga.</p>
        </div>
    </div>
</div>

@csrf

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Left Column: Citizen Master Data -->
    <div class="space-y-6">
        <div class="pb-4 border-b border-slate-200 dark:border-slate-700/50">
            <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Data Induk Warga
            </h3>
            <p class="text-xs text-slate-500 mt-1">Informasi utama kependudukan yang merujuk pada e-KTP.</p>
        </div>

        <div>
            <label for="nik" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">NIK (16 Digit) <span class="text-rose-500">*</span></label>
            <input type="text" name="nik" id="nik" value="{{ old('nik', $citizen->nik ?? '') }}" required maxlength="16" pattern="\d{16}"
                class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400 dark:text-white"
                placeholder="Misal: 3201020304050001">
            @error('nik') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $citizen->nama_lengkap ?? '') }}" required
                class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400 dark:text-white"
                placeholder="Nama sesuai KTP">
            @error('nama_lengkap') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="tgl_lahir" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Tanggal Lahir <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir', isset($citizen) ? $citizen->tgl_lahir->format('Y-m-d') : '') }}" required
                    class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition dark:text-white">
                @error('tgl_lahir') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="kontak" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">No. Telepon / WA</label>
                <input type="text" name="kontak" id="kontak" value="{{ old('kontak', $citizen->kontak ?? '') }}"
                    class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400 dark:text-white"
                    placeholder="08123456789">
                @error('kontak') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="alamat_desa" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Alamat Lengkap <span class="text-rose-500">*</span></label>
            <textarea name="alamat_desa" id="alamat_desa" rows="3" required
                class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition placeholder-slate-400 dark:text-white"
                placeholder="Jl. Contoh RT/RW, Dusun">{{ old('alamat_desa', $citizen->alamat_desa ?? '') }}</textarea>
            @error('alamat_desa') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
        </div>

        @if(Auth::user()->role->slug === 'superadmin')
            <div>
                <label for="desa_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Pilih Wilayah (Super Admin) <span class="text-rose-500">*</span></label>
                <select name="desa_id" id="desa_id" required
                    class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition dark:text-white">
                    <option value="">-- Pilih Wilayah Desa --</option>
                    @foreach($villages as $village)
                        <option value="{{ $village->id }}" {{ old('desa_id', $citizen->desa_id ?? '') == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                    @endforeach
                </select>
                @error('desa_id') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>
        @endif
    </div>

    <!-- Right Column: Poverty Record Management -->
    @php
        $hasRecord = old('has_poverty_record', isset($povertyRecord)) ? 'true' : 'false';
    @endphp
    <div class="space-y-6" x-data="{ hasPovertyData: {{ $hasRecord }} }">
        <div class="pb-4 border-b border-slate-200 dark:border-slate-700/50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Data Kemiskinan (SKTM)
                </h3>
                <p class="text-xs text-slate-500 mt-1">Status kesejahteraan yang memerlukan bukti lampiran.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="has_poverty_record" x-model="hasPovertyData" class="sr-only peer">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500"></div>
            </label>
        </div>

        <div x-show="hasPovertyData" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6 bg-amber-500/5 dark:bg-amber-500/10 border border-amber-500/20 rounded-2xl p-6">
            
            <div class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-start gap-3">
                <svg class="h-5 w-5 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs text-blue-700 dark:text-blue-300 font-medium">Sistem secara otomatis akan menghitung masa berlaku SKTM menjadi **3 Bulan** dari Tanggal Penetapan (Valid From) sesuai regulasi.</p>
            </div>

            <div>
                <label for="poverty_status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Status Penetapan</label>
                <select name="poverty_status" id="poverty_status" :required="hasPovertyData"
                    class="w-full bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition dark:text-white">
                    <option value="" disabled selected>-- Pilih Status --</option>
                    <option value="ACTIVE" {{ old('poverty_status', $povertyRecord->status ?? '') === 'ACTIVE' ? 'selected' : '' }}>Aktif Membutuhkan Bantuan</option>
                    <option value="PENDING_REVIEW" {{ old('poverty_status', $povertyRecord->status ?? '') === 'PENDING_REVIEW' ? 'selected' : '' }}>Menunggu Tinjauan Lapangan</option>
                    <option value="EXPIRED" {{ old('poverty_status', $povertyRecord->status ?? '') === 'EXPIRED' ? 'selected' : '' }}>Kedaluwarsa / Dicabut</option>
                </select>
                @error('poverty_status') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="valid_from" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Tgl. Penetapan</label>
                    <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from', isset($povertyRecord) ? $povertyRecord->valid_from->format('Y-m-d') : '') }}" :required="hasPovertyData"
                        class="w-full bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition dark:text-white">
                    @error('valid_from') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="income_range" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Rentang Pendapatan</label>
                    <select name="income_range" id="income_range" :required="hasPovertyData"
                        class="w-full bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition dark:text-white">
                        <option value="" disabled selected>-- Pilih Rentang --</option>
                        <option value="< Rp 500.000" {{ old('income_range', $povertyRecord->income_range ?? '') === '< Rp 500.000' ? 'selected' : '' }}>< Rp 500.000 / bln</option>
                        <option value="Rp 500.000 - Rp 1.000.000" {{ old('income_range', $povertyRecord->income_range ?? '') === 'Rp 500.000 - Rp 1.000.000' ? 'selected' : '' }}>Rp 500.000 - Rp 1.000.000</option>
                        <option value="Rp 1.000.000 - Rp 2.000.000" {{ old('income_range', $povertyRecord->income_range ?? '') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' }}>Rp 1.000.000 - Rp 2.000.000</option>
                    </select>
                    @error('income_range') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="source" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Sumber Berkas/Data</label>
                <input type="text" name="source" id="source" value="{{ old('source', $povertyRecord->source ?? 'Verifikasi Lapangan Desa') }}" :required="hasPovertyData"
                    class="w-full bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition placeholder-slate-400 dark:text-white"
                    placeholder="Contoh: Surat Pengantar RT/RW 01, Sistem Desa">
                @error('source') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>
            
        </div>
        
        <div x-show="!hasPovertyData" class="flex flex-col items-center justify-center py-12 text-center bg-slate-100/50 dark:bg-slate-800/30 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
            <svg class="h-10 w-10 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14h2v2h-2v-2zm0-7h2v5h-2V7zm-6 2A9 9 0 1120.5 4M4 10a9 9 0 1016.5 6" />
            </svg>
            <h4 class="text-sm font-bold text-slate-600 dark:text-slate-400">Pencatatan Kemiskinan Dinonaktifkan</h4>
            <p class="text-xs text-slate-500 mt-1 max-w-[250px]">Aktifkan toggle di atas jika warga ini juga membutuhkan pengajuan / pembaruan SKTM.</p>
        </div>
    </div>
</div>

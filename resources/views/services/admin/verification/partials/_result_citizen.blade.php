<!-- CITIZEN RESULT -->
<template x-if="resultType === 'CITIZEN'">
    <div class="space-y-6">
        <!-- Navigation Back -->
        <div x-show="lastHouseholdResult" x-transition>
            <button @click="backToHousehold()"
                class="flex items-center gap-2 text-[10px] font-black text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">
                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                Kembali ke Daftar Keluarga (No. KK: <span x-text="lastHouseholdResult?.household?.no_kk || ''"></span>)
            </button>
        </div>

        <!-- Premium Result Card -->
        <div class="premium-card w-full overflow-hidden">
            <div class="p-8 sm:p-12">
                <!-- Main Flex Layout -->
                <div class="flex flex-col md:flex-row gap-12">

                    <!-- Left Section: Profile & Identity -->
                    <div class="w-full md:w-[60%] space-y-10">
                        <div class="flex flex-col sm:flex-row gap-8 items-start">
                            <!-- Profile/Avatar -->
                            <div class="flex-shrink-0">
                                <div
                                    class="h-24 w-24 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] flex items-center justify-center text-4xl shadow-sm">
                                    👤
                                </div>
                            </div>

                            <!-- Name & Status Row -->
                            <div class="flex-grow min-w-0">
                                <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase truncate mb-4"
                                    x-text="result?.citizen ? result.citizen.nama_lengkap : nik"></h2>

                                <div class="flex flex-wrap items-center gap-3 mb-6">
                                    <!-- SKTM Badge -->
                                    <template x-if="result?.poverty_status">
                                        <div x-cloak
                                            :class="{
                                                'bg-emerald-500 shadow-emerald-500/20': result
                                                    .poverty_status === 'ACTIVE',
                                                'bg-rose-500 shadow-rose-500/20': result
                                                    .poverty_status === 'EXPIRED' || result
                                                    .poverty_status === 'REJECTED',
                                                'bg-amber-500 shadow-amber-500/20': result
                                                    .poverty_status === 'PENDING',
                                                'bg-slate-500 shadow-slate-500/20': result
                                                    .poverty_status === 'UNREGISTERED'
                                            }"
                                            class="px-4 py-1.5 rounded-xl text-[8px] font-black text-white shadow-lg tracking-widest uppercase shrink-0 flex items-center gap-2">
                                            <iconify-icon
                                                :icon="result.poverty_status === 'ACTIVE' ? 'lucide:shield-check' : (result
                                                    .poverty_status === 'PENDING' ? 'lucide:hourglass' :
                                                    'lucide:alert-circle')"></iconify-icon>
                                            <span class="font-black"
                                                x-text="'SKTM: ' + result.poverty_status.replace('_', ' ')"></span>
                                        </div>
                                    </template>

                                    <!-- SKD Badge -->
                                    <template x-if="result?.domicile_status">
                                        <div x-cloak
                                            :class="{
                                                'bg-emerald-500 shadow-emerald-500/20': result
                                                    .domicile_status === 'ACTIVE',
                                                'bg-rose-500 shadow-rose-500/20': result
                                                    .domicile_status === 'EXPIRED' || result
                                                    .domicile_status === 'REJECTED',
                                                'bg-amber-500 shadow-amber-500/20': result
                                                    .domicile_status === 'PENDING',
                                                'bg-slate-500 shadow-slate-500/20': result
                                                    .domicile_status === 'UNREGISTERED'
                                            }"
                                            class="px-4 py-1.5 rounded-xl text-[8px] font-black text-white shadow-lg tracking-widest uppercase shrink-0 flex items-center gap-2">
                                            <iconify-icon
                                                :icon="result.domicile_status === 'ACTIVE' ? 'lucide:shield-check' : (result
                                                    .domicile_status === 'PENDING' ? 'lucide:hourglass' :
                                                    'lucide:alert-circle')"></iconify-icon>
                                            <span class="font-black"
                                                x-text="'SKD: ' + result.domicile_status.replace('_', ' ')"></span>
                                        </div>
                                    </template>
                                    <!-- PINDAH Badge -->
                                    <template x-if="result?.move_status">
                                        <div x-cloak
                                            :class="{
                                                'bg-emerald-500 shadow-emerald-500/20': result
                                                    .move_status === 'ACTIVE',
                                                'bg-rose-500 shadow-rose-500/20': result.move_status === 'EXPIRED' ||
                                                    result.move_status === 'REJECTED',
                                                'bg-amber-500 shadow-amber-500/20': result
                                                    .move_status === 'PENDING',
                                                'bg-slate-500 shadow-slate-500/20': result
                                                    .move_status === 'UNREGISTERED'
                                            }"
                                            class="px-4 py-1.5 rounded-xl text-[8px] font-black text-white shadow-lg tracking-widest uppercase shrink-0 flex items-center gap-2">
                                            <iconify-icon
                                                :icon="result.move_status === 'ACTIVE' ? 'lucide:truck' : (result
                                                    .move_status === 'PENDING' ? 'lucide:hourglass' :
                                                    'lucide:alert-circle')"></iconify-icon>
                                            <span class="font-black"
                                                x-text="'PINDAH: ' + result.move_status.replace('_', ' ')"></span>
                                        </div>
                                    </template>
                                    <!-- DEATH Badge -->
                                    <template x-if="result?.death_status">
                                        <div x-cloak
                                            :class="{
                                                'bg-emerald-500 shadow-emerald-500/20': result
                                                    .death_status === 'ACTIVE',
                                                'bg-rose-500 shadow-rose-500/20': result.death_status === 'REJECTED',
                                                'bg-amber-500 shadow-amber-500/20': result
                                                    .death_status === 'PENDING',
                                                'bg-slate-500 shadow-slate-500/20': result
                                                    .death_status === 'UNREGISTERED'
                                            }"
                                            class="px-4 py-1.5 rounded-xl text-[8px] font-black text-white shadow-lg tracking-widest uppercase shrink-0 flex items-center gap-2">
                                            <iconify-icon
                                                :icon="result.death_status === 'ACTIVE' ? 'lucide:heart-off' : (result
                                                    .death_status === 'PENDING' ? 'lucide:hourglass' :
                                                    'lucide:alert-circle')"></iconify-icon>
                                            <span class="font-black"
                                                x-text="'KEMATIAN: ' + result.death_status.replace('_', ' ')"></span>
                                        </div>
                                    </template>

                                </div>

                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor
                                        Induk Kependudukan</p>
                                    <p class="text-lg font-black text-slate-600 dark:text-slate-300 tracking-wider"
                                        x-text="nik"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Identity Info Grid -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-10 border-t border-black/[0.03] dark:border-white/[0.03]">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status
                                    Kesejahteraan</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase leading-relaxed"
                                    x-text="result?.poverty_message"></p>
                            </div>
                            <div x-show="result?.domicile_message">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status
                                    Domisili</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase leading-relaxed"
                                    x-text="result?.domicile_message"></p>
                            </div>
                            <div x-show="result?.poverty_record">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Masa
                                    Berlaku Kemiskinan</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase"
                                    x-text="result?.poverty_record && result.poverty_record.valid_until_formatted ? 'HINGGA ' + result.poverty_record.valid_until_formatted : 'TIDAK TERBATAS'">
                                </p>
                            </div>
                            <div x-show="result?.domicile_record">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Masa
                                    Berlaku Domisili</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase"
                                    x-text="result?.domicile_record && result.domicile_record.valid_until_formatted ? 'HINGGA ' + result.domicile_record.valid_until_formatted : 'TIDAK TERBATAS'">
                                </p>
                            </div>
                            <div x-show="result?.move_message">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status
                                    Kepindahan</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase leading-relaxed"
                                    x-text="result?.move_message"></p>
                            </div>
                            <div x-show="result?.death_message">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status
                                    Kematian</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase leading-relaxed"
                                    x-text="result?.death_message"></p>
                            </div>
                            <div x-show="result?.move_record">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Masa
                                    Berlaku Pindah</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase"
                                    x-text="result?.move_record && result.move_record.valid_until_formatted ? 'HINGGA ' + result.move_record.valid_until_formatted : 'TIDAK TERBATAS'">
                                </p>
                            </div>
                            <div x-show="result?.citizen">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Wilayah
                                    Desa</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase"
                                    x-text="result?.citizen?.village ? (typeof result.citizen.village === 'object' ? result.citizen.village.name : result.citizen.village) : 'DESA ID: ' + (result?.citizen?.desa_id || '-')">
                                </p>
                            </div>
                            <div x-show="result?.citizen">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat
                                    Domisili</p>
                                <p class="text-sm font-black text-slate-700 dark:text-white uppercase"
                                    x-text="result?.citizen?.alamat_desa"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Document List -->
                    <div
                        class="w-full md:w-[40%] md:pl-12 md:border-l border-black/[0.03] dark:border-white/[0.03] space-y-6">
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <iconify-icon icon="lucide:files" class="text-primary-acorn text-lg"></iconify-icon>
                            Daftar Surat / Dokumen Tersedia
                        </h3>

                        <div class="flex flex-col gap-4">
                            <!-- Poverty Proof -->
                            <template x-if="result?.poverty_status === 'ACTIVE'">
                                <a :href="'/proof/' + nik + '/poverty'" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-black/[0.03] hover:border-primary-acorn/30 hover:bg-white dark:hover:bg-slate-800 transition-all group shadow-sm">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="lucide:shield-check"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-800 dark:text-white uppercase truncate">
                                            Bukti Surat Keterangan Miskin</p>
                                        <p class="text-[9px] text-emerald-500 font-bold uppercase truncate">
                                            Terverifikasi Aktif</p>
                                    </div>
                                    <iconify-icon icon="lucide:chevron-right"
                                        class="ml-auto text-slate-300 group-hover:text-primary-acorn transition-colors"></iconify-icon>
                                </a>
                            </template>

                            <template x-if="result?.poverty_status === 'EXPIRED'">
                                <div class="flex flex-col gap-4">
                                    <div @click="showDocAlert('EXPIRED')"
                                        class="flex items-center gap-4 p-4 rounded-2xl bg-rose-50/50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/30 opacity-80 cursor-pointer hover:bg-rose-100/50 transition-all">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-800/50 text-rose-500 flex items-center justify-center text-xl shrink-0">
                                            <iconify-icon icon="lucide:file-warning"></iconify-icon>
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                                Bukti SKTM</p>
                                            <p class="text-[9px] text-rose-500 font-bold uppercase truncate"
                                                x-text="result?.poverty_message.includes('SLA') ? 'Gagal Verifikasi (SLA)' : 'Status Kadaluarsa'">
                                            </p>
                                        </div>
                                    </div>
                                    <div x-show="result?.poverty_message.includes('SLA')"
                                        class="p-4 rounded-xl bg-orange-50 dark:bg-orange-950/20 border border-orange-200 dark:border-orange-900/30 text-[9px] text-orange-800 dark:text-orange-300 font-medium leading-relaxed">
                                        Desa tidak merespons dalam waktu 24 jam. Dokumen tidak dapat diterbitkan.
                                        Silakan klik tombol di bawah untuk mengajukan ulang.
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.poverty_status === 'REJECTED'">
                                <div @click="showDocAlert('REJECTED')"
                                    class="flex flex-col gap-4 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 shadow-sm shadow-rose-500/5 cursor-pointer hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-500/20 text-rose-600 flex items-center justify-center text-xl shrink-0">
                                            <iconify-icon icon="lucide:file-x"></iconify-icon>
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                                Bukti Surat Keterangan Miskin</p>
                                            <p class="text-[9px] text-rose-600 font-bold uppercase truncate">
                                                Verifikasi Ditolak</p>
                                        </div>
                                    </div>
                                    <div x-show="result?.poverty_rejection_reason"
                                        class="mt-2 p-3 rounded-xl bg-white/50 dark:bg-rose-900/40 border border-rose-100 dark:border-rose-800/50">
                                        <p
                                            class="text-[10px] font-black text-rose-800 dark:text-rose-300 uppercase tracking-widest mb-1">
                                            Alasan Penolakan:</p>
                                        <p class="text-[11px] text-rose-700 dark:text-rose-400 font-medium italic leading-relaxed"
                                            x-text="result.poverty_rejection_reason"></p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.poverty_status === 'PENDING'">
                                <div @click="showDocAlert('PENDING')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 opacity-80 cursor-pointer hover:bg-amber-100/50 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 text-amber-500 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:hourglass"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                            Bukti Surat Keterangan Miskin</p>
                                        <p class="text-[9px] text-amber-500 font-bold uppercase truncate">
                                            Menunggu Verifikasi</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.poverty_status === 'UNREGISTERED'">
                                <div @click="showDocAlert('UNREGISTERED')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/20 border border-black/[0.03] dark:border-white/[0.03] opacity-60 grayscale cursor-pointer hover:grayscale-0 hover:opacity-100 hover:bg-white dark:hover:bg-slate-800 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:file-x-2"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase truncate">
                                            Bukti Surat Keterangan Miskin</p>
                                        <p class="text-[9px] text-slate-400 font-medium uppercase truncate">
                                            Belum Ada Dokumen</p>
                                    </div>
                                </div>
                            </template>

                            <!-- Domicile Record Status Flow -->
                            <template x-if="result?.domicile_status === 'ACTIVE'">
                                <a :href="'/proof/' + nik + '/domicile'" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-black/[0.03] hover:border-primary-acorn/30 hover:bg-white dark:hover:bg-slate-800 transition-all group shadow-sm">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="lucide:shield-check"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-800 dark:text-white uppercase truncate">
                                            Bukti Surat Keterangan Domisili</p>
                                        <p class="text-[9px] text-emerald-500 font-bold uppercase truncate">
                                            Terverifikasi Aktif</p>
                                    </div>
                                    <iconify-icon icon="lucide:chevron-right"
                                        class="ml-auto text-slate-300 group-hover:text-primary-acorn transition-colors"></iconify-icon>
                                </a>
                            </template>

                            <template x-if="result?.domicile_status === 'PENDING'">
                                <div @click="showDocAlert('PENDING')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 cursor-pointer hover:bg-amber-100/50 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 text-amber-500 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:hourglass"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                            Bukti Surat Keterangan Domisili</p>
                                        <p class="text-[9px] text-amber-500 font-bold uppercase truncate">
                                            Menunggu Verifikasi</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.domicile_status === 'EXPIRED'">
                                <div @click="showDocAlert('EXPIRED')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-rose-50/50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/30 cursor-pointer hover:bg-rose-100/50 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-800/50 text-rose-500 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:file-warning"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                            Bukti Surat Keterangan Domisili</p>
                                        <p class="text-[9px] text-rose-500 font-bold uppercase truncate">
                                            Status Kadaluarsa</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.domicile_status === 'REJECTED'">
                                <div @click="showDocAlert('REJECTED')"
                                    class="flex flex-col gap-4 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 shadow-sm shadow-rose-500/5 cursor-pointer hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-500/20 text-rose-600 flex items-center justify-center text-xl shrink-0">
                                            <iconify-icon icon="lucide:file-x"></iconify-icon>
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                                Bukti Surat Keterangan Domisili</p>
                                            <p class="text-[9px] text-rose-600 font-bold uppercase truncate">
                                                Verifikasi Ditolak</p>
                                        </div>
                                    </div>
                                    <div x-show="result?.domicile_rejection_reason"
                                        class="mt-2 p-3 rounded-xl bg-white/50 dark:bg-rose-900/40 border border-rose-100 dark:border-rose-800/50">
                                        <p
                                            class="text-[10px] font-black text-rose-800 dark:text-rose-300 uppercase tracking-widest mb-1">
                                            Alasan Penolakan:</p>
                                        <p class="text-[11px] text-rose-700 dark:text-rose-400 font-medium italic leading-relaxed"
                                            x-text="result.domicile_rejection_reason"></p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.domicile_status === 'UNREGISTERED'">
                                <div @click="showDocAlert('UNREGISTERED')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/20 border border-black/[0.03] dark:border-white/[0.03] opacity-60 grayscale cursor-pointer hover:grayscale-0 hover:opacity-100 hover:bg-white dark:hover:bg-slate-800 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:file-x-2"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase truncate">
                                            Bukti SKD</p>
                                        <p class="text-[9px] text-slate-400 font-medium uppercase truncate">
                                            Belum Ada Dokumen</p>
                                    </div>
                                </div>
                            </template>

                            <!-- Move Record Status Flow -->
                            <template x-if="result?.move_status === 'ACTIVE'">
                                <a :href="'/proof/' + nik + '/move'" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-black/[0.03] hover:border-primary-acorn/30 hover:bg-white dark:hover:bg-slate-800 transition-all group shadow-sm">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="lucide:shield-check"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-800 dark:text-white uppercase truncate">
                                            Bukti Surat Pengantar Pindah</p>
                                        <p class="text-[9px] text-emerald-500 font-bold uppercase truncate">
                                            Terverifikasi Aktif</p>
                                    </div>
                                    <iconify-icon icon="lucide:chevron-right"
                                        class="ml-auto text-slate-300 group-hover:text-primary-acorn transition-colors"></iconify-icon>
                                </a>
                            </template>

                            <template x-if="result?.move_status === 'PENDING'">
                                <div @click="showDocAlert('PENDING')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 cursor-pointer hover:bg-amber-100/50 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 text-amber-500 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:hourglass"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                            Bukti Surat Pengantar Pindah</p>
                                        <p class="text-[9px] text-amber-500 font-bold uppercase truncate">
                                            Menunggu Verifikasi</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.move_status === 'EXPIRED'">
                                <div @click="showDocAlert('EXPIRED')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-rose-50/50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/30 cursor-pointer hover:bg-rose-100/50 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-800/50 text-rose-500 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:file-warning"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                            Bukti Surat Pengantar Pindah</p>
                                        <p class="text-[9px] text-rose-500 font-bold uppercase truncate">
                                            Status Kadaluarsa</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.move_status === 'REJECTED'">
                                <div @click="showDocAlert('REJECTED')"
                                    class="flex flex-col gap-4 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 shadow-sm shadow-rose-500/5 cursor-pointer hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-500/20 text-rose-600 flex items-center justify-center text-xl shrink-0">
                                            <iconify-icon icon="lucide:file-x"></iconify-icon>
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                                Bukti Surat Pengantar Pindah</p>
                                            <p class="text-[9px] text-rose-600 font-bold uppercase truncate">
                                                Verifikasi Ditolak</p>
                                        </div>
                                    </div>
                                    <div x-show="result?.move_rejection_reason"
                                        class="mt-2 p-3 rounded-xl bg-white/50 dark:bg-rose-900/40 border border-rose-100 dark:border-rose-800/50">
                                        <p
                                            class="text-[10px] font-black text-rose-800 dark:text-rose-300 uppercase tracking-widest mb-1">
                                            Alasan Penolakan:</p>
                                        <p class="text-[11px] text-rose-700 dark:text-rose-400 font-medium italic leading-relaxed"
                                            x-text="result.move_rejection_reason"></p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.move_status === 'UNREGISTERED'">
                                <div @click="showDocAlert('UNREGISTERED')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/20 border border-black/[0.03] dark:border-white/[0.03] opacity-60 grayscale cursor-pointer hover:grayscale-0 hover:opacity-100 hover:bg-white dark:hover:bg-slate-800 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:file-x-2"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase truncate">
                                            Bukti Pengantar Pindah</p>
                                        <p class="text-[9px] text-slate-400 font-medium uppercase truncate">
                                            Belum Ada Dokumen</p>
                                    </div>
                                </div>
                            </template>



                            <!-- Death Record Status Flow -->
                            <template x-if="result?.death_status === 'ACTIVE'">
                                <a :href="'/proof/' + nik + '/death'" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-black/[0.03] hover:border-primary-acorn/30 hover:bg-white dark:hover:bg-slate-800 transition-all group shadow-sm">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                                        <iconify-icon icon="lucide:shield-check"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-800 dark:text-white uppercase truncate">
                                            Bukti Surat Keterangan Kematian</p>
                                        <p class="text-[9px] text-emerald-500 font-bold uppercase truncate">
                                            Terverifikasi Aktif</p>
                                    </div>
                                    <iconify-icon icon="lucide:chevron-right"
                                        class="ml-auto text-slate-300 group-hover:text-primary-acorn transition-colors"></iconify-icon>
                                </a>
                            </template>

                            <template x-if="result?.death_status === 'PENDING'">
                                <div @click="showDocAlert('PENDING')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 cursor-pointer hover:bg-amber-100/50 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 text-amber-500 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:hourglass"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                            Bukti Surat Keterangan Kematian</p>
                                        <p class="text-[9px] text-amber-500 font-bold uppercase truncate">
                                            Menunggu Verifikasi</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.death_status === 'REJECTED'">
                                <div @click="showDocAlert('REJECTED')"
                                    class="flex flex-col gap-4 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 shadow-sm shadow-rose-500/5 cursor-pointer hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-500/20 text-rose-600 flex items-center justify-center text-xl shrink-0">
                                            <iconify-icon icon="lucide:file-x"></iconify-icon>
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase truncate">
                                                Bukti Surat Keterangan Kematian</p>
                                            <p class="text-[9px] text-rose-600 font-bold uppercase truncate">
                                                Verifikasi Ditolak</p>
                                        </div>
                                    </div>
                                    <div x-show="result?.death_rejection_reason"
                                        class="mt-2 p-3 rounded-xl bg-white/50 dark:bg-rose-900/40 border border-rose-100 dark:border-rose-800/50">
                                        <p
                                            class="text-[10px] font-black text-rose-800 dark:text-rose-300 uppercase tracking-widest mb-1">
                                            Alasan Penolakan:</p>
                                        <p class="text-[11px] text-rose-700 dark:text-rose-400 font-medium italic leading-relaxed"
                                            x-text="result.death_rejection_reason"></p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="result?.death_status === 'UNREGISTERED'">
                                <div @click="showDocAlert('UNREGISTERED')"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/20 border border-black/[0.03] dark:border-white/[0.03] opacity-60 grayscale cursor-pointer hover:grayscale-0 hover:opacity-100 hover:bg-white dark:hover:bg-slate-800 transition-all">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-xl shrink-0">
                                        <iconify-icon icon="lucide:file-x-2"></iconify-icon>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase truncate">
                                            Surat Kematian</p>
                                        <p class="text-[9px] text-slate-400 font-medium uppercase truncate">
                                            Belum Ada Pelaporan</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Actions Bar (Report Service) -->
        <div class="flex flex-wrap justify-center gap-4"
            x-show="((result?.poverty_status && result.poverty_status !== 'CITIZEN_NOT_FOUND') || (result?.domicile_status && result.domicile_status !== 'CITIZEN_NOT_FOUND')) && !reported && result?.death_status !== 'ACTIVE'">
            @can('service.report')
                <button @click="showReportModal = true"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-10 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    <iconify-icon icon="lucide:send" class="text-lg opacity-50"></iconify-icon>
                    <span>Ajukan / Perbarui Verifikasi</span>
                </button>
            @endcan
        </div>

    </div>
</template>

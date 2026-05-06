<!-- CITIZEN RESULT -->
<template x-if="resultType === 'CITIZEN'">
    <div class="space-y-6">
        <!-- Navigation Back -->
        <div x-show="lastHouseholdResult" x-transition>
            <button @click="backToHousehold()"
                class="flex items-center gap-2 text-[10px] font-black text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">
                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                Kembali ke Daftar Keluarga (No. KK: <span x-text="lastHouseholdResult.household.no_kk"></span>)
            </button>
        </div>

        <!-- Premium Result Card -->
        <div class="premium-card overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex flex-col md:flex-row gap-10 items-start">
                    <!-- Profile/Avatar -->
                    <div class="flex-shrink-0">
                        <div
                            class="h-28 w-28 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] flex items-center justify-center text-4xl shadow-sm">
                            👤
                        </div>
                    </div>

                    <!-- Data Body -->
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center justify-between gap-4">
                            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase truncate"
                                x-text="result.citizen ? result.citizen.nama_lengkap : nik"></h2>
                            <span x-show="result.status"
                                :class="{
                                    'bg-emerald-500 shadow-emerald-500/20': result.status === 'ACTIVE',
                                    'bg-rose-500 shadow-rose-500/20': result.status === 'EXPIRED',
                                    'bg-amber-500 shadow-amber-500/20': result.status === 'PENDING_REVIEW'
                                }"
                                class="px-5 py-2 rounded-xl text-[9px] font-black text-white shadow-lg tracking-widest uppercase shrink-0"
                                x-text="result.status">
                            </span>
                        </div>

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                    Status Kesejahteraan</p>
                                <p class="text-lg font-black text-slate-700 dark:text-white leading-tight uppercase tracking-tight"
                                    x-text="result.message"></p>
                            </div>

                            <div x-show="result.record">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                    Masa Berlaku</p>
                                <p class="text-base font-black text-slate-600 dark:text-slate-300 uppercase tracking-tight"
                                    x-text="result.record && result.record.valid_until_formatted ? 'HINGGA ' + result.record.valid_until_formatted : 'TIDAK TERBATAS'">
                                </p>
                            </div>
                        </div>

                        <!-- Identity Details -->
                        <div
                            class="mt-8 pt-8 border-t border-black/[0.03] dark:border-white/[0.03] grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div x-show="result.citizen">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                    Wilayah Desa</p>
                                <p class="text-sm font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight"
                                    x-text="result.citizen.village ? (typeof result.citizen.village === 'object' ? result.citizen.village.name : result.citizen.village) : 'IDENTITAS DESA: ' + result.citizen.desa_id">
                                </p>
                            </div>
                            <div x-show="result.citizen">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                    Alamat Domisili</p>
                                <p class="text-sm font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight"
                                    x-text="result.citizen.alamat_desa"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="flex flex-wrap justify-center gap-4"
            x-show="(result.status === 'ACTIVE' || result.status === 'PENDING_REVIEW' || result.status === 'EXPIRED') && !reported">
            @can('service.report')
                <button @click="showReportModal = true"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-10 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1 active:scale-95">
                    Lapor Pelayanan Diberikan
                </button>
            @endcan

            @can('poverty.print_proof')
                <template x-if="result.status === 'ACTIVE'">
                    <a :href="'/proof/' + nik" target="_blank"
                        class="bg-white hover:bg-slate-50 text-slate-800 px-10 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl border border-black/[0.03] transition-all hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                        <iconify-icon icon="lucide:printer" class="text-xl text-primary-acorn"></iconify-icon>
                        Cetak Bukti Verifikasi
                    </a>
                </template>
            @endcan
        </div>
    </div>
</template>

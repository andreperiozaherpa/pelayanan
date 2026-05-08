<!-- Report Modal -->
<div x-show="showReportModal" @keydown.window.escape="showReportModal = false"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <!-- Backdrop with Deep Blur -->
    <div x-show="showReportModal" x-transition:enter="ease-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-md w-full h-full" @click.stop="showReportModal = false">
    </div>

    <!-- Modal Content (Centered) -->
    <div x-show="showReportModal" x-transition:enter="ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-300"
        x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" id="report-modal-content"
        class="bg-white dark:bg-slate-900 rounded-[2.5rem] w-full max-w-lg shadow-2xl relative border border-black/[0.03] dark:border-white/[0.05] z-10 flex flex-col max-h-[90vh] overflow-visible">

        <!-- Close Button -->
        <button @click.stop="showReportModal = false"
            class="absolute top-8 right-8 h-10 w-10 flex items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-rose-500 transition-all hover:rotate-90 z-50">
            <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
        </button>

        <!-- Header (Fixed) -->
        <div
            class="p-10 pb-6 relative overflow-hidden text-center border-b border-black/[0.03] dark:border-white/[0.03]">
            <div
                class="h-16 w-16 rounded-2xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center text-2xl mx-auto shadow-inner mb-6">
                <iconify-icon icon="lucide:clipboard-check"></iconify-icon>
            </div>
            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                Permohonan Verifikasi Dokumen
            </h3>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                Teruskan ke Operator Desa
            </p>

            <!-- Citizen Context -->
            <div
                class="mt-8 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-3xl border border-black/[0.03] dark:border-white/[0.03] flex items-center gap-4 text-left">
                <div
                    class="h-10 w-10 rounded-xl bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] flex items-center justify-center text-lg shadow-sm">
                    👤
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-black text-slate-800 dark:text-white uppercase truncate"
                        x-text="result?.citizen?.nama_lengkap || nik"></p>
                    <p class="text-[9px] text-slate-400 font-bold tracking-wider" x-text="nik"></p>
                </div>
            </div>
        </div>

        <!-- Scrollable Body -->
        <div class="flex-grow overflow-y-auto p-10 space-y-8 custom-scrollbar min-h-0">
            <!-- Service Type -->
            <div class="space-y-3">
                <label
                    class="flex items-center gap-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">
                    <iconify-icon icon="lucide:layout-grid" class="text-primary-acorn"></iconify-icon>
                    Pilih Jenis Pelayanan
                </label>
                <div class="relative group">
                    <select id="service-type-select" class="w-full">
                        <option value="">-- PILIH JENIS PELAYANAN --</option>
                        <option value="KETERANGAN KEMISKINAN">KETERANGAN KEMISKINAN (SKTM)</option>
                        <option value="PENGANTAR PINDAH">PENGANTAR PINDAH (KELUAR WILAYAH)</option>
                        <option value="LAPOR DATANG">LAPOR DATANG (MASUK WILAYAH)</option>
                        <option value="KETERANGAN DOMISILI">KETERANGAN DOMISILI (SKD)</option>
                        <option value="SURAT KEMATIAN">SURAT KEMATIAN (PELAPORAN)</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-3">
                <label
                    class="flex items-center gap-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1">
                    <iconify-icon icon="lucide:message-square" class="text-primary-acorn"></iconify-icon>
                    Catatan Tambahan
                </label>
                <textarea x-model="report.notes"
                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[13px] font-medium outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition-all placeholder-slate-400 dark:placeholder-slate-500 min-h-[120px]"
                    placeholder="Tuliskan keterangan hasil verifikasi..."></textarea>
            </div>

            <button @click="submitReport()" :disabled="!report.service_type || loading"
                class="relative group w-full overflow-hidden rounded-2xl shadow-xl shadow-primary-acorn/20">
                <div
                    class="absolute inset-0 bg-primary-acorn transition-transform group-hover:scale-105 group-active:scale-95 duration-300">
                </div>
                <div class="relative py-5 flex items-center justify-center gap-3">
                    <iconify-icon x-show="!loading" icon="lucide:send" class="text-lg text-white/50"></iconify-icon>
                    <div x-show="loading"
                        class="h-5 w-5 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>
                    <span class="text-white font-black text-[11px] uppercase tracking-[0.2em]">Kirim Permohonan</span>
                </div>
            </button>
        </div>
    </div>
</div>

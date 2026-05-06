<!-- Report Modal -->
<div x-show="showReportModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-cloak>
    <div x-show="showReportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div x-show="showReportModal" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="premium-card w-full max-w-lg p-10 relative" @click.away="showReportModal = false">
        <button @click="showReportModal = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
        </button>

        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-8 uppercase tracking-tight">Lapor
            Pelayanan</h3>
        <div class="space-y-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">
                    Jenis Layanan</label>
                <select x-model="report.service_type"
                    class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition uppercase tracking-wider">
                    <option value="">PILIH LAYANAN...</option>
                    <option value="BANTUAN PANGAN">BANTUAN PANGAN</option>
                    <option value="BANTUAN PENDIDIKAN (KIP)">BANTUAN PENDIDIKAN (KIP)</option>
                    <option value="JAMINAN KESEHATAN (PBI)">JAMINAN KESEHATAN (PBI)</option>
                    <option value="SEMBAKO/BPNT">SEMBAKO/BPNT</option>
                    <option value="LAINNYA">LAINNYA</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">
                    Catatan Tambahan</label>
                <textarea x-model="report.notes"
                    class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition uppercase tracking-wider"
                    rows="3" placeholder="OPSIONAL..."></textarea>
            </div>

            <div class="pt-4">
                <button @click="submitReport()" :disabled="!report.service_type || loading"
                    class="w-full bg-primary-acorn hover:bg-primary-acorn/90 disabled:opacity-50 text-white py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl transition-all active:scale-95">
                    Kirim Laporan Pelayanan
                </button>
            </div>
        </div>
    </div>
</div>

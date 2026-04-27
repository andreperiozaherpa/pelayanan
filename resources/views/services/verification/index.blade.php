@extends('layouts.app')

@section('title', 'Verifikasi Nomor Induk')

@section('content')
    <div class="space-y-8" x-data="verificationApp()">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    Verifikasi Data Penduduk
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                    Gunakan Nomor Induk Kependudukan atau Pindai Kode QR untuk validasi status kemiskinan secara real-time.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button @click="toggleScanner()"
                    class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm flex items-center justify-center"
                    title="Pindai Kode QR">
                    <iconify-icon icon="lucide:qr-code" class="text-xl"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- Search Interface -->
        <div class="premium-card p-6 sm:p-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <iconify-icon icon="lucide:search" class="text-lg"></iconify-icon>
                    </div>
                    <input type="text" x-model="nik" @keyup.enter="verifyNik('NIK')"
                        class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition uppercase tracking-wider"
                        placeholder="MASUKKAN 16 DIGIT NOMOR INDUK KEPENDUDUKAN..." maxlength="16">
                </div>
                <button @click="verifyNik('NIK')" :disabled="loading || nik.length < 16"
                    class="bg-primary-acorn hover:bg-primary-acorn/90 disabled:opacity-50 disabled:cursor-not-allowed text-white px-10 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5 active:scale-95">
                    <span x-show="!loading">Verifikasi Data</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>

            <!-- Scanner Container -->
            <div x-show="showScanner" x-transition
                class="mt-6 border-2 border-dashed border-black/[0.03] dark:border-white/[0.03] rounded-3xl overflow-hidden bg-slate-50 dark:bg-black/20">
                <div id="qr-reader" class="w-full"></div>
                <div class="p-4 bg-white/80 dark:bg-slate-900/80 text-center border-t border-black/[0.03]">
                    <button @click="stopScanner()"
                        class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:opacity-80 transition">Tutup
                        Kamera Pemindai</button>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div x-show="error" x-transition
            class="premium-card p-6 bg-rose-50/50 dark:bg-rose-500/10 border-rose-100 dark:border-rose-500/20 flex items-center gap-4">
            <div class="p-2.5 bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-500/20">
                <iconify-icon icon="lucide:alert-circle" class="text-xl"></iconify-icon>
            </div>
            <div>
                <p class="text-[11px] font-black text-rose-600 uppercase tracking-tight" x-text="error"></p>
                <p class="text-[10px] text-rose-500/70 font-medium uppercase tracking-tight">Silakan periksa kembali Nomor
                    Induk atau kualitas gambar Kode QR.</p>
            </div>
        </div>

        <!-- Result State -->
        <template x-if="result">
            <div class="space-y-6" x-transition>
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

                <!-- Success Notification -->
                <div class="flex justify-center" x-show="reported">
                    <div
                        class="premium-card px-8 py-4 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20 flex items-center gap-3 shadow-lg shadow-emerald-500/5">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <iconify-icon icon="lucide:check-circle" class="text-lg"></iconify-icon>
                        </div>
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Laporan Pelayanan
                            Berhasil Dicatat</span>
                    </div>
                </div>

                <!-- Report Modal -->
                <div x-show="showReportModal"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                    x-transition x-cloak>
                    <div class="premium-card w-full max-w-lg p-10 relative" @click.away="showReportModal = false">
                        <button @click="showReportModal = false"
                            class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
                            <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
                        </button>

                        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-8 uppercase tracking-tight">Lapor
                            Pelayanan</h3>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Jenis
                                    Layanan</label>
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
                                <label
                                    class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Catatan
                                    Tambahan</label>
                                <textarea x-model="report.notes"
                                    class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[11px] font-bold outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition uppercase tracking-wider"
                                    rows="3" placeholder="OPSIONAL..."></textarea>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button @click="showReportModal = false"
                                    class="flex-grow py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-[10px] uppercase tracking-widest rounded-2xl transition">Batal</button>
                                <button @click="submitReport()" :disabled="!report.service_type"
                                    class="flex-grow py-3.5 bg-primary-acorn hover:bg-primary-acorn/90 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-lg shadow-primary-acorn/20 transition disabled:opacity-50">Kirim
                                    Laporan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection

@push('scripts')
    <!-- CDNs -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        function verificationApp() {
            return {
                nik: '',
                loading: false,
                result: null,
                error: null,
                showScanner: false,
                html5QrCode: null,
                showReportModal: false,
                reported: false,
                report: {
                    service_type: '',
                    notes: ''
                },

                async verifyNik(method = 'NIK') {
                    if (this.nik.length < 16) return;

                    this.loading = true;
                    this.error = null;
                    this.result = null;
                    this.reported = false;

                    try {
                        const response = await fetch('{{ route('api.verification.check') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                nik: this.nik,
                                method: method
                            })
                        });

                        const json = await response.json();

                        if (json.success) {
                            this.result = json.data;
                        } else {
                            this.error = json.message || 'Terjadi kesalahan sistem.';
                        }
                    } catch (e) {
                        this.error = 'Gagal terhubung ke server. Periksa koneksi internet Anda.';
                    } finally {
                        this.loading = false;
                    }
                },

                async submitReport() {
                    if (!this.report.service_type) return;

                    this.loading = true;

                    try {
                        const response = await fetch('{{ route('service.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                citizen_nik: this.nik,
                                service_type: this.report.service_type,
                                notes: this.report.notes
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'BERHASIL!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            });
                            this.reported = true;
                            this.showReportModal = false;
                        } else if (response.status === 422) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'CELAH DUPLIKASI!',
                                text: data.message,
                                confirmButtonText: 'Tutup',
                                confirmButtonColor: '#3498db'
                            });
                        } else {
                            throw new Error(data.message || 'Gagal menyimpan laporan.');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'SISTEM SIBUK',
                            text: 'Gagal mengirim laporan. Silakan coba beberapa saat lagi.',
                            confirmButtonColor: '#e74c3c'
                        });
                    } finally {
                        this.loading = false;
                    }
                },

                toggleScanner() {
                    this.showScanner = !this.showScanner;
                    if (this.showScanner) {
                        this.$nextTick(() => {
                            this.startScanner();
                        });
                    } else {
                        this.stopScanner();
                    }
                },

                startScanner() {
                    this.html5QrCode = new Html5Qrcode("qr-reader");
                    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                        this.nik = decodedText;
                        this.stopScanner();
                        this.verifyNik('QR_SCAN');
                    };
                    const config = {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    };
                    this.html5QrCode.start({
                        facingMode: "environment"
                    }, config, qrCodeSuccessCallback);
                },

                stopScanner() {
                    if (this.html5QrCode) {
                        this.html5QrCode.stop().then((ignore) => {
                            this.showScanner = false;
                        }).catch((err) => {
                            console.warn("QR Scanner Stop Error: ", err);
                        });
                    } else {
                        this.showScanner = false;
                    }
                }
            }
        }
    </script>
@endpush

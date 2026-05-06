@extends('layouts.app')

@section('content')
    <div x-data="verificationApp()" class="max-w-5xl mx-auto space-y-10 pb-20">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    Data Dokumen
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
                        placeholder="Input No KK atau NIK" maxlength="16">
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

        <!-- Loading State -->
        <div x-show="loading" x-transition class="flex flex-col items-center justify-center py-20 space-y-4">
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 border-4 border-primary-acorn/20 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-primary-acorn border-t-transparent rounded-full animate-spin">
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest animate-pulse">Menghubungkan ke Basis
                Data Pusat...</p>
        </div>

        <div x-show="!loading && result" x-transition class="space-y-10">

            @include('services.verification.partials._result_citizen')

            @include('services.verification.partials._result_household')

            <!-- Success Notification -->
            <div x-show="reported" x-transition
                class="premium-card p-6 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20 flex items-center gap-4">
                <div class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-500/20">
                    <iconify-icon icon="lucide:check-circle" class="text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-[11px] font-black text-emerald-600 uppercase tracking-tight">Laporan Pelayanan Berhasil
                        Tercatat</p>
                    <p class="text-[10px] text-emerald-500/70 font-medium uppercase tracking-tight">Data telah disinkronkan
                        dengan sistem audit internal.</p>
                </div>
            </div>
        </div>

        @include('services.verification.partials._report_modal')

    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode"></script>
        <script>
            function verificationApp() {
                return {
                    nik: '',
                    loading: false,
                    result: null,
                    resultType: null,
                    lastHouseholdResult: null, // Memory for navigation back
                    error: null,
                    showScanner: false,
                    html5QrCode: null,
                    showReportModal: false,
                    reported: false,
                    report: {
                        service_type: '',
                        notes: ''
                    },

                    init() {
                        // Check if there is a search query in the URL on load
                        const urlParams = new URLSearchParams(window.location.search);
                        const q = urlParams.get('q');
                        if (q && q.length === 16) {
                            this.nik = q;
                            this.verifyNik('URL', true, false); // false = don't push state again
                        }

                        // Listen for browser back/forward buttons
                        window.onpopstate = (event) => {
                            if (event.state && event.state.nik) {
                                this.nik = event.state.nik;
                                this.verifyNik(event.state.method || 'NIK', event.state.manual || false, false);
                            } else {
                                // Reset if we go back to the initial state
                                this.nik = '';
                                this.result = null;
                                this.resultType = null;
                                this.error = null;
                            }
                        };
                    },

                    async verifyNik(method = 'NIK', isManualSearch = true, shouldPushState = true) {
                        if (this.nik.length < 16) return;

                        // Clear history memory if it's a new manual search from the top bar
                        if (isManualSearch) {
                            this.lastHouseholdResult = null;
                        }

                        this.loading = true;
                        this.error = null;
                        this.result = null;
                        this.resultType = null;
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
                                this.resultType = json.type || 'CITIZEN';
                                this.result = json.data;

                                // Update browser URL and history
                                if (shouldPushState) {
                                    const newUrl = window.location.pathname + '?q=' + this.nik;
                                    window.history.pushState({
                                        nik: this.nik,
                                        method: method,
                                        manual: isManualSearch
                                    }, '', newUrl);
                                }

                                // If we just found a household, store it for potential 'back' navigation
                                if (this.resultType === 'HOUSEHOLD') {
                                    this.lastHouseholdResult = json.data;
                                }
                            } else {
                                this.error = json.data ? json.data.message : (json.message || 'Terjadi kesalahan sistem.');
                            }
                        } catch (e) {
                            this.error = 'Gagal terhubung ke server. Periksa koneksi internet Anda.';
                        } finally {
                            this.loading = false;
                        }
                    },

                    backToHousehold() {
                        if (this.lastHouseholdResult) {
                            window.history.back();
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
                                    nik: this.nik,
                                    ...this.report
                                })
                            });

                            const json = await response.json();
                            if (json.success) {
                                this.reported = true;
                                this.showReportModal = false;
                                this.report = {
                                    service_type: '',
                                    notes: ''
                                };
                            }
                        } catch (e) {
                            this.error = 'Gagal mengirim laporan.';
                        } finally {
                            this.loading = false;
                        }
                    },

                    toggleScanner() {
                        this.showScanner = !this.showScanner;
                        if (this.showScanner) {
                            this.startScanner();
                        } else {
                            this.stopScanner();
                        }
                    },

                    startScanner() {
                        this.$nextTick(() => {
                            this.html5QrCode = new Html5Qrcode("qr-reader");
                            this.html5QrCode.start({
                                    facingMode: "environment"
                                }, {
                                    fps: 10,
                                    qrbox: {
                                        width: 250,
                                        height: 250
                                    }
                                },
                                (decodedText) => {
                                    this.nik = decodedText;
                                    this.stopScanner();
                                    this.verifyNik('QR_SCAN');
                                }
                            ).catch(err => {
                                console.error(err);
                                this.error = "Gagal mengakses kamera.";
                                this.showScanner = false;
                            });
                        });
                    },

                    stopScanner() {
                        if (this.html5QrCode) {
                            this.html5QrCode.stop().then(() => {
                                this.html5QrCode.clear();
                                this.showScanner = false;
                            }).catch(err => console.error(err));
                        } else {
                            this.showScanner = false;
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection

@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Base Selection */
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
            /* slate-50 */
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 1.25rem !important;
            /* 20px */
            height: 64px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 1.5rem !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: none !important;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(15, 23, 42, 0.5) !important;
            /* slate-900/50 */
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Selection Focus/Active */
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #10b981 !important;
            /* emerald-500 */
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
            background-color: #ffffff !important;
        }

        .dark .select2-container--default.select2-container--open .select2-selection--single {
            background-color: #0f172a !important;
        }

        /* Text Styling */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important;
            /* slate-700 */
            font-weight: 800 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            line-height: 1 !important;
            padding: 0 !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important;
            /* slate-200 */
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important;
            /* slate-400 */
        }

        /* Arrow Removal & Replacement */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        .select2-container--default .select2-selection--single::after {
            content: '';
            width: 1.25rem;
            height: 1.25rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            position: absolute;
            right: 1.5rem;
            transition: transform 0.3s ease;
        }

        .select2-container--default.select2-container--open .select2-selection--single::after {
            transform: rotate(180deg);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2310b981' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E");
        }

        /* Dropdown Container */
        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            padding: 0.5rem !important;
            z-index: 10001 !important;
            overflow: hidden !important;
            animation: select2SlideUp 0.3s ease-out;
        }

        .dark .select2-dropdown {
            background-color: #1e293b !important;
            /* slate-800 */
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        @keyframes select2SlideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Search Field */
        .select2-search--dropdown {
            padding: 0.75rem !important;
        }

        .select2-search--dropdown .select2-search__field {
            background-color: #f1f5f9 !important;
            /* slate-100 */
            border: 1px solid transparent !important;
            border-radius: 1rem !important;
            padding: 0.75rem 1rem !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            outline: none !important;
            transition: all 0.3s ease !important;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #0f172a !important;
            color: #ffffff !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            background-color: #ffffff !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
        }

        /* Results/Options */
        .select2-results__options {
            max-height: 240px !important;
        }

        .select2-results__option {
            padding: 0.875rem 1.25rem !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border-radius: 0.875rem !important;
            margin-bottom: 2px !important;
            color: #64748b !important;
            /* slate-500 */
            transition: all 0.2s ease !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #10b981 !important;
            /* emerald-500 */
            color: #ffffff !important;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #f1f5f9 !important;
            color: #10b981 !important;
        }

        .dark .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #334155 !important;
            color: #34d399 !important;
        }

        .select2-results__option:last-child {
            margin-bottom: 0 !important;
        }

        .select2-dropdown {
            z-index: 100001 !important;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.2);
        }
    </style>
@endpush

@section('content')
    <div x-data="verificationApp()">
        <div class="w-full space-y-10 pb-20">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                        Data Dokumen
                    </h1>
                    <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                        Gunakan Nomor Induk Kependudukan atau Pindai Kode QR untuk validasi status kemiskinan secara
                        real-time.
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


            <!-- Not Found State with Lapor Datang Option -->
            <div x-show="error && error.includes('tidak ditemukan')" x-transition
                class="premium-card p-8 border-amber-100 dark:border-amber-500/20 bg-amber-50/30 dark:bg-amber-500/5 flex flex-col items-center text-center space-y-6">
                <div class="p-4 bg-amber-100 dark:bg-amber-500/20 text-amber-600 rounded-2xl shadow-inner">
                    <iconify-icon icon="lucide:user-search" class="text-4xl"></iconify-icon>
                </div>
                <div class="max-w-md">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Warga Belum Terdaftar</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed mt-2">
                        NIK <span class="font-black text-amber-600" x-text="nik"></span> tidak ditemukan dalam basis data penduduk saat ini. Jika ini adalah warga baru yang pindah ke wilayah Anda, silakan gunakan fitur Lapor Datang.
                    </p>
                </div>
                <a :href="'{{ route('services.arrival.create') }}?nik=' + nik"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-10 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-amber-500/20 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                    <iconify-icon icon="lucide:user-plus" class="text-lg"></iconify-icon>
                    <span>Daftarkan Warga Baru (Lapor Datang)</span>
                </a>
            </div>

            <!-- Error State (Generic) -->
            <div x-show="error && !error.includes('tidak ditemukan')" x-transition
                class="premium-card p-6 bg-rose-50/50 dark:bg-rose-500/10 border-rose-100 dark:border-rose-500/20 flex items-center gap-4">
                <div class="p-2.5 bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-500/20">
                    <iconify-icon icon="lucide:alert-circle" class="text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-[11px] font-black text-rose-600 uppercase tracking-tight" x-text="error"></p>
                    <p class="text-[10px] text-rose-500/70 font-medium uppercase tracking-tight">Silakan periksa kembali
                        Nomor
                        Induk atau kualitas gambar Kode QR.</p>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" x-transition class="flex flex-col items-center justify-center py-20 space-y-4">
                <div class="relative w-16 h-16">
                    <div class="absolute inset-0 border-4 border-primary-acorn/20 rounded-full"></div>
                    <div
                        class="absolute inset-0 border-4 border-primary-acorn border-t-transparent rounded-full animate-spin">
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest animate-pulse">Menghubungkan ke
                    Basis
                    Data Pusat...</p>
            </div>

            <div x-show="!loading && result" x-transition class="w-full space-y-10">

                @include('services.admin.verification.partials._result_citizen')

                @include('services.admin.verification.partials._result_household')

                <!-- Success Notification -->
                <div x-show="reported" x-transition
                    class="premium-card p-6 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20 flex items-center gap-4">
                    <div class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-500/20">
                        <iconify-icon icon="lucide:check-circle" class="text-xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-emerald-600 uppercase tracking-tight">Laporan Pelayanan
                            Berhasil
                            Tercatat</p>
                        <p class="text-[10px] text-emerald-500/70 font-medium uppercase tracking-tight">Data telah
                            disinkronkan
                            dengan sistem audit internal.</p>
                    </div>
                </div>
            </div>

            <template x-teleport="body">
                @include('services.admin.verification.partials._report_modal')
            </template>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        notes: '',
                        destination_address: '',
                        date_of_death: '',
                        place_of_death: '',
                        cause_of_death: ''
                    },


                    init() {
                        // Check if there is a search query in the URL on load
                        const urlParams = new URLSearchParams(window.location.search);
                        const q = urlParams.get('q');
                        if (q && q.length === 16) {
                            this.nik = q;
                            this.verifyNik('NIK', true, false); // false = don't push state again
                        }

                        // Watch for modal visibility to init Select2
                        this.$watch('showReportModal', value => {
                            if (value) {
                                this.$nextTick(() => {
                                    const select = $('#service-type-select');
                                    select.select2({
                                        width: '100%',
                                        placeholder: '-- PILIH JENIS PELAYANAN --'
                                    });

                                    select.on('change', (e) => {
                                        this.report.service_type = e.target.value;
                                    });
                                });
                            }
                        });

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
                            const response = await fetch('{{ route('services.verification.check') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
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

                    showDocAlert(status) {
                        let title = 'Dokumen Belum Tersedia';
                        let text =
                            'Penduduk ini belum memiliki riwayat pelayanan atau permohonan untuk jenis dokumen ini di sistem kami.';
                        let icon = 'info';

                        if (status === 'EXPIRED') {
                            title = 'Dokumen Kadaluarsa';
                            text =
                                'Masa berlaku dokumen ini telah habis. Silakan ajukan pembaruan melalui tombol di bawah jika diperlukan.';
                            icon = 'warning';
                        } else if (status === 'REJECTED') {
                            title = 'Permohonan Ditolak';
                            text =
                                'Permohonan untuk dokumen ini telah ditolak oleh verifikator. Silakan periksa alasan penolakan pada detail dokumen.';
                            icon = 'error';
                        } else if (status === 'PENDING') {
                            title = 'Sedang Diproses';
                            text =
                                'Dokumen ini sedang dalam tahap verifikasi oleh petugas desa. Mohon tunggu hingga proses selesai.';
                            icon = 'info';
                        }

                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            confirmButtonText: 'PAHAM'
                        });
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
                            const response = await fetch('{{ route('services.requests.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    citizen_nik: this.nik,
                                    ...this.report
                                })
                            });

                            const json = await response.json();
                            if (response.ok && json.success) {
                                this.reported = true;
                                this.showReportModal = false;
                                this.report = {
                                    service_type: '',
                                    notes: '',
                                    destination_address: '',
                                    date_of_death: '',
                                    place_of_death: '',
                                    cause_of_death: ''
                                };

                                // Refresh data to show PENDING status
                                await this.verifyNik('NIK', false, false);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'BERHASIL!',
                                    text: 'Permohonan verifikasi telah dikirim ke desa.',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                let errorMsg = json.message || 'Terjadi kesalahan sistem.';
                                if (json.errors) {
                                    // Extract the first validation error message from Laravel
                                    errorMsg = Object.values(json.errors)[0][0];
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'PENGAJUAN GAGAL',
                                    text: errorMsg,
                                    confirmButtonText: 'Tutup'
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'KONEKSI BERMASALAH',
                                text: 'Gagal mengirim laporan. Pastikan koneksi internet Anda stabil.',
                                confirmButtonText: 'Tutup'
                            });
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
                                    this.verifyNik('QR');
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

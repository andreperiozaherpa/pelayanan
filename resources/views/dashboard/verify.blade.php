@extends('layouts.app')

@section('title', 'Verifikasi NIK')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8" x-data="verificationApp()">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white tracking-tight">Verifikasi Data Warga</h1>
            <p class="text-slate-400 mt-2">Gunakan NIK atau Scan QR untuk validasi status kemiskinan secara real-time.</p>
        </div>

        <!-- Interface -->
        <div class="bg-slate-800/50 rounded-3xl border border-slate-700/50 p-8 shadow-2xl backdrop-blur-sm">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="nik" @keyup.enter="verifyNik('NIK')"
                        class="block w-full pl-11 pr-4 py-4 bg-slate-900/50 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition"
                        placeholder="Masukkan 16 digit NIK..." maxlength="16">
                </div>
                <button @click="verifyNik('NIK')" :disabled="loading || nik.length < 16"
                    class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-2xl transition shadow-lg shadow-emerald-600/20">
                    <span x-show="!loading">Verifikasi</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Proses...
                    </span>
                </button>
                <button @click="toggleScanner()"
                    class="p-4 bg-slate-700 hover:bg-slate-600 text-white rounded-2xl transition border border-slate-600"
                    title="Scan QR Code">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h3m-3 0h-3m3 10h3m11-3v3m0 0h-3m3 0v-3m-12-9h2m8 0h2m-12 9h2m8 0h2m-11 5V5a2 2 0 012-2h4a2 2 0 012 2v14a2 2 0 01-2 2h-4a2 2 0 01-2-2z" />
                    </svg>
                </button>
            </div>

            <!-- Scanner Container -->
            <div x-show="showScanner" x-transition
                class="mt-6 border-2 border-dashed border-slate-700 rounded-3xl overflow-hidden bg-black/20">
                <div id="qr-reader" class="w-full"></div>
                <div class="p-4 bg-slate-900/80 text-center">
                    <button @click="stopScanner()"
                        class="text-xs font-bold text-rose-400 uppercase tracking-widest hover:text-rose-300">Tutup
                        Kamera</button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center py-12">
            <div class="w-12 h-12 border-4 border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin"></div>
        </div>

        <!-- Error State -->
        <div x-show="error" x-transition
            class="bg-rose-500/10 border border-rose-500/20 p-6 rounded-3xl flex items-center gap-4">
            <div class="p-2 bg-rose-500/20 rounded-full">
                <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-rose-500" x-text="error"></p>
                <p class="text-sm text-rose-400/80">Silakan periksa kembali nomor NIK atau kualitas gambar QR.</p>
            </div>
        </div>

        <!-- Result State -->
        <template x-if="result">
            <div class="space-y-6" x-transition>
                <!-- Smart Card -->
                <div class="relative overflow-hidden rounded-[2.5rem] border-2 shadow-2xl p-1 w-full"
                    :class="{
                        'border-emerald-500/30 bg-gradient-to-br from-emerald-500/10 to-slate-900': result
                            .status === 'ACTIVE',
                        'border-rose-500/30 bg-gradient-to-br from-rose-500/10 to-slate-900': result
                            .status === 'EXPIRED',
                        'border-amber-500/30 bg-gradient-to-br from-amber-500/10 to-slate-900': result
                            .status === 'PENDING_REVIEW' || result.status === 'NOT_FOUND'
                    }">
                    <div class="bg-slate-900/90 rounded-[2.3rem] p-8 md:p-12 relative overflow-hidden">
                        <div class="flex flex-col md:flex-row gap-12 items-start">
                            <!-- Profile/Avatar -->
                            <div class="flex-shrink-0">
                                <div
                                    class="h-32 w-32 rounded-3xl bg-slate-800 border-4 border-slate-700 flex items-center justify-center text-5xl shadow-inner shadow-black/20">
                                    👤
                                </div>
                            </div>

                            <!-- Data Body -->
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <p class="text-2xl font-black text-emerald-400 tracking-tighter"
                                        x-text="result.citizen ? result.citizen.nama_lengkap : nik"></p>
                                    <span x-show="result.status"
                                        :class="{
                                            'bg-emerald-500 shadow-emerald-500/50': result.status === 'ACTIVE',
                                            'bg-rose-500 shadow-rose-500/50': result.status === 'EXPIRED',
                                            'bg-amber-500 shadow-amber-500/50': result.status === 'PENDING_REVIEW'
                                        }"
                                        class="px-6 py-2 rounded-full text-[10px] font-black text-white shadow-2xl tracking-[0.2em] uppercase"
                                        x-text="result.status">
                                    </span>
                                </div>

                                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <p class="text-slate-500 uppercase font-black tracking-widest text-[10px] mb-1">
                                            Status Kesejahteraan</p>
                                        <p class="text-2xl font-bold text-white leading-tight" x-text="result.message"></p>
                                    </div>

                                    <div x-show="result.record">
                                        <p class="text-slate-500 uppercase font-black tracking-widest text-[10px] mb-1">Masa
                                            Berlaku</p>
                                        <p class="text-xl font-medium text-slate-200"
                                            x-text="result.record && result.record.valid_until_formatted ? 'Hingga ' + result.record.valid_until_formatted : 'Tidak Terbatas'">
                                        </p>
                                    </div>
                                </div>

                                <!-- Secondary Identity Info -->
                                <div class="mt-8 pt-8 border-t border-slate-700/50 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div x-show="result.citizen">
                                        <p class="text-slate-500 uppercase font-black tracking-widest text-[10px] mb-1">
                                            Wilayah Desa</p>
                                        <p class="text-lg text-slate-200"
                                            x-text="result.citizen.village ? (typeof result.citizen.village === 'object' ? result.citizen.village.name : result.citizen.village) : 'Desa ID: ' + result.citizen.desa_id">
                                        </p>
                                    </div>
                                    <div x-show="result.citizen">
                                        <p class="text-slate-500 uppercase font-black tracking-widest text-[10px] mb-1">
                                            Alamat Domisili</p>
                                        <p class="text-lg text-slate-200" x-text="result.citizen.alamat_desa"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Background Decoration -->
                        <div class="absolute -bottom-12 -right-12 w-64 h-64 opacity-5 pointer-events-none">
                            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#FFFFFF"
                                    d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,79.6,-45.8C87.4,-32.6,90,-16.3,88.5,-0.9C87,14.6,81.4,29.2,73.1,42.4C64.8,55.6,53.8,67.3,40.4,74.1C27,80.9,13.5,82.8,-0.5,83.6C-14.4,84.4,-28.8,84.1,-41.8,77.7C-54.8,71.3,-66.4,58.7,-74.6,44.7C-82.8,30.7,-87.6,15.4,-88.4,-0.5C-89.2,-16.3,-86.1,-32.7,-77.8,-46.7C-69.5,-60.7,-56.1,-72.3,-41.4,-79.1C-26.7,-85.9,-13.4,-87.9,0.4,-88.6C14.2,-89.3,28.4,-88.7,44.7,-76.4Z"
                                    transform="translate(100 100)" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Contextual Actions -->
                <div class="flex flex-wrap justify-center gap-4" x-show="(result.status === 'ACTIVE' || result.status === 'PENDING_REVIEW' || result.status === 'EXPIRED') && !reported">
                    @can('service.report')
                        <button @click="showReportModal = true"
                            class="px-12 py-5 bg-white text-slate-900 font-bold rounded-[2rem] hover:bg-slate-200 transition transform hover:-translate-y-1 shadow-2xl h-16 flex items-center">
                            Lapor Pelayanan Diberikan
                        </button>
                    @endcan

                    @can('poverty.print_proof')
                        <template x-if="result.status === 'ACTIVE'">
                            <a :href="'/proof/' + nik" target="_blank"
                                class="px-12 py-5 bg-slate-700 text-white font-bold rounded-[2rem] hover:bg-slate-600 transition transform hover:-translate-y-1 shadow-2xl h-16 flex items-center border border-slate-600">
                                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m32 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Bukti Verifikasi
                            </a>
                        </template>
                    @endcan
                </div>

                <div class="flex justify-center" x-show="reported">
                    <div
                        class="px-8 py-4 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-2xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm font-bold uppercase tracking-widest">Pelayanan Berhasil Dicatat</span>
                    </div>
                </div>

                <!-- Report Modal -->
                <div x-show="showReportModal"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm"
                    x-transition>
                    <div class="bg-slate-800 border border-slate-700 w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl"
                        @click.away="showReportModal = false">
                        <h3 class="text-2xl font-bold text-white mb-6">Lapor Pelayanan</h3>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-1">Jenis
                                    Layanan</label>
                                <select x-model="report.service_type"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500/50">
                                    <option value="">Pilih Layanan...</option>
                                    <option value="Bantuan Pangan">Bantuan Pangan</option>
                                    <option value="Bantuan Pendidikan (KIP)">Bantuan Pendidikan (KIP)</option>
                                    <option value="Jaminan Kesehatan (PBI)">Jaminan Kesehatan (PBI)</option>
                                    <option value="Sembako/BPNT">Sembako/BPNT</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-1">Catatan
                                    Tambahan</label>
                                <textarea x-model="report.notes"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500/50"
                                    rows="3" placeholder="Opsional..."></textarea>
                            </div>
                            <div class="flex gap-4 pt-4">
                                <button @click="showReportModal = false"
                                    class="flex-grow py-4 bg-slate-700 text-white font-bold rounded-2xl hover:bg-slate-600 transition">Batal</button>
                                <button @click="submitReport()" :disabled="!report.service_type"
                                    class="flex-grow py-4 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-500 transition disabled:opacity-50">Kirim
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

                    this.isSubmitting = true;

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
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            });
                            this.reported = true;
                            this.showReportModal = false;
                        } else if (response.status === 422) {
                            // Security Notification (Duplicate Check)
                            Swal.fire({
                                icon: 'warning',
                                title: 'Celah Duplikasi!',
                                text: data.message,
                                confirmButtonText: 'Tutup',
                                confirmButtonColor: '#10b981'
                            });
                        } else {
                            throw new Error(data.message || 'Gagal menyimpan laporan.');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sistem Sibuk',
                            text: 'Gagal mengirim laporan. Silakan coba beberapa saat lagi.',
                            confirmButtonColor: '#ef4444'
                        });
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                toggleScanner() {
                    // ... scanner logic ...
                },
                // ... other methods ...
            }
        }
    </script>
@endpush

@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 1.25rem !important;
            height: 64px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 1.5rem !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(15, 23, 42, 0.5) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 1.5rem !important;
            padding: 0.5rem !important;
            z-index: 10001 !important;
            overflow: hidden !important;
        }

        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
    </style>
@endpush

@section('content')
    <div x-data="arrivalApp()" class="w-full space-y-10 pb-20">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    Lapor Datang Warga
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                    Registrasi kedatangan penduduk baru yang belum terdaftar dalam sistem.
                </p>
            </div>

            <a href="{{ route('services.verification') }}"
                class="flex items-center gap-2 px-6 py-3.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-acorn transition shadow-sm">
                <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
                <span>Kembali ke Verifikasi</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="premium-card overflow-hidden">
            <div class="p-8 sm:p-12">
                <form @submit.prevent="submitForm()" class="space-y-10">
                    <!-- Section: Data Identitas -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                                <iconify-icon icon="lucide:user" class="text-xl"></iconify-icon>
                            </div>
                            <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em]">Data Identitas</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NIK (16 Digit)</label>
                                <input type="text" x-model="form.nik" maxlength="16" required
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition uppercase tracking-wider">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                <input type="text" x-model="form.nama_lengkap" required
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition uppercase tracking-wider">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                                <input type="date" x-model="form.tgl_lahir" required
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kontak / WA (Opsional)</label>
                                <input type="text" x-model="form.kontak"
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition tracking-wider"
                                    placeholder="Contoh: 0812...">
                            </div>
                        </div>
                    </div>

                    <hr class="border-black/[0.03] dark:border-white/[0.03]">

                    <!-- Section: Data Kedatangan -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                <iconify-icon icon="lucide:map-pin" class="text-xl"></iconify-icon>
                            </div>
                            <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em]">Data Kedatangan</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Desa Tujuan</label>
                                <select id="desa-select" x-model="form.desa_id" required class="w-full">
                                    <option value="">-- PILIH DESA --</option>
                                    @foreach (\App\Models\Village::all() as $village)
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Sekarang (Desa)</label>
                                <input type="text" x-model="form.alamat_desa" required
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition uppercase tracking-wider"
                                    placeholder="Nama Dusun / RT / RW">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Asal (Lengkap)</label>
                                <textarea x-model="form.previous_address" required rows="3"
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition uppercase tracking-wider"
                                    placeholder="Alamat Lengkap dari Daerah Asal"></textarea>
                            </div>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Kedatangan</label>
                                    <input type="date" x-model="form.arrival_date" required
                                        class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Tambahan (Opsional)</label>
                                    <input type="text" x-model="form.notes"
                                        class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn outline-none transition uppercase tracking-wider">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" :disabled="loading"
                            class="w-full md:w-auto bg-primary-acorn hover:bg-primary-acorn/90 text-white px-12 py-5 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-primary-acorn/20 transition-all hover:-translate-y-1 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3">
                            <template x-if="!loading">
                                <div class="flex items-center gap-3">
                                    <iconify-icon icon="lucide:save" class="text-xl"></iconify-icon>
                                    <span>Simpan Data Kedatangan</span>
                                </div>
                            </template>
                            <template x-if="loading">
                                <div class="flex items-center gap-3">
                                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Memproses...</span>
                                </div>
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function arrivalApp() {
                return {
                    loading: false,
                    form: {
                        nik: '{{ request('nik') }}',
                        nama_lengkap: '',
                        tgl_lahir: '',
                        alamat_desa: '',
                        desa_id: '',
                        previous_address: '',
                        arrival_date: new Date().toISOString().split('T')[0],
                        kontak: '',
                        notes: ''
                    },

                    init() {
                        this.$nextTick(() => {
                            const select = $('#desa-select');
                            select.select2({
                                width: '100%',
                                placeholder: '-- PILIH DESA --'
                            });

                            select.on('change', (e) => {
                                this.form.desa_id = e.target.value;
                            });
                        });
                    },

                    async submitForm() {
                        this.loading = true;
                        try {
                            const response = await fetch('{{ route('services.arrival.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(this.form)
                            });

                            const json = await response.json();
                            if (response.ok && json.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'BERHASIL!',
                                    text: 'Data warga baru dan laporan kedatangan telah tersimpan.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'rounded-[2rem]',
                                        confirmButton: 'bg-primary-acorn rounded-xl px-8 py-3 text-[10px] font-black uppercase tracking-widest'
                                    }
                                }).then(() => {
                                    window.location.href = '{{ route('services.verification') }}?q=' + this.form.nik;
                                });
                            } else {
                                let errorMsg = json.message || 'Terjadi kesalahan sistem.';
                                if (json.errors) {
                                    errorMsg = Object.values(json.errors)[0][0];
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'GAGAL',
                                    text: errorMsg,
                                    confirmButtonText: 'TUTUP',
                                    customClass: {
                                        popup: 'rounded-[2rem]',
                                        confirmButton: 'bg-rose-500 rounded-xl px-8 py-3 text-[10px] font-black uppercase tracking-widest'
                                    }
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'KONEKSI BERMASALAH',
                                text: 'Gagal mengirim data. Pastikan koneksi internet Anda stabil.',
                                confirmButtonText: 'TUTUP'
                            });
                        } finally {
                            this.loading = false;
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection

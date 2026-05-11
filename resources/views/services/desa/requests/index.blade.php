@extends('layouts.app')

@section('title', 'Antrean Permohonan Layanan')

@section('content')
    <div class="space-y-8 pb-20" x-data="requestQueue()">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span
                        class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-[0.2em] rounded-full border border-primary-acorn/20 shadow-sm">Verifikasi
                        Desa</span>
                    <span
                        class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ Auth::user()->village->name ?? 'Semua Wilayah' }}</span>
                </div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase leading-none">
                    Antrean Permohonan
                </h1>
                <p class="text-[11px] text-slate-500 font-bold tracking-tight mt-2 uppercase opacity-60">
                    Kelola dan verifikasi permohonan layanan dari Front Office
                </p>
                <div
                    class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 bg-rose-500/10 rounded-lg border border-rose-500/20">
                    <iconify-icon icon="lucide:clock" class="text-rose-500 text-sm"></iconify-icon>
                    <span class="text-[9px] font-black text-rose-600 uppercase tracking-widest">SLA 1 HARI: Wajib Selesai
                        Hari Ini</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div
                    class="premium-card px-6 py-4 flex items-center gap-5 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl border-rose-500/10">
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Antrean</p>
                        <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">
                            {{ $requests->total() }} Permohonan</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center border border-primary-acorn/10 shadow-inner">
                        <iconify-icon icon="lucide:inbox" class="text-xl"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-black/[0.03] dark:border-white/[0.03]">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Warga /
                                Pemohon</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis
                                Layanan</th>
                            @if (Auth::user()->isSuperAdmin())
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Desa
                                </th>
                            @endif
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Catatan FO
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu /
                                Urgensi</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.03] dark:divide-white/[0.03]">
                        @forelse($requests as $req)
                            @php
                                $isToday = $req->created_at->isToday();
                                $hoursDiff = $req->created_at->diffInHours(now());
                                $isOverdue = !$isToday;
                                $isUrgent = $isToday && $hoursDiff >= 2;
                            @endphp
                            <tr
                                class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-all group {{ $isOverdue ? 'bg-rose-50/30 dark:bg-rose-950/10' : '' }}">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:scale-110 transition-transform">
                                            <iconify-icon icon="lucide:user" class="text-lg"></iconify-icon>
                                        </div>
                                        <div>
                                            <p
                                                class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">
                                                {{ $req->citizen->nama_lengkap }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 tracking-wider mt-1">
                                                {{ $req->citizen_nik }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-wider rounded-lg border border-primary-acorn/10">
                                        {{ $req->service_type }}
                                    </span>
                                </td>
                                @if (Auth::user()->isSuperAdmin())
                                    <td class="px-8 py-6">
                                        <p
                                            class="text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                            {{ $req->citizen->village->name ?? '-' }}
                                        </p>
                                    </td>
                                @endif
                                <td class="px-8 py-6">
                                    <p class="text-[11px] text-slate-500 font-medium italic">
                                        "{{ $req->notes ?? '-' }}"
                                    </p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tabular-nums">
                                            {{ $req->created_at->translatedFormat('d M Y') }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-[9px] text-slate-400 opacity-50 uppercase">
                                                {{ $req->created_at->format('H:i') }}</p>

                                            @if ($isOverdue)
                                                <span
                                                    class="flex items-center gap-1 px-1.5 py-0.5 bg-rose-600 text-white text-[7px] font-black uppercase tracking-widest rounded shadow-sm animate-pulse">
                                                    <iconify-icon icon="lucide:alert-triangle"
                                                        class="text-[8px]"></iconify-icon>
                                                    Sangat Terlambat
                                                </span>
                                            @elseif($isUrgent)
                                                <span
                                                    class="flex items-center gap-1 px-1.5 py-0.5 bg-amber-500/20 text-amber-600 text-[7px] font-black uppercase tracking-widest rounded border border-amber-500/20">
                                                    <iconify-icon icon="lucide:clock-3" class="text-[8px]"></iconify-icon>
                                                    Butuh Segera
                                                </span>
                                            @else
                                                <span
                                                    class="flex items-center gap-1 px-1.5 py-0.5 bg-emerald-500/10 text-emerald-500 text-[7px] font-black uppercase tracking-widest rounded border border-emerald-500/10">
                                                    Baru
                                                </span>
                                            @endif
                                        </div>
                                        <p
                                            class="text-[8px] font-black {{ $isOverdue ? 'text-rose-500' : ($isUrgent ? 'text-amber-500' : 'text-slate-400') }} uppercase tracking-tighter mt-1 opacity-70">
                                            @if ($isOverdue)
                                                Sudah Melewati {{ $req->created_at->diffInDays(now()) }} Hari
                                            @else
                                                Menunggu {{ $hoursDiff }} Jam
                                            @endif
                                        </p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        @if (!Auth::user()->isSuperAdmin())
                                            <button
                                                @click.stop="openApproveModal({{ $req->id }}, {{ json_encode($req->citizen->nama_lengkap) }}, '{{ $req->service_type->name }}', {{ json_encode($req->notes) }})"
                                                class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-md hover:shadow-emerald-500/20 hover:-translate-y-0.5 transition-all active:scale-95 inline-flex items-center gap-2">
                                                Setujui
                                                <iconify-icon icon="lucide:check-circle" class="text-base"></iconify-icon>
                                            </button>
                                            <button
                                                @click.stop="openRejectModal({{ $req->id }}, {{ json_encode($req->citizen->nama_lengkap) }})"
                                                class="p-2.5 rounded-xl text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition-all">
                                                <iconify-icon icon="lucide:x-circle" class="text-xl"></iconify-icon>
                                            </button>
                                        @else
                                            <span
                                                class="text-[9px] font-bold text-slate-400 uppercase italic tracking-widest opacity-50">Hanya
                                                Pemantauan</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isSuperAdmin() ? 6 : 5 }}" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <iconify-icon icon="lucide:inbox"
                                            class="text-5xl text-slate-300 mb-4"></iconify-icon>
                                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Tidak ada
                                            antrean permohonan saat ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($requests->hasPages())
                <div
                    class="px-8 py-6 bg-slate-50/50 dark:bg-slate-900/50 border-t border-black/[0.03] dark:border-white/[0.03]">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>

        <!-- Approval Modal -->
        <div x-show="showApproveModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

            <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/10"
                @click.outside="showApproveModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-10"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="p-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center border border-emerald-500/10">
                                <iconify-icon icon="lucide:shield-check" class="text-2xl"></iconify-icon>
                            </div>
                            <div>
                                <h3
                                    class="text-lg font-black text-slate-800 dark:text-white tracking-tight uppercase leading-none">
                                    Verifikasi Persetujuan</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2"
                                    x-text="selectedRequest.name"></p>
                            </div>
                        </div>
                        <button @click="showApproveModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <iconify-icon icon="lucide:x" class="text-2xl"></iconify-icon>
                        </button>
                    </div>

                    <div class="space-y-8">
                        <!-- Poverty Specific Field -->
                        <div class="space-y-3" x-show="selectedRequest.type === 'POVERTY'">
                            <label
                                class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                <iconify-icon icon="lucide:coins" class="text-primary-acorn"></iconify-icon>
                                Rentang Penghasilan
                            </label>
                            <select x-model="form.income_range"
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border border-black/[0.03] rounded-2xl text-[11px] font-black outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition-all uppercase tracking-wider">
                                <option value="">-- PILIH PENGHASILAN --</option>
                                <option value="DI BAWAH RP 500.000">DI BAWAH RP 500.000</option>
                                <option value="RP 500.000 - RP 1.000.000">RP 500.000 - RP 1.000.000</option>
                                <option value="RP 1.000.000 - RP 2.000.000">RP 1.000.000 - RP 2.000.000</option>
                                <option value="DI ATAS RP 2.000.000">DI ATAS RP 2.000.000</option>
                            </select>
                        </div>

                        <!-- Domicile Specific Field -->
                        <div class="space-y-3" x-show="selectedRequest.type === 'DOMICILE'">
                            <label
                                class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                <iconify-icon icon="lucide:info" class="text-primary-acorn"></iconify-icon>
                                Keperluan Domisili
                            </label>
                            <textarea x-model="form.purpose" rows="3" placeholder="CONTOH: PERSYARATAN ADMINISTRASI BANK / KERJA..."
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border border-black/[0.03] rounded-2xl text-[11px] font-black outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition-all uppercase tracking-wider placeholder:opacity-30"></textarea>
                        </div>

                        <!-- Move Specific Fields -->
                        <div class="space-y-6" x-show="selectedRequest.type === 'MOVE'">
                            <div class="space-y-3">
                                <label
                                    class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    <iconify-icon icon="lucide:map-pin" class="text-primary-acorn"></iconify-icon>
                                    Alamat Tujuan
                                </label>
                                <textarea x-model="form.destination_address" rows="2" placeholder="ALAMAT LENGKAP TUJUAN PINDAH..."
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border border-black/[0.03] rounded-2xl text-[11px] font-black outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition-all uppercase tracking-wider placeholder:opacity-30"></textarea>
                            </div>
                            <div class="space-y-3">
                                <label
                                    class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    <iconify-icon icon="lucide:help-circle" class="text-primary-acorn"></iconify-icon>
                                    Alasan Pindah
                                </label>
                                <textarea x-model="form.reason_move" rows="2" placeholder="CONTOH: MENGIKUTI ORANG TUA / PEKERJAAN..."
                                    class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border border-black/[0.03] rounded-2xl text-[11px] font-black outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition-all uppercase tracking-wider placeholder:opacity-30"></textarea>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                <iconify-icon icon="lucide:calendar" class="text-primary-acorn"></iconify-icon>
                                Berlaku Hingga
                            </label>
                            <input type="date" x-model="form.valid_until"
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border border-black/[0.03] rounded-2xl text-[11px] font-black outline-none focus:ring-4 focus:ring-primary-acorn/10 focus:border-primary-acorn transition-all uppercase tracking-wider">
                            <p class="text-[9px] text-slate-400 font-bold uppercase ml-1 italic">* Dokumen tidak akan
                                berlaku setelah tanggal ini.</p>
                        </div>

                        <button @click="submitApproval()"
                            class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 py-5 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:shadow-primary-acorn/20 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50 disabled:translate-y-0 disabled:shadow-none flex items-center justify-center gap-3">
                            <iconify-icon x-show="!loading" icon="lucide:check-circle" class="text-lg"></iconify-icon>
                            <iconify-icon x-show="loading" icon="lucide:loader-2"
                                class="text-lg animate-spin"></iconify-icon>
                            <span x-text="loading ? 'MEMPROSES...' : 'SETUJUI PERMOHONAN'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div x-show="showRejectModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

            <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/10"
                @click.outside="showRejectModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-10"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="p-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center border border-rose-500/10">
                                <iconify-icon icon="lucide:file-x" class="text-2xl"></iconify-icon>
                            </div>
                            <div>
                                <h3
                                    class="text-lg font-black text-slate-800 dark:text-white tracking-tight uppercase leading-none">
                                    Tolak Permohonan</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2"
                                    x-text="selectedRequest.name"></p>
                            </div>
                        </div>
                        <button @click="showRejectModal = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <iconify-icon icon="lucide:x" class="text-2xl"></iconify-icon>
                        </button>
                    </div>

                    <div class="space-y-8">
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                <iconify-icon icon="lucide:message-square" class="text-rose-500"></iconify-icon>
                                Alasan Penolakan
                            </label>
                            <textarea x-model="form.reason" rows="4" placeholder="CONTOH: DATA TIDAK SESUAI FAKTA LAPANGAN..."
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border border-black/[0.03] rounded-2xl text-[11px] font-black outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all uppercase tracking-wider placeholder:opacity-30"></textarea>
                        </div>

                        <button @click="submitRejection()"
                            class="w-full bg-rose-600 text-white py-5 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50 disabled:translate-y-0 disabled:shadow-none flex items-center justify-center gap-3">
                            <iconify-icon x-show="!loading" icon="lucide:x-circle" class="text-lg"></iconify-icon>
                            <iconify-icon x-show="loading" icon="lucide:loader-2"
                                class="text-lg animate-spin"></iconify-icon>
                            <span x-text="loading ? 'MEMPROSES...' : 'TOLAK PERMOHONAN SEKARANG'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function requestQueue() {
                return {
                    showApproveModal: false,
                    showRejectModal: false,
                    selectedRequest: {
                        id: null,
                        name: '',
                        type: ''
                    },
                    loading: false,
                    form: {
                        income_range: '',
                        purpose: '',
                        destination_address: '',
                        reason_move: '',
                        valid_until: '',
                        reason: ''
                    },

                    openApproveModal(id, name, type, notes) {
                        this.selectedRequest = {
                            id,
                            name,
                            type
                        };
                        this.form.income_range = '';
                        this.form.purpose = type === 'DOMICILE' ? notes : '';

                        // Parse multiline notes for MOVE
                        if (type === 'MOVE' && notes && notes.includes('TUJUAN: ')) {
                            const parts = notes.split('\nCATATAN: ');
                            this.form.destination_address = parts[0].replace('TUJUAN: ', '');
                            this.form.reason_move = parts[1] || '';
                        } else {
                            this.form.destination_address = '';
                            this.form.reason_move = type === 'MOVE' ? notes : '';
                        }

                        // Default valid until based on type
                        // Poverty: 6 months, Domicile: 3 months, Move: 1 month
                        const d = new Date();
                        if (type === 'POVERTY') {
                            d.setMonth(d.getMonth() + 6);
                        } else if (type === 'MOVE') {
                            d.setMonth(d.getMonth() + 1);
                        } else {
                            d.setMonth(d.getMonth() + 3);
                        }
                        this.form.valid_until = d.toISOString().split('T')[0];
                        this.showApproveModal = true;
                    },

                    async submitApproval() {
                        // Validation logic
                        if (!this.form.valid_until) {
                            this.showWarning('TANGGAL WAJIB DIISI', 'Harap tentukan masa berlaku dokumen.');
                            return;
                        }
                        ns

                        if (this.selectedRequest.type === 'POVERTY' && !this.form.income_range) {
                            this.showWarning('DATA TIDAK LENGKAP', 'Harap pilih rentang penghasilan.');
                            return;
                        }

                        if (this.selectedRequest.type === 'DOMICILE' && !this.form.purpose) {
                            this.showWarning('DATA TIDAK LENGKAP', 'Harap isi keperluan domisili.');
                            return;
                        }

                        if (this.selectedRequest.type === 'MOVE') {
                            if (!this.form.destination_address || !this.form.reason_move) {
                                this.showWarning('DATA TIDAK LENGKAP', 'Harap isi alamat tujuan dan alasan pindah.');
                                return;
                            }
                        }

                        if (this.loading) return;
                        this.loading = true;

                        try {
                            const response = await fetch(`/layanan/permohonan/${this.selectedRequest.id}/approve`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(this.selectedRequest.type === 'MOVE' ? {
                                    ...this.form,
                                    reason: this.form.reason_move
                                } : this.form)
                            });

                            const json = await response.json();
                            if (response.ok && json.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'BERHASIL!',
                                    text: json.message,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'rounded-[1.5rem] border-none shadow-2xl',
                                        confirmButton: 'bg-primary-acorn text-white rounded-xl px-8 py-3 font-bold'
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(json.message || 'Gagal menyetujui permohonan.');
                            }
                        } catch (e) {
                            Swal.fire('Error', e.message, 'error');
                        } finally {
                            this.loading = false;
                        }
                    },

                    showWarning(title, text) {
                        Swal.fire({
                            icon: 'warning',
                            title: title,
                            text: text,
                            customClass: {
                                popup: 'rounded-[1.5rem] border-none shadow-2xl',
                                confirmButton: 'bg-amber-500 text-white rounded-xl px-8 py-3 font-bold'
                            },
                            buttonsStyling: false
                        });
                    },

                    openRejectModal(id, name) {
                        this.selectedRequest = {
                            id,
                            name
                        };
                        this.form.reason = '';
                        this.showRejectModal = true;
                    },

                    async submitRejection() {
                        if (!this.form.reason) {
                            this.showWarning('ALASAN WAJIB DIISI', 'Berikan alasan penolakan untuk petugas Front Office.');
                            return;
                        }

                        if (this.loading) return;
                        this.loading = true;

                        try {
                            const response = await fetch(`/layanan/permohonan/${this.selectedRequest.id}/reject`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    reason: this.form.reason
                                })
                            });

                            const json = await response.json();
                            if (response.ok && json.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'DITOLAK',
                                    text: 'Permohonan telah berhasil ditolak.',
                                    customClass: {
                                        popup: 'rounded-[1.5rem] border-none shadow-2xl',
                                        confirmButton: 'bg-rose-500 text-white rounded-xl px-8 py-3 font-bold'
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(json.message || 'Gagal menolak permohonan.');
                            }
                        } catch (e) {
                            Swal.fire('Error', e.message, 'error');
                        } finally {
                            this.loading = false;
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection

@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Back Button -->
    <div class="flex items-center gap-3">
        <a href="{{ route('cms-complaints.index') }}" class="flex items-center justify-center h-10 w-10 bg-white dark:bg-slate-900 border border-black/[0.05] dark:border-white/[0.05] rounded-xl text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 shadow-sm transition-all">
            <iconify-icon icon="lucide:arrow-left" class="text-xl"></iconify-icon>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Detail Pengaduan</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                ID Pengaduan: #{{ $cmsComplaint->id }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Complaint Content Left -->
        <div class="lg:col-span-8 space-y-6">
            <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                <!-- Header Meta -->
                <div class="flex flex-wrap justify-between items-start gap-4 pb-6 border-b border-black/[0.04] dark:border-white/[0.04] mb-6">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subjek</span>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase mt-1">
                            {{ $cmsComplaint->subject }}
                        </h2>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block text-right">Status Saat Ini</span>
                        <div class="mt-1">
                            @if($cmsComplaint->status === 'pending')
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-amber-500/10 text-amber-600 border border-amber-500/10 uppercase tracking-widest">
                                    PENDING
                                </span>
                            @elseif($cmsComplaint->status === 'processed')
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-blue-500/10 text-blue-600 border border-blue-500/10 uppercase tracking-widest">
                                    DIPROSES
                                </span>
                            @elseif($cmsComplaint->status === 'resolved')
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-500/10 text-emerald-600 border border-emerald-500/10 uppercase tracking-widest">
                                    SELESAI
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Complaint Message Body -->
                <div class="space-y-4">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Isi Pengaduan / Keluhan</span>
                        <div class="mt-2 text-sm text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/40 p-4 rounded-xl border border-black/[0.02] dark:border-white/[0.02] whitespace-pre-line">
                            {{ $cmsComplaint->content }}
                        </div>
                    </div>

                    <!-- Attachment -->
                    @if($cmsComplaint->attachment)
                        <div class="pt-4">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Berkas Lampiran / Bukti Pendukung</span>
                            <div class="mt-2">
                                @php
                                    $fileExt = pathinfo($cmsComplaint->attachment, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp

                                @if($isImage)
                                    <div class="mt-2 max-w-md border border-black/[0.05] dark:border-white/[0.05] rounded-xl overflow-hidden shadow-sm">
                                        <img src="{{ asset('storage/' . $cmsComplaint->attachment) }}" class="w-full h-auto object-cover max-h-96" alt="Lampiran Pengaduan">
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $cmsComplaint->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-acorn hover:underline">
                                            <iconify-icon icon="lucide:external-link" class="text-sm"></iconify-icon>
                                            Buka Gambar Asli di Tab Baru
                                        </a>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-black/[0.02] dark:border-white/[0.02] w-fit">
                                        <iconify-icon icon="lucide:file-text" class="text-3xl text-primary-acorn"></iconify-icon>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800 dark:text-white">Dokumen Pendukung (.{{ $fileExt }})</p>
                                            <a href="{{ asset('storage/' . $cmsComplaint->attachment) }}" target="_blank" class="text-[10px] font-bold text-primary-acorn hover:underline uppercase tracking-wider block mt-0.5">
                                                Unduh Lampiran
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Response History (If already replied) -->
            @if($cmsComplaint->reply)
                <div class="premium-card bg-emerald-50/50 dark:bg-emerald-950/10 rounded-2xl p-6 border border-emerald-500/10 shadow-sm">
                    <div class="flex items-start gap-3">
                        <iconify-icon icon="lucide:message-square-quote" class="text-2xl text-emerald-600 mt-1"></iconify-icon>
                        <div class="space-y-2 flex-1">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xs font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">Tanggapan Resmi Admin</h3>
                                <span class="text-[9px] text-emerald-700/60 font-bold uppercase tracking-wider">
                                    {{ $cmsComplaint->replied_at ? $cmsComplaint->replied_at->translatedFormat('d M Y H:i') : '-' }}
                                </span>
                            </div>
                            <p class="text-sm text-emerald-800/90 dark:text-emerald-300 leading-relaxed bg-white/40 dark:bg-slate-900/40 p-4 rounded-xl border border-emerald-500/5 whitespace-pre-line">
                                {{ $cmsComplaint->reply }}
                            </p>
                            <p class="text-[9px] text-emerald-600/70 font-semibold uppercase tracking-wider">
                                Oleh: {{ $cmsComplaint->repliedBy ? $cmsComplaint->repliedBy->name : 'System/Admin' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Meta Information & Response Form Right -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Submitter Info -->
            <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-5 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 pb-2 border-b border-black/[0.03] dark:border-white/[0.03]">Profil Pengadu</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 flex-shrink-0">
                            <iconify-icon icon="lucide:user" class="text-lg"></iconify-icon>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider line-clamp-1">{{ $cmsComplaint->name }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Warga Pengadu</p>
                        </div>
                    </div>
                    <div class="space-y-2 pt-2 border-t border-black/[0.02] dark:border-white/[0.02]">
                        <div>
                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block">Email</span>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-semibold break-all">{{ $cmsComplaint->email }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block">No. Telepon / WA</span>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-semibold">{{ $cmsComplaint->phone }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block">Tanggal Masuk</span>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-semibold">{{ $cmsComplaint->created_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply / Actions Form -->
            @can('cms.complaints.reply')
            <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-5 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 pb-2 border-b border-black/[0.03] dark:border-white/[0.03]">Tindak Lanjut & Tanggapan</h3>
                <form action="{{ route('cms-complaints.update', $cmsComplaint->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Status Dropdown -->
                    <div>
                        <label for="status" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Ubah Status</label>
                        <select name="status" id="status" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn shadow-inner">
                            <option value="pending" {{ $cmsComplaint->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ $cmsComplaint->status === 'processed' ? 'selected' : '' }}>Diproses (Processed)</option>
                            <option value="resolved" {{ $cmsComplaint->status === 'resolved' ? 'selected' : '' }}>Selesai (Resolved)</option>
                        </select>
                    </div>

                    <!-- Reply/Response Field -->
                    <div>
                        <label for="reply" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggapan / Catatan</label>
                        <textarea name="reply" id="reply" rows="5" placeholder="Tuliskan respon resmi atau catatan tindakan dari instansi untuk laporan ini..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn shadow-inner leading-relaxed">{{ old('reply', $cmsComplaint->reply) }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary-acorn/10 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <iconify-icon icon="lucide:save" class="text-sm"></iconify-icon>
                            Simpan Tanggapan
                        </button>
                    </div>
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection

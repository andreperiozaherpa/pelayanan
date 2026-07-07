@extends('layouts.app')

@section('title', 'Daftar Pengaduan Masyarakat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Pengaduan Masyarakat</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola dan tanggapi laporan, aspirasi, atau pengaduan warga.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-4 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
        <form action="{{ route('cms-complaints.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <a href="{{ route('cms-complaints.index', ['status' => 'all', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition {{ !request('status') || request('status') === 'all' ? 'bg-primary-acorn text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200/50' }}">
                    Semua
                </a>
                <a href="{{ route('cms-complaints.index', ['status' => 'pending', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200/50' }}">
                    Pending
                </a>
                <a href="{{ route('cms-complaints.index', ['status' => 'processed', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition {{ request('status') === 'processed' ? 'bg-blue-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200/50' }}">
                    Diproses
                </a>
                <a href="{{ route('cms-complaints.index', ['status' => 'resolved', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition {{ request('status') === 'resolved' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200/50' }}">
                    Selesai
                </a>
            </div>

            <!-- Search input -->
            <div class="relative w-full md:w-80">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari pengaduan..." 
                       class="w-full pl-4 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn shadow-inner">
                <button type="submit" class="absolute right-3 top-3 text-slate-400 hover:text-primary-acorn transition">
                    <iconify-icon icon="lucide:search" class="text-base"></iconify-icon>
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pengadu & Kontak</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Laporan</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tanggal</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                    @forelse($complaints as $complaint)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                            <!-- Name & Contacts -->
                            <td class="py-4 px-6">
                                <div>
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">
                                        {{ $complaint->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-semibold mt-1">
                                        <iconify-icon icon="lucide:mail" class="inline text-xs mr-1"></iconify-icon> {{ $complaint->email }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                        <iconify-icon icon="lucide:phone" class="inline text-xs mr-1"></iconify-icon> {{ $complaint->phone }}
                                    </p>
                                </div>
                            </td>

                            <!-- Subject & Content Preview -->
                            <td class="py-4 px-6">
                                <div class="max-w-xs md:max-w-md">
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider line-clamp-1">
                                        {{ $complaint->subject }}
                                    </p>
                                    <p class="text-[10px] text-slate-500 font-medium mt-1 line-clamp-1">
                                        {{ $complaint->content }}
                                    </p>
                                </div>
                            </td>

                            <!-- Date Created -->
                            <td class="py-4 px-6 text-center">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                                    {{ $complaint->created_at->translatedFormat('d M Y H:i') }}
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6 text-center">
                                @if($complaint->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-amber-500/10 text-amber-600 border border-amber-500/10 uppercase tracking-widest">
                                        PENDING
                                    </span>
                                @elseif($complaint->status === 'processed')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-blue-500/10 text-blue-600 border border-blue-500/10 uppercase tracking-widest">
                                        DIPROSES
                                    </span>
                                @elseif($complaint->status === 'resolved')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-500/10 text-emerald-600 border border-emerald-500/10 uppercase tracking-widest">
                                        SELESAI
                                    </span>
                                @endif
                            </td>

                            <!-- Action buttons -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('cms-complaints.show', $complaint->id) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Lihat & Balas Pengaduan">
                                        <iconify-icon icon="lucide:eye" class="text-lg"></iconify-icon>
                                    </a>

                                    @can('cms.complaints.delete')
                                    <form action="{{ route('cms-complaints.destroy', $complaint->id) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition" title="Hapus Pengaduan">
                                            <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Tidak ada pengaduan ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($complaints->hasPages())
        <div class="px-6 py-4 border-t border-black/[0.02] dark:border-white/[0.02]">
            {{ $complaints->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                Swal.fire({
                    title: 'KONFIRMASI HAPUS',
                    text: "Apakah Anda yakin ingin menghapus data pengaduan ini secara permanen?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YA, HAPUS',
                    cancelButtonText: 'BATAL',
                    customClass: {
                        popup: 'rounded-[2.5rem] border-none shadow-2xl p-8',
                        confirmButton: 'bg-rose-500 text-white rounded-2xl px-10 py-4 font-black text-[10px] uppercase tracking-widest transition-all hover:scale-105 mr-2',
                        cancelButton: 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl px-10 py-4 font-black text-[10px] uppercase tracking-widest transition-all hover:scale-105'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

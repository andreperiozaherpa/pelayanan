@extends('layouts.app')

@section('title', 'Kategori Artikel CMS')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Kategori Artikel</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola kategori pengelompokan artikel blog website.</p>
        </div>
        @can('cms.articles.create')
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('cms-categories.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                <iconify-icon icon="lucide:folder-plus" class="text-lg"></iconify-icon>
                Tambah Kategori
            </a>
        </div>
        @endcan
    </div>

    <!-- Table -->
    <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Slug</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori Induk</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Jumlah Artikel</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                            <td class="py-4 px-6">
                                <span class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider group-hover:text-primary-acorn transition">
                                    {{ $category->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <code class="text-[10px] font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                                    {{ $category->slug }}
                                </code>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $category->parent ? $category->parent->name : '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-indigo-500/10 text-indigo-500 border border-indigo-500/10 uppercase tracking-widest">
                                    {{ $category->articles_count }} Artikel
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @can('cms.articles.edit')
                                    <a href="{{ route('cms-categories.edit', $category->id) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Edit Kategori">
                                        <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                                    </a>
                                    @endcan

                                    @can('cms.articles.delete')
                                    <form action="{{ route('cms-categories.destroy', $category->id) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition" title="Hapus Kategori">
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
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Tidak ada kategori ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-black/[0.02] dark:border-white/[0.02]">
            {{ $categories->links() }}
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
                    text: "Apakah Anda yakin ingin menghapus kategori ini?",
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

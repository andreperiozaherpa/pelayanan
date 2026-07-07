@extends('layouts.app')

@section('title', 'Menu Navigasi CMS')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Menu Navigasi</h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola struktur navigasi website utama, menu drop-down, dan tautan halaman statis.</p>
            </div>
            @can('cms.menus.create')
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('cms-menus.create') }}"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        <iconify-icon icon="lucide:plus-circle" class="text-lg"></iconify-icon>
                        Tambah Menu
                    </a>
                </div>
            @endcan
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-black/[0.02] dark:border-white/[0.02]">
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Menu</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Icon</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tautan / URL</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Halaman Statis</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Urutan</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.02] dark:divide-white/[0.02]">
                        @forelse($menus as $menu)
                            <!-- Parent Menu Row -->
                            <tr class="bg-slate-50/30 dark:bg-slate-800/10 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition group font-black">
                                <td class="py-4 px-6 flex items-center gap-2">
                                    <span class="text-[11px] uppercase tracking-wider text-slate-800 dark:text-white">
                                        {{ $menu->title }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[8px] bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase">Utama</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($menu->icon)
                                        <iconify-icon icon="{{ $menu->icon }}" class="text-lg text-slate-500"></iconify-icon>
                                    @else
                                        <span class="text-slate-400 font-medium">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <code class="text-[10px] font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                                        {{ $menu->url }}
                                    </code>
                                </td>
                                <td class="py-4 px-6">
                                    @if($menu->page)
                                        <span class="text-[10px] font-bold text-primary-acorn">
                                            {{ $menu->page->title }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-medium text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center text-[11px] font-bold text-slate-600 dark:text-slate-400">
                                    {{ $menu->order }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if ($menu->is_active)
                                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-500/10 text-emerald-500 border border-emerald-500/10 uppercase tracking-widest">
                                            AKTIF
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-slate-500/10 text-slate-500 border border-slate-500/10 uppercase tracking-widest">
                                            NONAKTIF
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @can('cms.menus.edit')
                                            <a href="{{ route('cms-menus.edit', $menu->id) }}"
                                                class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition"
                                                title="Edit Menu">
                                                <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                                            </a>
                                        @endcan
                                        @can('cms.menus.delete')
                                            <form action="{{ route('cms-menus.destroy', $menu->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu utama ini beserta seluruh sub-menunya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition"
                                                    title="Hapus Menu">
                                                    <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <!-- Children/Submenus Rows -->
                            @foreach($menu->children as $child)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                                    <td class="py-3 px-6 pl-12 flex items-center gap-2">
                                        <iconify-icon icon="lucide:corner-down-right" class="text-slate-400"></iconify-icon>
                                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            {{ $child->title }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        @if($child->icon)
                                            <iconify-icon icon="{{ $child->icon }}" class="text-base text-slate-400"></iconify-icon>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        <code class="text-[10px] font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                                            {{ $child->url }}
                                        </code>
                                    </td>
                                    <td class="py-3 px-6">
                                        @if($child->page)
                                            <span class="text-[10px] font-semibold text-primary-acorn">
                                                {{ $child->page->title }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center text-xs text-slate-500 dark:text-slate-400">
                                        {{ $child->order }}
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        @if ($child->is_active)
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/10 uppercase">
                                                AKTIF
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-slate-500/10 text-slate-500 border border-slate-500/10 uppercase">
                                                NONAKTIF
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            @can('cms.menus.edit')
                                                <a href="{{ route('cms-menus.edit', $child->id) }}"
                                                    class="p-1.5 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded transition"
                                                    title="Edit Submenu">
                                                    <iconify-icon icon="lucide:edit-3" class="text-base"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('cms.menus.delete')
                                                <form action="{{ route('cms-menus.destroy', $child->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus submenu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded transition"
                                                        title="Hapus Submenu">
                                                        <iconify-icon icon="lucide:trash-2" class="text-base"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    Belum ada menu navigasi yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($menus->hasPages())
                <div class="p-6 border-t border-black/[0.02] dark:border-white/[0.02]">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

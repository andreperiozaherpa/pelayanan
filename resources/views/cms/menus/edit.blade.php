@extends('layouts.app')

@section('title', 'Ubah Menu Navigasi')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 1rem !important;
            height: 52px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 1.25rem !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(15, 23, 42, 0.5) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding-left: 0 !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 13px !important;
            right: 15px !important;
        }

        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 1rem !important;
            padding: 0.5rem !important;
            z-index: 10001 !important;
            overflow: hidden !important;
        }

        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #f59e0b !important;
            color: #ffffff !important;
            border-radius: 0.5rem !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
            border-radius: 0.5rem !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 0.5rem !important;
            padding: 6px 12px !important;
            outline: none !important;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #0f172a !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header & Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('cms-menus.index') }}" class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Ubah Menu Navigasi</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui data menu navigasi, ubah tautan halaman, atau urutan tampilnya.</p>
        </div>
    </div>

    <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
        <div class="p-8 sm:p-10">
            <form action="{{ route('cms-menus.update', $cmsMenu->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama / Label Menu</label>
                        <input type="text" name="title" value="{{ old('title', $cmsMenu->title) }}" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="Contoh: Profil" />
                        @error('title')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Icon (Iconify Name - Opsional)</label>
                        <input type="text" name="icon" value="{{ old('icon', $cmsMenu->icon) }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="Contoh: lucide:info" />
                        @error('icon')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Menu -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Menu Induk (Parent)</label>
                        <select name="parent_id" id="parent_id"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">
                            <option value="">-- Menu Utama (Tidak Ada Induk) --</option>
                            @foreach($parentMenus as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $cmsMenu->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Target Buka Tautan</label>
                        <select name="target" id="target" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">
                            <option value="_self" {{ old('target', $cmsMenu->target) == '_self' ? 'selected' : '' }}>Tab Sama (_self)</option>
                            <option value="_blank" {{ old('target', $cmsMenu->target) == '_blank' ? 'selected' : '' }}>Tab Baru (_blank)</option>
                        </select>
                        @error('target')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Type -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Tipe Tautan</label>
                        <select id="link_type"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">
                            <option value="page" {{ old('cms_page_id', $cmsMenu->cms_page_id) ? 'selected' : '' }}>Halaman Statis CMS</option>
                            <option value="custom" {{ !old('cms_page_id', $cmsMenu->cms_page_id) ? 'selected' : '' }}>Tautan / URL Kustom</option>
                        </select>
                    </div>

                    <!-- Cms Page Dropdown -->
                    <div id="page_wrapper" class="space-y-2 col-span-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Pilih Halaman Statis</label>
                        <select name="cms_page_id" id="cms_page_id"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">
                            <option value="">-- Pilih Halaman Statis --</option>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}" {{ old('cms_page_id', $cmsMenu->cms_page_id) == $page->id ? 'selected' : '' }}>
                                    {{ $page->title }} (Slug: {{ $page->slug }})
                                </option>
                            @endforeach
                        </select>
                        @error('cms_page_id')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Custom URL Input -->
                    <div id="url_wrapper" class="space-y-2 col-span-2 hidden">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">URL / Tautan Kustom</label>
                        <input type="text" name="url" id="url" value="{{ old('url', $cmsMenu->url) }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="Contoh: https://google.com atau /kontak" />
                        @error('url')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 col-span-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="2"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="Contoh: Menampilkan daftar layanan administrasi warga...">{{ old('description', $cmsMenu->description) }}</textarea>
                        @error('description')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Urutan (Order)</label>
                        <input type="number" name="order" value="{{ old('order', $cmsMenu->order) }}" required min="0"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                        @error('order')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active Switch -->
                    <div class="flex flex-col justify-end">
                        <label class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-900/30 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl hover:border-primary-acorn/50 transition-all cursor-pointer group">
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $cmsMenu->is_active) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-primary-acorn focus:ring-primary-acorn/20 bg-white dark:bg-slate-900 transition-all">
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-white group-hover:text-primary-acorn transition-colors uppercase tracking-tight">Aktifkan Menu</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Tampilkan menu ini di navigasi publik website.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-6 border-t border-black/[0.03] dark:border-white/[0.03] flex justify-end gap-3">
                    <a href="{{ route('cms-menus.index') }}" class="px-6 py-2.5 rounded-xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-acorn hover:bg-primary-acorn/90 text-white px-8 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        Simpan Menu
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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            const parentSelect = $('#parent_id');
            const pageSelect = $('#cms_page_id');
            const targetSelect = $('#target');
            const linkTypeSelect = $('#link_type');

            parentSelect.select2({
                width: '100%',
                placeholder: '-- Menu Utama (Tidak Ada Induk) --',
                allowClear: true
            });

            pageSelect.select2({
                width: '100%',
                placeholder: '-- Pilih Halaman Statis --',
                allowClear: true
            });

            targetSelect.select2({
                width: '100%',
                minimumResultsForSearch: -1
            });

            linkTypeSelect.select2({
                width: '100%',
                minimumResultsForSearch: -1
            });

            const pageWrapper = document.getElementById('page_wrapper');
            const urlWrapper = document.getElementById('url_wrapper');
            const cmsPageId = document.getElementById('cms_page_id');
            const urlInput = document.getElementById('url');

            function toggleLinkFields() {
                if (linkTypeSelect.val() === 'page') {
                    pageWrapper.classList.remove('hidden');
                    urlWrapper.classList.add('hidden');
                    cmsPageId.setAttribute('required', 'required');
                    urlInput.removeAttribute('required');
                } else {
                    pageWrapper.classList.add('hidden');
                    urlWrapper.classList.remove('hidden');
                    cmsPageId.removeAttribute('required');
                    pageSelect.val(null).trigger('change');
                    urlInput.setAttribute('required', 'required');
                }
            }

            linkTypeSelect.on('change', function() {
                toggleLinkFields();
            });
            toggleLinkFields(); // Init on load
        });
    </script>
@endpush
@endsection

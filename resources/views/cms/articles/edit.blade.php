@extends('layouts.app')

@section('title', 'Edit Artikel')

@section('content')
    <div class="space-y-6">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-4">
            <a href="{{ route('cms-articles.index') }}"
                class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Edit Artikel</h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui informasi artikel blog
                    "{{ $cmsArticle->title }}".</p>
            </div>
        </div>

        <form action="{{ route('cms-articles.update', $cmsArticle->id) }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            @method('PUT')

            <!-- Left: Main content -->
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Judul
                            Artikel</label>
                        <input type="text" name="title" value="{{ old('title', $cmsArticle->title) }}" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[12px] font-black transition-all dark:text-white"
                            placeholder="Masukkan judul artikel..." />
                        @error('title')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Konten
                            Artikel</label>
                        <textarea name="content" id="editor" rows="15" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="Tulis isi artikel...">{{ old('content', $cmsArticle->content) }}</textarea>
                        @error('content')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEO Metadata Section -->
                <div
                    class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                    <div>
                        <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Optimasi SEO
                            (Search Engine Optimization)</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Konfigurasikan
                            informasi agar artikel mudah ditemukan di Google/Bing.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Meta Title -->
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">SEO
                                Title (Meta Title)</label>
                            <input type="text" name="meta_title"
                                value="{{ old('meta_title', $cmsArticle->seo?->meta_title) }}"
                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                                placeholder="Judul untuk mesin pencari..." />
                        </div>

                        <!-- Canonical URL -->
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Canonical
                                URL</label>
                            <input type="url" name="canonical_url"
                                value="{{ old('canonical_url', $cmsArticle->seo?->canonical_url) }}"
                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                                placeholder="https://example.com/artikel-sumber" />
                        </div>
                    </div>

                    <!-- Meta Description -->
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Meta
                            Description</label>
                        <textarea name="meta_description" rows="3"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="Deskripsi singkat konten artikel untuk hasil pencarian Google...">{{ old('meta_description', $cmsArticle->seo?->meta_description) }}</textarea>
                    </div>

                    <!-- Meta Keywords -->
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Meta
                            Keywords</label>
                        <input type="text" name="meta_keywords"
                            value="{{ old('meta_keywords', $cmsArticle->seo?->meta_keywords) }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="keyword1, keyword2, keyword3..." />
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar options -->
            <div class="space-y-6">
                <div
                    class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                    <!-- Status & Save -->
                    <div class="space-y-4">
                        <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Publikasi
                        </h2>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Status
                                Dokumen</label>
                            <select name="status" required
                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">
                                <option value="draft"
                                    {{ old('status', $cmsArticle->status) === 'draft' ? 'selected' : '' }}>DRAFT / TANGGUH
                                </option>
                                <option value="published"
                                    {{ old('status', $cmsArticle->status) === 'published' ? 'selected' : '' }}>PUBLISHED /
                                    TAYANG</option>
                                <option value="archived"
                                    {{ old('status', $cmsArticle->status) === 'archived' ? 'selected' : '' }}>ARCHIVED /
                                    ARSIP</option>
                            </select>
                        </div>

                        <div class="pt-4 flex flex-col gap-2">
                            <button type="submit"
                                class="w-full bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                                Perbarui Artikel
                            </button>
                            <a href="{{ route('cms-articles.index') }}"
                                class="w-full text-center py-3 rounded-2xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                    <!-- Category -->
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Kategori
                            Artikel</label>
                        <select name="category_id" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">
                            <option value="">-- PILIH KATEGORI --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $cmsArticle->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Featured Image -->
                    <div class="space-y-2">
                        @include('cms.partials.dropzone-upload', [
                            'id' => 'featured_image',
                            'name' => 'featured_image',
                            'label' => 'Gambar Utama (Featured Image)',
                            'value' => old('featured_image', $cmsArticle->featured_image),
                            'placeholder' => 'Klik atau seret gambar utama ke sini',
                            'folder' => 'articles',
                        ])
                        @error('featured_image')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-tight">{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <style>
            .note-editor.note-frame {
                border: 1px solid rgba(0, 0, 0, 0.05) !important;
                border-radius: 1rem !important;
                background-color: transparent !important;
                overflow: hidden;
            }

            .dark .note-editor.note-frame {
                border: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .note-toolbar {
                background-color: #f1f5f9 !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
                padding: 0.75rem !important;
            }

            .dark .note-toolbar {
                background-color: #1e293b !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .note-editable {
                background-color: #f8fafc !important;
                color: #0f172a !important;
                min-height: 400px;
                font-family: inherit;
                font-size: 0.875rem;
                padding: 1.5rem !important;
            }

            .dark .note-editable {
                background-color: rgba(15, 23, 42, 0.5) !important;
                color: #f1f5f9 !important;
            }

            .note-btn {
                background-color: transparent !important;
                border: 1px solid transparent !important;
                color: #475569 !important;
                padding: 0.4rem 0.6rem !important;
                border-radius: 0.375rem !important;
                transition: all 0.2s;
            }

            .dark .note-btn {
                color: #cbd5e1 !important;
            }

            .note-btn:hover {
                background-color: rgba(0, 0, 0, 0.05) !important;
            }

            .dark .note-btn:hover {
                background-color: rgba(255, 255, 255, 0.08) !important;
            }

            .note-btn.active {
                background-color: #475569 !important;
                color: #fff !important;
            }

            .dark .note-btn.active {
                background-color: #334155 !important;
                color: #fff !important;
            }

            .note-dropdown-menu {
                background-color: #fff !important;
                border: 1px solid rgba(0, 0, 0, 0.05) !important;
                border-radius: 0.75rem !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            }

            .dark .note-dropdown-menu {
                background-color: #1e293b !important;
                border-color: rgba(255, 255, 255, 0.05) !important;
                color: #fff !important;
            }

            .note-dropdown-item:hover {
                background-color: #f1f5f9 !important;
            }

            .dark .note-dropdown-item:hover {
                background-color: #334155 !important;
            }

            .note-modal {
                z-index: 1050 !important;
            }

            .note-modal-backdrop {
                z-index: 1040 !important;
                background-color: rgba(0, 0, 0, 0.5) !important;
            }

            .note-modal-content {
                background-color: #fff !important;
                border-radius: 1.25rem !important;
                border: 1px solid rgba(0, 0, 0, 0.05) !important;
            }

            .dark .note-modal-content {
                background-color: #0f172a !important;
                border-color: rgba(255, 255, 255, 0.1) !important;
                color: #fff !important;
            }

            .note-modal-header {
                border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            }

            .dark .note-modal-header {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .note-modal-footer {
                border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
            }

            .dark .note-modal-footer {
                border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .note-form-control {
                background-color: #f8fafc !important;
                border: 1px solid rgba(0, 0, 0, 0.05) !important;
                border-radius: 0.5rem !important;
            }

            .dark .note-form-control {
                background-color: rgba(15, 23, 42, 0.5) !important;
                border-color: rgba(255, 255, 255, 0.05) !important;
                color: #fff !important;
            }

            .note-statusbar {
                display: none !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#editor').summernote({
                    placeholder: 'Tulis konten artikel di sini...',
                    tabsize: 2,
                    height: 400,
                    dialogsInBody: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onImageUpload: function(files) {
                            uploadImage(files[0], $(this));
                        }
                    }
                });

                function uploadImage(file, $el) {
                    let data = new FormData();
                    data.append("file", file);
                    data.append("folder", "articles");

                    $el.summernote('saveRange');

                    $.ajax({
                        url: "{{ route('cms-media.upload') }}",
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        data: data,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success && response.url) {
                                $el.summernote('restoreRange');
                                $el.summernote('insertImage', response.url);
                            } else {
                                alert(response.message || 'Gagal mengunggah gambar');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr, status, error);
                            alert('Gagal mengunggah gambar: ' + (xhr.responseJSON?.message || error));
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection

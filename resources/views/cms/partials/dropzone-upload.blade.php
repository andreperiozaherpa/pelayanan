@push('styles')
    @once
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
        <style>
            .premium-dropzone {
                border: 2px dashed rgba(247, 164, 0, 0.15) !important;
                border-radius: 1.5rem !important;
                background-color: rgba(248, 250, 252, 0.4) !important;
                transition: all 0.3s ease !important;
                padding: 1.5rem !important;
                min-height: 100px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                flex-direction: column !important;
                cursor: pointer !important;
            }
            .dark .premium-dropzone {
                background-color: rgba(15, 23, 42, 0.4) !important;
                border-color: rgba(255, 255, 255, 0.08) !important;
            }
            .premium-dropzone:hover {
                border-color: #f7a400 !important;
                background-color: rgba(247, 164, 0, 0.02) !important;
            }
            .dark .premium-dropzone:hover {
                background-color: rgba(247, 164, 0, 0.05) !important;
            }
            .premium-dropzone .dz-message {
                margin: 0 !important;
                text-align: center !important;
            }
            .premium-dropzone .dz-preview {
                margin: 0.5rem !important;
                font-size: 11px !important;
            }
            .premium-dropzone .dz-preview .dz-image {
                border-radius: 1rem !important;
                width: 80px !important;
                height: 80px !important;
            }
            .premium-dropzone .dz-preview .dz-details,
            .premium-dropzone .dz-preview .dz-progress,
            .premium-dropzone .dz-preview .dz-error-message,
            .premium-dropzone .dz-preview .dz-success-mark,
            .premium-dropzone .dz-preview .dz-error-mark {
                display: none !important;
            }
        </style>
    @endonce
@endpush

@push('scripts')
    @once
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <script>
            Dropzone.autoDiscover = false;
        </script>
    @endonce
@endpush

<div class="space-y-2">
    @if(isset($label))
        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">{{ $label }}</label>
    @endif
    
    <div class="flex flex-col sm:flex-row gap-4 items-center">
        <!-- Dropzone Area -->
        <div id="dz-{{ $id }}" class="premium-dropzone dropzone w-full flex-1">
            <div class="dz-message flex flex-col items-center gap-2">
                <iconify-icon icon="lucide:cloud-upload" class="text-2xl text-primary-acorn"></iconify-icon>
                <span class="dz-button text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    {{ $placeholder ?? 'Klik atau Seret Berkas ke Sini' }}
                </span>
            </div>
        </div>

        <!-- Hidden input for file path -->
        <input type="hidden" name="{{ $name }}" id="dz-input-{{ $id }}" value="{{ $value ?? '' }}">

        <!-- Preview Thumbnail -->
        <div id="dz-preview-container-{{ $id }}" class="h-20 w-20 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900 border border-black/[0.05] dark:border-white/[0.05] flex-shrink-0 flex items-center justify-center relative {{ ($value ?? '') ? '' : 'hidden' }}">
            <img id="dz-preview-img-{{ $id }}" src="{{ ($value ?? '') ? (Str::startsWith($value, 'http') ? $value : asset('storage/' . $value)) : '#' }}" class="h-full w-full object-cover" alt="Preview">
            <button type="button" id="dz-clear-{{ $id }}" class="absolute top-1 right-1 h-5 w-5 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center transition shadow-lg" title="Hapus Berkas">
                <iconify-icon icon="lucide:x" class="text-xs"></iconify-icon>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('dz-input-{{ $id }}');
        const previewContainer = document.getElementById('dz-preview-container-{{ $id }}');
        const previewImg = document.getElementById('dz-preview-img-{{ $id }}');
        const clearBtn = document.getElementById('dz-clear-{{ $id }}');

        const dz = new Dropzone('#dz-{{ $id }}', {
            url: '{{ route("cms-media.upload") }}',
            maxFiles: 1,
            paramName: 'file',
            acceptedFiles: 'image/*',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            params: {
                folder: '{{ $folder ?? "general" }}'
            },
            init: function() {
                this.on('success', function(file, response) {
                    if (response.success) {
                        input.value = response.path;
                        previewImg.src = response.url;
                        previewContainer.classList.remove('hidden');
                        this.removeFile(file);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Berkas berhasil diunggah',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Gagal mengunggah berkas'
                        });
                        this.removeFile(file);
                    }
                });
                this.on('error', function(file, errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: typeof errorMessage === 'object' ? errorMessage.message : errorMessage
                    });
                    this.removeFile(file);
                });
            }
        });

        clearBtn.addEventListener('click', function() {
            input.value = '';
            previewContainer.classList.add('hidden');
            previewImg.src = '#';
        });
    });
</script>
@endpush

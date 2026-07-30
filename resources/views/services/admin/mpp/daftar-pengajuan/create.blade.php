@extends('layouts.app')

@section('content')
    <div x-data="mppRequestApp()" class="w-full space-y-10 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                @if ($mppService->logo)
                    <div class="h-16 w-16 rounded-2xl bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] p-2 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('storage/' . $mppService->logo) }}" alt="{{ $mppService->name }}" class="max-h-full max-w-full object-contain">
                    </div>
                @else
                    <div class="h-16 w-16 rounded-2xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center text-2xl shadow-inner">
                        <iconify-icon icon="lucide:file-signature"></iconify-icon>
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                        {{ $mppService->name }}
                    </h1>
                    <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                        {{ $mppService->description ?: 'Pengajuan permohonan baru untuk pelayanan dinamis.' }}
                    </p>
                </div>
            </div>

            <a href="{{ route('mpp-requests.index') }}"
                class="flex items-center gap-2 px-6 py-3.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-acorn transition shadow-sm">
                <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <form action="{{ route('mpp-requests.store', $mppService->slug) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto space-y-8" @submit="loading = true">
            @csrf

            <div class="premium-card">
                <div class="p-8 sm:p-12 space-y-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                            <iconify-icon icon="lucide:file-text" class="text-xl"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Formulir Permohonan</h2>
                            <p class="text-[10px] text-slate-500 font-medium">Isi semua field yang bertanda <span class="text-red-500">*</span></p>
                        </div>
                    </div>

                    @foreach ($mppService->fields ?? [] as $field)
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                {{ $field['label'] ?? $field['name'] ?? 'Field' }}
                                @if (!empty($field['required']))
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if (($field['type'] ?? 'text') === 'textarea')
                                <textarea name="form_data[{{ $field['name'] }}]"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all"
                                    {{ !empty($field['required']) ? 'required' : '' }}>{{ old('form_data.' . $field['name']) }}</textarea>
                            @elseif (($field['type'] ?? 'text') === 'file')
                                <div class="dropzone-container">
                                    <div id="dz-{{ $field['name'] }}" class="dropzone border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-6 text-center cursor-pointer hover:border-primary-acorn/50 transition">
                                        <div class="dz-message">
                                            <iconify-icon icon="lucide:upload" class="text-2xl text-slate-300"></iconify-icon>
                                            <p class="text-[10px] font-bold text-slate-400 mt-1">Klik atau drop file di sini</p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="form_data[{{ $field['name'] }}]" value="{{ old('form_data.' . $field['name']) }}">
                                </div>
                            @elseif (($field['type'] ?? 'text') === 'select' && !empty($field['options']))
                                <select name="form_data[{{ $field['name'] }}]"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all appearance-none"
                                    {{ !empty($field['required']) ? 'required' : '' }}>
                                    <option value="">Pilih {{ $field['label'] ?? '...' }}</option>
                                    @foreach ($field['options'] as $opt)
                                        <option value="{{ $opt['value'] ?? $opt }}" @selected(old('form_data.' . $field['name']) == ($opt['value'] ?? $opt))>
                                            {{ $opt['label'] ?? $opt }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ $field['type'] ?? 'text' }}" name="form_data[{{ $field['name'] }}]"
                                    value="{{ old('form_data.' . $field['name']) }}"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all"
                                    {{ !empty($field['required']) ? 'required' : '' }}>
                            @endif

                            @error('form_data.' . $field['name'])
                                <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-4 justify-end">
                <a href="{{ route('mpp-requests.index') }}"
                    class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="px-8 py-3.5 bg-primary-acorn hover:bg-primary-acorn/90 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5 flex items-center gap-2"
                    x-bind:disabled="loading">
                    <iconify-icon icon="lucide:send" class="text-lg"></iconify-icon>
                    <span x-text="loading ? 'Mengirim...' : 'Ajukan Permohonan'"></span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
    <script>
        if (typeof Dropzone !== 'undefined') Dropzone.autoDiscover = false;

        function mppRequestApp() {
            return {
                loading: false,
                init() {
                    this.waitForDropzone();
                },
                waitForDropzone() {
                    if (typeof Dropzone !== 'undefined') {
                        this.initDropzones();
                        return;
                    }
                    setTimeout(() => this.waitForDropzone(), 100);
                },
                initDropzones() {
                    const fields = @json($mppService->fields ?? []);
                    fields.forEach(field => {
                        if (field.type !== 'file') return;
                        const fieldName = field.name;
                        const dzElement = document.getElementById('dz-' + fieldName);
                        if (!dzElement) return;

                        new Dropzone('#dz-' + fieldName, {
                            url: '{{ route("mpp-requests.upload") }}',
                            maxFiles: 1,
                            paramName: 'file',
                            acceptedFiles: '.pdf,.jpg,.jpeg,.png',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            init: function() {
                                this.on('success', function(file, response) {
                                    if (response.success) {
                                        document.querySelector(`input[name="form_data[${fieldName}]"]`).value = response.path;
                                    }
                                });
                                this.on('error', function(file, message) {
                                    alert('Upload gagal: ' + (message.message || message));
                                });
                            }
                        });
                    });
                }
            }
        }
    </script>
@endpush

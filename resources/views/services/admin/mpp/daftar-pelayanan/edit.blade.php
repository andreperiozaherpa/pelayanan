@extends('layouts.app')

@section('title', 'Edit Pelayanan MPP')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('mpp-services.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-acorn transition shadow-sm">
            <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
            <span>Kembali</span>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Edit Pelayanan</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui konfigurasi pelayanan {{ $mppService->name }}.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('mpp-services.update', $mppService) }}" enctype="multipart/form-data" class="space-y-6" x-data='mppServiceForm(@json($mppService->fields ?? []))'>
        @csrf
        @method('PUT')

        <div class="premium-card">
            <div class="p-8 sm:p-12 space-y-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                        <iconify-icon icon="lucide:settings" class="text-xl"></iconify-icon>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Informasi Dasar</h2>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama Pelayanan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $mppService->name) }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all @error('name') ring-2 ring-red-500 @enderror"
                        placeholder="Contoh: Sertifikasi Halal" required>
                    @error('name')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Anjungan</label>
                    <select name="anjungan_id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all appearance-none @error('anjungan_id') ring-2 ring-red-500 @enderror">
                        <option value="">-- Pilih Anjungan (Opsional) --</option>
                        @foreach($anjungans as $anjungan)
                            <option value="{{ $anjungan->id }}" {{ old('anjungan_id', $mppService->anjungan_id) == $anjungan->id ? 'selected' : '' }}>
                                {{ $anjungan->code }} - {{ $anjungan->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('anjungan_id')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all @error('description') ring-2 ring-red-500 @enderror"
                        placeholder="Deskripsi pelayanan...">{{ old('description', $mppService->description) }}</textarea>
                    @error('description')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Logo</label>
                    @if($mppService->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $mppService->logo) }}" alt="Logo" class="h-12 w-12 rounded-lg object-cover border">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all @error('logo') ring-2 ring-red-500 @enderror">
                    <input type="hidden" name="logo" value="{{ $mppService->logo }}">
                    @error('logo')
                        <p class="text-[9px] font-bold text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $mppService->is_active ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-primary-acorn focus:ring-primary-acorn/30">
                    <label for="is_active" class="text-[11px] font-bold text-slate-600 dark:text-slate-300">Aktif</label>
                </div>
            </div>
        </div>

        <div class="premium-card">
            <div class="p-8 sm:p-12 space-y-8">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center">
                            <iconify-icon icon="lucide:list" class="text-xl"></iconify-icon>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Kolom Formulir</h2>
                            <p class="text-[10px] text-slate-500 font-medium">Atur field yang akan muncul di form pengajuan</p>
                        </div>
                    </div>
                    <button type="button" @click="addField()"
                        class="flex items-center gap-1.5 px-4 py-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white rounded-xl font-black text-[9px] uppercase tracking-widest transition-all shadow-sm">
                        <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                        Tambah Kolom
                    </button>
                </div>

                <template x-for="(field, index) in fields" :key="index">
                    <div class="p-5 bg-slate-50 dark:bg-slate-800/30 rounded-xl border border-black/[0.03] dark:border-white/[0.03] space-y-4 relative">
                        <button type="button" @click="removeField(index)" class="absolute top-3 right-3 p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition" title="Hapus kolom">
                            <iconify-icon icon="lucide:x" class="text-lg"></iconify-icon>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Label <span class="text-red-500">*</span></label>
                                <input type="text" x-model="field.label" :name="'fields['+index+'][label]'"
                                    class="w-full px-3 py-2.5 bg-white dark:bg-slate-800 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all"
                                    placeholder="Nama lengkap" required>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tipe Input <span class="text-red-500">*</span></label>
                                <select x-model="field.type" :name="'fields['+index+'][type]'"
                                    class="w-full px-3 py-2.5 bg-white dark:bg-slate-800 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all appearance-none">
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="select">Select</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="file">File</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5" x-show="field.type === 'select'">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Opsi Pilihan</label>
                            <div class="space-y-2">
                                <template x-for="(opt, oi) in (field.options || [])" :key="oi">
                                    <div class="flex items-center gap-2">
                                        <input type="text" x-model="field.options[oi]" :name="'fields['+index+'][options][]'"
                                            class="flex-1 px-3 py-2 bg-white dark:bg-slate-800 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn transition-all"
                                            placeholder="Opsi">
                                        <button type="button" @click="field.options.splice(oi, 1)" class="p-1.5 text-slate-400 hover:text-red-500">
                                            <iconify-icon icon="lucide:minus-circle" class="text-base"></iconify-icon>
                                        </button>
                                    </div>
                                </template>
                                <button type="button" @click="if(!field.options) field.options = []; field.options.push('')"
                                    class="text-[9px] font-bold text-primary-acorn hover:underline">
                                    + Tambah opsi
                                </button>
                            </div>
                        </div>

                        <label class="flex items-center gap-2 text-[10px] font-bold text-slate-600 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" x-model="field.required" :name="'fields['+index+'][required]'" value="1"
                                class="w-3.5 h-3.5 rounded border-slate-300 text-primary-acorn focus:ring-primary-acorn/30">
                            Required / Wajib diisi
                        </label>
                        <input type="hidden" :name="'fields['+index+'][id]'" x-bind:value="field.id || ''">
                    </div>
                </template>

                <p x-show="fields.length === 0" class="text-center py-8 text-[10px] font-bold text-slate-400">
                    Belum ada kolom formulir. Klik "Tambah Kolom" untuk mulai.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 justify-end">
            <a href="{{ route('mpp-services.index') }}"
                class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3.5 bg-primary-acorn hover:bg-primary-acorn/90 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                <iconify-icon icon="lucide:save" class="text-lg inline-block mr-1.5"></iconify-icon>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function mppServiceForm(fields = []) {
        return {
            fields: fields,
            addField() {
                this.fields.push({
                    label: '',
                    type: 'text',
                    required: false,
                    options: []
                });
            },
            removeField(index) {
                this.fields.splice(index, 1);
            }
        }
    }
</script>
@endpush

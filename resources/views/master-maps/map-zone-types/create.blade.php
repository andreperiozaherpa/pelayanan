@extends('layouts.app')

@section('title', 'Tambah Tipe Zonasi')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Tambah Tipe Zonasi</h1>
        <p class="text-xs text-slate-500 font-medium tracking-tight">Definisikan klasifikasi tipe kawasan baru beserta atribut petanya.</p>
    </div>

    <div class="premium-card p-6 md:p-8 max-w-xl">
        <form action="{{ route('map-zone-types.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Nama Klasifikasi</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Kawasan Industri, Pemukiman Penduduk" required
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                @error('name') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Warna Peta</label>
                <div class="flex gap-2">
                    <input type="color" name="color" value="#f97316"
                        class="h-11 w-14 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl cursor-pointer" />
                    <input type="text" id="color-hex" placeholder="#f97316" readonly
                        class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-800/80 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-mono font-bold dark:text-white" />
                </div>
                @error('color') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Keterangan / Kategori Deskripsi</label>
                <textarea name="description" placeholder="Penjelasan singkat mengenai tipe zonasi ini..." rows="3"
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="flex-1 bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all">
                    Simpan Tipe
                </button>
                <a href="{{ route('map-zone-types.index') }}" class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.querySelector('input[name="color"]');
        const colorHex = document.getElementById('color-hex');
        colorHex.value = colorInput.value;
        colorInput.addEventListener('input', (e) => {
            colorHex.value = e.target.value;
        });
    });
</script>
@endpush

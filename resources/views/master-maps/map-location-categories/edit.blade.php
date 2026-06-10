@extends('layouts.app')

@section('title', 'Ubah Kategori Lokasi')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Ubah Kategori Lokasi</h1>
        <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui informasi nama, ikon, atau warna kategori penanda.</p>
    </div>

    <div class="premium-card p-6 md:p-8 max-w-xl">
        <form action="{{ route('map-location-categories.update', $mapLocationCategory->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $mapLocationCategory->name) }}" required
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                @error('name') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Kode Ikon (Iconify)</label>
                <input type="text" name="icon" value="{{ old('icon', $mapLocationCategory->icon) }}" required
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white placeholder-slate-400" />
                <p class="text-[9px] text-slate-400 mt-1">Gunakan kode ikon dari <a href="https://iconify.design" target="_blank" class="text-blue-500 font-bold underline">Iconify</a>.</p>
                @error('icon') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block">Warna Penanda</label>
                <div class="flex gap-2">
                    <input type="color" name="color" value="{{ old('color', $mapLocationCategory->color) }}"
                        class="h-11 w-14 bg-slate-50 dark:bg-slate-800/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl cursor-pointer" />
                    <input type="text" id="color-hex" placeholder="#3b82f6" readonly
                        class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-800/80 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[11px] font-mono font-bold dark:text-white" />
                </div>
                @error('color') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="flex-1 bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all">
                    Perbarui Kategori
                </button>
                <a href="{{ route('map-location-categories.index') }}" class="px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
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

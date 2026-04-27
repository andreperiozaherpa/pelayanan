@extends('layouts.app')

@section('title', 'Edit Data Role')

@section('content')
<div class="space-y-6">
    <!-- Header & Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('roles.index') }}" class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Edit Role</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui informasi dan izin akses untuk role <strong>{{ $role->name }}</strong>.</p>
        </div>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="p-8 sm:p-10">
            <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-8">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama Role</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider" />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="2"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider">{{ old('description', $role->description) }}</textarea>
                    </div>
                </div>

                <!-- Permissions Selection -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Daftar Izin Akses (Permissions)</label>
                        <button type="button" onclick="const checks = document.querySelectorAll('input[type=checkbox]'); const allChecked = Array.from(checks).every(c => c.checked); checks.forEach(c => c.checked = !allChecked)"
                            class="text-[10px] font-black text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">
                            Pilih Semua / Batal
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($permissions as $permission)
                        <label class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-900/30 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl hover:border-primary-acorn/50 transition-all cursor-pointer group">
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                    class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-primary-acorn focus:ring-primary-acorn/20 bg-white dark:bg-slate-900 transition-all"
                                    {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-white group-hover:text-primary-acorn transition-colors uppercase tracking-tight">{{ $permission->name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $permission->slug }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('permissions')
                        <p class="text-[10px] text-rose-500 font-bold mt-2 uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6 border-t border-black/[0.03] dark:border-white/[0.03] flex justify-end gap-3">
                    <a href="{{ route('roles.index') }}" class="px-6 py-2.5 rounded-xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary-acorn hover:bg-primary-acorn/90 text-white px-8 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

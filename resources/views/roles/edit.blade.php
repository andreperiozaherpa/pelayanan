@extends('layouts.app')

@section('title', 'Edit Data Role')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 dark:hover:text-white transition mb-6 group">
        <svg class="h-5 w-5 transform group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="font-bold text-sm text-slate-600 dark:text-slate-400">Kembali ke Daftar</span>
    </a>

    <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-[2.5rem] shadow-2xl overflow-hidden">
        <div class="p-8 sm:p-12">
            <div class="mb-10">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Edit Role</h1>
                <p class="text-slate-500 mt-2 font-medium">Perbarui informasi dan izin akses untuk role <strong>{{ $role->name }}</strong>.</p>
            </div>

            <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-8">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Role</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="2"
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold transition-all dark:text-white">{{ old('description', $role->description) }}</textarea>
                    </div>
                </div>

                <!-- Permissions Selection -->
                <div class="space-y-4 pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Daftar Izin Akses (Permissions)</label>
                        <button type="button" @click="const checks = document.querySelectorAll('input[type=checkbox]'); const allChecked = Array.from(checks).every(c => c.checked); checks.forEach(c => c.checked = !allChecked)"
                            class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:text-indigo-600 transition">
                            Pilih Semua / Batal
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($permissions as $permission)
                        <label class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900/30 border border-slate-200 dark:border-white/5 rounded-2xl hover:border-emerald-500/50 transition-all cursor-pointer group">
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                    class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 text-emerald-500 focus:ring-emerald-500/20 bg-white dark:bg-slate-900 transition-all"
                                    {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900 dark:text-white group-hover:text-emerald-500 transition-colors">{{ $permission->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $permission->slug }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('permissions')
                        <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-10">
                    <button type="submit" class="w-full py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black uppercase tracking-[0.2em] shadow-2xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Perbarui Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Data Role')

@section('content')
    <div class="space-y-6">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-4">
            <a href="{{ route('roles.index') }}"
                class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Edit Role</h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui informasi dan izin akses untuk role
                    <strong>{{ $role->name }}</strong>.</p>
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
                            <label
                                class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama
                                Role</label>
                            <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider" />
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Deskripsi
                                (Opsional)</label>
                            <textarea name="description" rows="2"
                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider">{{ old('description', $role->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Permissions Selection -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-black/[0.05] dark:border-white/[0.05] pb-3">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Daftar Izin Akses (Permissions)</label>
                            <button type="button" onclick="const checks = document.querySelectorAll('input[name=\'permissions[]\']'); const allChecked = Array.from(checks).every(c => c.checked); checks.forEach(c => c.checked = !allChecked)"
                                class="text-[10px] font-black text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">
                                Pilih Semua / Batal
                            </button>
                        </div>

                        <div class="space-y-2">
                            @foreach($groupedPermissions as $groupName => $groupPermissions)
                                <div class="p-4 bg-slate-50/50 dark:bg-slate-900/10 border border-black/[0.02] dark:border-white/[0.02] rounded-2xl transition-all"
                                     x-data="{
                                         open: false,
                                         toggleGroup() {
                                             const checks = this.$el.querySelectorAll('input[type=checkbox]');
                                             const allChecked = Array.from(checks).every(c => c.checked);
                                             checks.forEach(c => c.checked = !allChecked);
                                         }
                                     }">
                                    <div class="flex items-center justify-between">
                                        <button type="button" @click="open = !open" 
                                            class="flex items-center gap-2 text-[11px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider hover:opacity-80 transition focus:outline-none">
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                            {{ $groupName }}
                                        </button>
                                        <button type="button" @click="toggleGroup()"
                                            class="text-[9px] font-bold text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">
                                            Pilih / Batal Modul
                                        </button>
                                    </div>
                                    <div x-show="open" class="grid grid-cols-1 md:grid-cols-2 gap-2 pt-3 border-t border-black/[0.03] dark:border-white/[0.03] mt-3">
                                        @foreach($groupPermissions as $permission)
                                            <label class="flex items-center gap-3 p-3 bg-white dark:bg-slate-900/30 border border-black/[0.03] dark:border-white/[0.03] rounded-xl hover:border-primary-acorn/50 transition-all cursor-pointer group">
                                                <div class="relative inline-flex items-center">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                        class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-700 text-primary-acorn focus:ring-primary-acorn/20 bg-white dark:bg-slate-900 transition-all"
                                                        {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-black text-slate-800 dark:text-white group-hover:text-primary-acorn transition-colors uppercase tracking-tight">
                                                        {{ $permission->name }}
                                                    </p>
                                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                                        {{ $permission->slug }}
                                                    </p>
                                                    @if ($permission->description)
                                                        <p class="text-[9px] font-medium text-slate-500 dark:text-slate-400 mt-1 leading-relaxed normal-case tracking-normal">
                                                            {{ $permission->description }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <p class="text-[10px] text-rose-500 font-bold mt-2 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-6 border-t border-black/[0.03] dark:border-white/[0.03] flex justify-end gap-3">
                        <a href="{{ route('roles.index') }}"
                            class="px-6 py-2.5 rounded-xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-primary-acorn hover:bg-primary-acorn/90 text-white px-8 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

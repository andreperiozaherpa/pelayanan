@extends('layouts.app')

@section('title', 'Edit Data User')

@section('content')
<div class="space-y-6">
    <!-- Header & Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="p-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-slate-400 hover:text-primary-acorn transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Edit User: {{ $user->name }}</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Perbarui profil atau hak akses pengguna sistem.</p>
        </div>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="p-8 sm:p-10">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-8" x-data="{ role: '{{ $user->role_id }}' }">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white uppercase tracking-wider" />
                    </div>

                    <!-- Role -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Role Pengguna</label>
                        <select name="role_id" x-model="role" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white appearance-none uppercase tracking-wider">
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}" data-slug="{{ $r->slug }}" {{ old('role_id', $user->role_id) == $r->id ? 'selected' : '' }}>
                                    {{ strtoupper($r->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Village (Conditional) -->
                    <div class="space-y-2" x-show="document.querySelector(`option[value='${role}']`)?.dataset.slug === 'operatordesa'" x-cloak x-transition>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Wilayah Desa</label>
                        <select name="desa_id"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white appearance-none uppercase tracking-wider">
                            <option value="">PILIH DESA...</option>
                            @foreach($villages as $v)
                                <option value="{{ $v->id }}" {{ old('desa_id', $user->desa_id) == $v->id ? 'selected' : '' }}>
                                    {{ strtoupper($v->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- OPD (Conditional) -->
                    <div class="space-y-2" x-show="document.querySelector(`option[value='${role}']`)?.dataset.slug === 'operatoropd'" x-cloak x-transition>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">OPD / Unit Kerja</label>
                        <select name="opd_id"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white appearance-none uppercase tracking-wider">
                            <option value="">PILIH OPD...</option>
                            @foreach($opds as $opd)
                                <option value="{{ $opd->id }}" {{ old('opd_id', $user->opd_id) == $opd->id ? 'selected' : '' }}>
                                    {{ strtoupper($opd->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Password Info -->
                <div class="p-5 bg-primary-acorn/[0.03] border border-primary-acorn/10 rounded-2xl flex items-start gap-3">
                    <svg class="h-4 w-4 text-primary-acorn mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-[10px] text-slate-600 dark:text-slate-400 font-bold uppercase tracking-tight leading-relaxed">Kosongkan kolom password di bawah jika Anda tidak ingin mengubah password pengguna saat ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $user->is_active ? 'checked' : '' }}>
                        <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-acorn"></div>
                    </label>
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Akun Aktif</span>
                </div>

                <div class="pt-6 border-t border-black/[0.03] dark:border-white/[0.03] flex justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="px-6 py-2.5 rounded-xl font-black text-[10px] text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition">
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

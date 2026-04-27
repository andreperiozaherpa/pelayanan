@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 dark:hover:text-white transition mb-6 group">
        <svg class="h-5 w-5 transform group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="font-bold text-sm">Kembali ke Daftar</span>
    </a>

    <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/50 dark:border-slate-700/50 rounded-[2.5rem] shadow-2xl overflow-hidden">
        <div class="p-8 sm:p-12">
            <div class="mb-10">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Tambah User</h1>
                <p class="text-slate-500 mt-2 font-medium">Lengkapi informasi di bawah untuk mendaftarkan pengguna baru ke sistem.</p>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-8" x-data="{ role: '' }">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold transition-all dark:text-white"
                            placeholder="Contoh: Budi Santoso" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold transition-all dark:text-white"
                            placeholder="budi@example.com" />
                    </div>

                    <!-- Role -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Role Pengguna</label>
                        <select name="role_id" x-model="role" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold transition-all dark:text-white appearance-none">
                            <option value="">Pilih Role...</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}" data-slug="{{ $r->slug }}" {{ old('role_id') == $r->id ? 'selected' : '' }}>
                                    {{ $r->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Village (Conditional) -->
                    <div class="space-y-2" x-show="document.querySelector(`option[value='${role}']`)?.dataset.slug === 'operatordesa'" x-cloak x-transition>
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Wilayah Desa</label>
                        <select name="desa_id"
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold transition-all dark:text-white appearance-none">
                            <option value="">Pilih Desa...</option>
                            @foreach($villages as $v)
                                <option value="{{ $v->id }}" {{ old('desa_id') == $v->id ? 'selected' : '' }}>
                                    {{ $v->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-5 py-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold transition-all dark:text-white" />
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center gap-3 pt-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Akun Aktif</span>
                </div>

                <div class="pt-10">
                    <button type="submit" class="w-full py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black uppercase tracking-[0.2em] shadow-2xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

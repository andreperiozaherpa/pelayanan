@extends('layouts.app')

@section('title', 'Edit Data User')

@php
    $initialRoleId = old('role_id', $user->role_id);
    $initialRole = $roles->firstWhere('id', $initialRoleId);
    $initialTipe = match ($initialRole?->slug) {
        'operatordesa' => 'wilayah',
        'operatoropd' => 'dinas',
        default => 'none',
    };
    $wilayahRoleId = $roles->firstWhere('slug', 'operatordesa')?->id;
    $dinasRoleId = $roles->firstWhere('slug', 'operatoropd')?->id;
@endphp

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
            border-radius: 1.25rem !important;
            min-height: 52px !important;
            height: auto !important;
            display: flex !important;
            align-items: center !important;
            padding: 0.75rem 1.25rem !important;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1) !important;
            box-shadow: none !important;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(15,23,42,0.5) !important;
            border-color: rgba(255,255,255,0.05) !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.1) !important;
            background-color: #ffffff !important;
        }
        .dark .select2-container--default.select2-container--open .select2-selection--single {
            background-color: #0f172a !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important;
            padding: 0 !important;
            line-height: 1.2 !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }
        .select2-container--default .select2-selection--single::after {
            content: '';
            width: 1.25rem;
            height: 1.25rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s ease;
        }
        .select2-container--default.select2-container--open .select2-selection--single::after {
            transform: translateY(-50%) rotate(180deg);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2310b981' stroke-width='3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E");
        }
        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important;
            padding: 0.5rem !important;
            z-index: 10001 !important;
            overflow: hidden !important;
        }
        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: rgba(255,255,255,0.05) !important;
        }
        .select2-container--default .select2-results__option {
            padding: 0.75rem 1rem !important;
            border-radius: 0.75rem !important;
            color: #334155 !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
        .dark .select2-container--default .select2-results__option {
            color: #e2e8f0 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #10b981 !important;
            color: #ffffff !important;
        }
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
        }
        .dark .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: rgba(16,185,129,0.15) !important;
            color: #6ee7b7 !important;
        }
        .select2-search--dropdown {
            padding: 0.5rem 0.25rem !important;
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.75rem !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
            padding: 0.625rem 1rem !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            outline: none !important;
        }
        .dark .select2-search--dropdown .select2-search__field {
            background-color: #0f172a !important;
            border-color: rgba(255,255,255,0.05) !important;
            color: #e2e8f0 !important;
        }
    </style>
@endpush

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

    @if ($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-800 dark:text-rose-300 rounded-2xl flex items-start gap-3" role="alert">
            <iconify-icon icon="lucide:alert-circle" class="text-xl text-rose-600 flex-shrink-0 mt-0.5"></iconify-icon>
            <div>
                <h4 class="font-bold text-sm">Terjadi Kesalahan Pengisian</h4>
                <ul class="list-disc list-inside text-xs text-rose-700/90 dark:text-rose-300/90 mt-1.5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="premium-card overflow-hidden">
        <div class="p-8 sm:p-10">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-8" x-data="{
                role: '{{ $initialRoleId }}',
                tipe: '{{ $initialTipe }}',
                roleSlug() {
                    return document.querySelector(`option[value='${this.role}']`)?.dataset.slug || '';
                },
                onRoleChange() {
                    const s = this.roleSlug();
                    if (s === 'operatordesa') this.tipe = 'wilayah';
                    else if (s === 'operatoropd') this.tipe = 'dinas';
                    else this.tipe = 'none';
                    this.$nextTick(() => initUserSelect2());
                },
                setTipe(t) {
                    this.tipe = t;
                    if (t === 'wilayah') this.role = '{{ $wilayahRoleId }}';
                    if (t === 'dinas') this.role = '{{ $dinasRoleId }}';
                    this.$nextTick(() => initUserSelect2());
                }
            }">
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
                        <select name="role_id" x-model="role" @change="onRoleChange()" required
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white appearance-none uppercase tracking-wider">
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}" data-slug="{{ $r->slug }}" {{ $initialRoleId == $r->id ? 'selected' : '' }}>
                                    {{ strtoupper($r->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipe Penempatan -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Jenis Penempatan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="setTipe('wilayah')" x-cloak
                                :class="tipe === 'wilayah' ? 'border-primary-acorn bg-primary-acorn/10 text-primary-acorn shadow-lg shadow-primary-acorn/10' : 'border-black/[0.03] dark:border-white/[0.03] bg-slate-50 dark:bg-slate-900/50 text-slate-500 hover:border-primary-acorn/40'"
                                class="flex flex-col items-center gap-1.5 px-2 py-3.5 border rounded-2xl transition-all cursor-pointer">
                                <iconify-icon icon="lucide:map-pin" class="text-lg"></iconify-icon>
                                <span class="text-[9px] font-black uppercase tracking-widest">Wilayah</span>
                            </button>
                            <button type="button" @click="setTipe('dinas')" x-cloak
                                :class="tipe === 'dinas' ? 'border-primary-acorn bg-primary-acorn/10 text-primary-acorn shadow-lg shadow-primary-acorn/10' : 'border-black/[0.03] dark:border-white/[0.03] bg-slate-50 dark:bg-slate-900/50 text-slate-500 hover:border-primary-acorn/40'"
                                class="flex flex-col items-center gap-1.5 px-2 py-3.5 border rounded-2xl transition-all cursor-pointer">
                                <iconify-icon icon="lucide:building-2" class="text-lg"></iconify-icon>
                                <span class="text-[9px] font-black uppercase tracking-widest">Dinas</span>
                            </button>
                            <button type="button" @click="setTipe('none')" x-cloak
                                :class="tipe === 'none' ? 'border-primary-acorn bg-primary-acorn/10 text-primary-acorn shadow-lg shadow-primary-acorn/10' : 'border-black/[0.03] dark:border-white/[0.03] bg-slate-50 dark:bg-slate-900/50 text-slate-500 hover:border-primary-acorn/40'"
                                class="flex flex-col items-center gap-1.5 px-2 py-3.5 border rounded-2xl transition-all cursor-pointer">
                                <iconify-icon icon="lucide:shield" class="text-lg"></iconify-icon>
                                <span class="text-[9px] font-black uppercase tracking-widest">Tidak Ada</span>
                            </button>
                        </div>
                    </div>

                    <!-- Wilayah (Kecamatan + Desa) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:col-span-2" x-show="tipe === 'wilayah'" x-cloak x-transition>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Wilayah Kecamatan *</label>
                            <select name="district_id" id="kecamatan" class="user-select2" data-placeholder="PILIH KECAMATAN...">
                                <option value="">PILIH KECAMATAN...</option>
                                @foreach($districts as $d)
                                    <option value="{{ $d->id }}" {{ old('district_id', $user->district_id) == $d->id ? 'selected' : '' }}>
                                        {{ strtoupper($d->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Wilayah Desa <span class="text-slate-400 normal-case">(opsional)</span></label>
                            <select name="desa_id" id="desa" class="user-select2" data-placeholder="PILIH DESA...">
                                <option value="">PILIH DESA...</option>
                                @foreach($villages as $v)
                                    <option value="{{ $v->id }}" data-district="{{ $v->district_id }}" {{ old('desa_id', $user->desa_id) == $v->id ? 'selected' : '' }}>
                                        {{ strtoupper($v->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('desa_id')
                                <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dinas (OPD) -->
                    <div class="space-y-2 md:col-span-2" x-show="tipe === 'dinas'" x-cloak x-transition>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">OPD / Unit Kerja *</label>
                        <select name="opd_id" id="opd" class="user-select2" data-placeholder="PILIH OPD...">
                            <option value="">PILIH OPD...</option>
                            @foreach($opds as $opd)
                                <option value="{{ $opd->id }}" {{ old('opd_id', $user->opd_id) == $opd->id ? 'selected' : '' }}>
                                    {{ strtoupper($opd->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('opd_id')
                            <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p>
                        @enderror
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

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const userVillages = @json($villages->map(fn ($v) => ['id' => (string) $v->id, 'name' => $v->name, 'district' => (string) $v->district_id]));

        function initUserSelect2() {
            $('.user-select2').each(function () {
                if ($(this).hasClass('select2-hidden-accessible')) $(this).select2('destroy');
                $(this).select2({
                    width: '100%',
                    placeholder: $(this).data('placeholder') || $(this).find('option:first').text(),
                });
            });
        }

        function renderDesaOptions() {
            const kecamatan = $('#kecamatan').val();
            const current = $('#desa').val();
            $('#desa').empty().append('<option value="">PILIH DESA...</option>');
            userVillages.forEach(function (v) {
                if (!kecamatan || v.district === kecamatan) {
                    const sel = v.id === current ? 'selected' : '';
                    $('#desa').append('<option value="' + v.id + '" ' + sel + '>' + v.name.toUpperCase() + '</option>');
                }
            });
            initUserSelect2();
        }

        document.addEventListener('DOMContentLoaded', function () {
            initUserSelect2();
            $('#kecamatan').on('change', renderDesaOptions);
            renderDesaOptions();
        });
    </script>
@endpush

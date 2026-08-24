@extends('layouts.app')

@section('title', 'Tukar Loket Petugas')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
            border-radius: 1.25rem !important;
            min-height: 72px !important;
            height: auto !important;
            display: flex !important;
            align-items: center !important;
            padding: 0.75rem 1.5rem !important;
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
            right: 1.5rem;
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
        .select2-role {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            line-height: 1.2;
        }
        .dark .select2-role {
            color: #64748b;
        }
        .select2-name {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #334155;
            line-height: 1.3;
            margin-top: 1px;
        }
        .dark .select2-name {
            color: #e2e8f0;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered .select2-role {
            font-size: 8px;
        }
    </style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('counter-users.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-acorn transition shadow-sm">
            <iconify-icon icon="lucide:arrow-left" class="text-lg"></iconify-icon>
            <span>Kembali</span>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Tukar Loket Petugas</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Tukar loket antara petugas yang sudah memiliki loket.</p>
        </div>
    </div>

    <div class="premium-card">
        <div class="p-8 sm:p-12">
            <div class="mb-8 p-5 bg-primary-acorn/5 border border-primary-acorn/15 rounded-2xl">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Petugas Saat Ini</p>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[10px] font-black uppercase tracking-wider rounded-lg border border-primary-acorn/20">
                        {{ $counterUser->counter->code }}
                    </span>
                    <div>
                        <p class="text-sm font-black text-slate-800 dark:text-white">{{ $counterUser->user->name }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ $counterUser->counter->name }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('counter-users.update', $counterUser) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-select label="Tukar dengan Petugas" name="swap_user_id" :selected="old('swap_user_id')" required select2>
                    <option value="">Pilih Petugas Lain</option>
                    @foreach($swapUsers as $user)
                        <option value="{{ $user->id }}" data-role="{{ $user->role?->name ?? 'Tanpa Role' }}">{{ $user->name }} ({{ $user->email }}) — {{ $user->role?->name ?? 'Tanpa Role' }}</option>
                    @endforeach
                </x-select>

                <div class="flex items-start gap-3 p-4 bg-amber-50 dark:bg-amber-500/5 border border-amber-200/60 dark:border-amber-500/15 rounded-xl">
                    <iconify-icon icon="lucide:repeat-2" class="text-lg text-amber-500 mt-0.5 shrink-0"></iconify-icon>
                    <p class="text-xs font-medium text-amber-700 dark:text-amber-400 leading-relaxed">
                        Loket petugas ini akan bertukar dengan loket petugas yang dipilih. Keduanya harus sudah memiliki loket.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="px-8 py-3 bg-primary-acorn hover:bg-primary-acorn/90 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                        <iconify-icon icon="lucide:repeat-2" class="text-lg inline-block mr-1.5"></iconify-icon>
                        Tukar Loket
                    </button>
                    <a href="{{ route('counter-users.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Batal</a>
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
        function formatUser(user) {
            if (!user.id) return user.text;
            var $option = $(user.element);
            var role = $option.data('role') || '';
            return $('<div><div class="select2-role">' + role + '</div><div class="select2-name">' + user.text.split(' — ')[0] + '</div></div>');
        }
        function formatUserSelection(user) {
            if (!user.id) return user.text;
            var $option = $(user.element);
            var role = $option.data('role') || '';
            var name = user.text.split(' — ')[0];
            return $('<div><div class="select2-role">' + role + '</div><div class="select2-name">' + name + '</div></div>');
        }
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2-hidden').select2({
                width: '100%',
                placeholder: function() {
                    return $(this).find('option:first').text();
                },
                templateResult: formatUser,
                templateSelection: formatUserSelection,
            });
        });
    </script>
@endpush

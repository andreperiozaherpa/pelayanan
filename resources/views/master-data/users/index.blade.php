@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <!-- Premium Style Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Manajemen User</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kelola hak akses dan otoritas pengguna sistem secara terpusat.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('users.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                <iconify-icon icon="lucide:user-plus" class="text-lg"></iconify-icon>
                Tambah User
            </a>
        </div>
    </div>

    <x-datatable 
        :records="$users" 
        search-placeholder="Cari Nama atau Email..."
        :search-value="request('search')"
    >
        <x-slot:thead>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Akun</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Role & Wilayah</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
            <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
        </x-slot:thead>

        @forelse($users as $user)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center text-[11px] font-black uppercase">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-wider">{{ $user->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 border border-black/5 transition group-hover:bg-primary-acorn/10 group-hover:text-primary-acorn group-hover:border-primary-acorn/10">
                        {{ $user->role->name ?? 'No Role' }}
                    </span>
                    @if($user->village)
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1 tracking-widest">📍 {{ $user->village->name }}</p>
                    @endif
                </td>
                <td class="py-4 px-6">
                    @if($user->is_active)
                        <x-badge variant="success" label="Active" />
                    @else
                        <x-badge variant="danger" label="Inactive" />
                    @endif
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Edit User">
                            <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                        </a>
                        @if($user->id !== Auth::id())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/5 rounded-lg transition" title="Delete User">
                                    <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-16 text-center">
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Tidak ada pengguna ditemukan.</p>
                </td>
            </tr>
        @endforelse
    </x-datatable>
</div>
@endsection

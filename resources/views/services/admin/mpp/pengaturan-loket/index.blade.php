@extends('layouts.app')

@section('title', 'Penugasan Loket Petugas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Penugasan Loket</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Atur penugasan petugas front office ke loket antrean MPP.</p>
        </div>
        <a href="{{ route('counter-users.create') }}"
            class="flex items-center gap-2 bg-primary-acorn hover:bg-primary-acorn/90 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
            <iconify-icon icon="lucide:plus-circle" class="text-lg"></iconify-icon>
            Tambah Penugasan
        </a>
    </div>

    <div class="premium-card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/20 border-b border-black/[0.03] dark:border-white/[0.03]">
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Loket</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Petugas</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Email</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Status</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition border-b border-black/[0.02] dark:border-white/[0.02]">
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 bg-primary-acorn/10 text-primary-acorn text-[9px] font-black uppercase tracking-wider rounded-lg border border-primary-acorn/20">
                                    {{ $assignment->counter->code }}
                                </span>
                                <span class="ml-2 text-[11px] font-bold text-slate-600 dark:text-slate-300">{{ $assignment->counter->name }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-[11px] font-black text-slate-800 dark:text-white">{{ $assignment->user->name }}</p>
                            </td>
                            <td class="py-4 px-6 text-[10px] font-medium text-slate-500">{{ $assignment->user->email }}</td>
                            <td class="py-4 px-6">
                                @if($assignment->is_active)
                                    <x-badge variant="success" label="Aktif" />
                                @else
                                    <x-badge variant="danger" label="Nonaktif" />
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('counter-users.edit', $assignment) }}" class="p-2 text-slate-400 hover:text-primary-acorn hover:bg-primary-acorn/5 rounded-lg transition" title="Edit">
                                        <iconify-icon icon="lucide:edit-3" class="text-lg"></iconify-icon>
                                    </a>
                                    <form method="POST" action="{{ route('counter-users.destroy', $assignment) }}" onsubmit="return confirm('Hapus penugasan ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition" title="Hapus">
                                            <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <iconify-icon icon="lucide:user-cog" class="text-3xl text-slate-300"></iconify-icon>
                                    <p class="text-sm font-medium text-slate-400">Belum ada penugasan loket</p>
                                    <a href="{{ route('counter-users.create') }}" class="text-xs font-bold text-primary-acorn hover:underline">Tambah Penugasan</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assignments->hasPages())
            <div class="px-6 py-4 border-t border-black/[0.03] dark:border-white/[0.03]">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

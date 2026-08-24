<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MppCounterUserController extends Controller
{
    public function index(): View
    {
        $assignments = CounterUser::with(['counter', 'user'])->latest()->paginate(20);

        return view('services.admin.mpp.pengaturan-loket.index', compact('assignments'));
    }

    public function create(): View
    {
        $assignedCounterIds = CounterUser::where('is_active', true)->pluck('counter_id');
        $assignedUserId = CounterUser::where('is_active', true)->pluck('user_id');

        $counters = Counter::whereNotIn('id', $assignedCounterIds)
            ->orderByRaw('CAST(code AS UNSIGNED)')
            ->get();

        $users = User::whereNotIn('id', $assignedUserId)
            ->eligibleForCounter()
            ->with('role')
            ->orderBy('name')
            ->get();

        return view('services.admin.mpp.pengaturan-loket.create', compact('counters', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'counter_id' => 'required|exists:mpp_counters,id',
            'user_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $counterTaken = CounterUser::where('counter_id', $validated['counter_id'])
            ->where('is_active', true)
            ->exists();

        if ($counterTaken) {
            return back()->withErrors(['counter_id' => 'Loket ini sudah ditugaskan ke petugas lain. Gunakan Tukar Loket untuk menukarnya.'])->withInput();
        }

        $userHasLoket = CounterUser::where('user_id', $validated['user_id'])
            ->where('is_active', true)
            ->exists();

        if ($userHasLoket) {
            return back()->withErrors(['user_id' => 'Petugas ini sudah memiliki loket. Gunakan Tukar Loket untuk menukarnya.'])->withInput();
        }

        $user = User::findOrFail($validated['user_id']);

        if (! $user->hasMppPermission()) {
            return back()->withErrors(['user_id' => 'Petugas ini tidak memiliki permission pengelolaan MPP.'])->withInput();
        }

        CounterUser::create([
            'counter_id' => $validated['counter_id'],
            'user_id' => $validated['user_id'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('counter-users.index')
            ->with('success', 'Penugasan loket berhasil ditambahkan.');
    }

    public function edit(CounterUser $counterUser): View
    {
        $swapUsers = User::where('id', '!=', $counterUser->user_id)
            ->whereHas('activeCounterAssignments', function ($query) use ($counterUser) {
                $query->where('counter_id', '!=', $counterUser->counter_id);
            })
            ->eligibleForCounter()
            ->with(['role', 'activeCounterAssignments.counter'])
            ->orderBy('name')
            ->get();

        return view('services.admin.mpp.pengaturan-loket.edit', compact('counterUser', 'swapUsers'));
    }

    public function update(Request $request, CounterUser $counterUser): RedirectResponse
    {
        $validated = $request->validate([
            'swap_user_id' => 'required|exists:users,id',
        ]);

        $swapUser = User::findOrFail($validated['swap_user_id']);

        if ($swapUser->id === $counterUser->user_id) {
            return back()->withErrors(['swap_user_id' => 'Petugas target sama dengan petugas saat ini.'])->withInput();
        }

        if (! $swapUser->hasMppPermission()) {
            return back()->withErrors(['swap_user_id' => 'Petugas target tidak memiliki permission pengelolaan MPP.'])->withInput();
        }

        $swapAssignment = $swapUser->activeCounterAssignments()
            ->where('counter_id', '!=', $counterUser->counter_id)
            ->first();

        if (! $swapAssignment) {
            return back()->withErrors(['swap_user_id' => 'Petugas target belum memiliki loket untuk ditukar.'])->withInput();
        }

        DB::transaction(function () use ($counterUser, $swapAssignment) {
            $currentCounterId = $counterUser->counter_id;

            $counterUser->update(['counter_id' => $swapAssignment->counter_id]);
            $swapAssignment->update(['counter_id' => $currentCounterId]);
        });

        return redirect()->route('counter-users.index')
            ->with('success', 'Loket berhasil ditukar antar petugas.');
    }

    public function destroy(CounterUser $counterUser): RedirectResponse
    {
        $counterUser->delete();

        return redirect()->route('counter-users.index')
            ->with('success', 'Penugasan loket berhasil dihapus.');
    }
}

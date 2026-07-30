<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $counters = Counter::orderBy('code')->get();
        $users = User::with('role')->orderBy('name')->get();

        return view('services.admin.mpp.pengaturan-loket.create', compact('counters', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'counter_id' => 'required|exists:mpp_counters,id',
            'user_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $exists = CounterUser::where('counter_id', $validated['counter_id'])
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['user_id' => 'User sudah ditugaskan ke loket ini.'])->withInput();
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
        $counters = Counter::orderBy('code')->get();
        $users = User::with('role')->orderBy('name')->get();

        return view('services.admin.mpp.pengaturan-loket.edit', compact('counterUser', 'counters', 'users'));
    }

    public function update(Request $request, CounterUser $counterUser): RedirectResponse
    {
        $validated = $request->validate([
            'counter_id' => 'required|exists:mpp_counters,id',
            'user_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $exists = CounterUser::where('counter_id', $validated['counter_id'])
            ->where('user_id', $validated['user_id'])
            ->where('id', '!=', $counterUser->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['user_id' => 'User sudah ditugaskan ke loket ini.'])->withInput();
        }

        $counterUser->update([
            'counter_id' => $validated['counter_id'],
            'user_id' => $validated['user_id'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('counter-users.index')
            ->with('success', 'Penugasan loket berhasil diperbarui.');
    }

    public function destroy(CounterUser $counterUser): RedirectResponse
    {
        $counterUser->delete();

        return redirect()->route('counter-users.index')
            ->with('success', 'Penugasan loket berhasil dihapus.');
    }
}

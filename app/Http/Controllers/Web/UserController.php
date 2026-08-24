<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreUserRequest;
use App\Http\Requests\Web\UpdateUserRequest;
use App\Http\Resources\Web\UserResource;
use App\Models\District;
use App\Models\Opd;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'village', 'opd']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        $users = $query->latest()->paginate(10);

        return view('master-data.users.index', [
            'users' => $users,
            'roles' => Role::all(),
            'villages' => Village::all(),
            'opds' => Opd::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-data.users.create', [
            'roles' => Role::all(),
            'districts' => District::orderBy('name')->get(),
            'villages' => Village::all(),
            'opds' => Opd::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $this->normalizeLocationFields($request->validated());
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        $user = User::create($validated);

        Audit::log('CREATE_USER', $user, $user->toArray());

        return redirect()->route('users.index')->with('success', "User {$user->name} berhasil dibuat.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('master-data.users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'districts' => District::orderBy('name')->get(),
            'villages' => Village::all(),
            'opds' => Opd::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $this->normalizeLocationFields($request->validated());

        $oldValue = $user->toArray();

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        Audit::log('UPDATE_USER', $user, $user->fresh()->toArray(), $oldValue);

        return redirect()->route('users.index')->with('success', "Data user {$user->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $oldValue = $user->toArray();
        $user->delete();

        Audit::log('DELETE_USER', $user, null, $oldValue);

        return redirect()->route('users.index')->with('success', "User {$user->name} telah dihapus.");
    }

    /**
     * Clear location fields that do not apply to the user's role.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizeLocationFields(array $validated): array
    {
        $role = Role::find($validated['role_id'] ?? null);

        if ($role?->slug === 'operatordesa') {
            $validated['opd_id'] = null;
        } elseif ($role?->slug === 'operatoropd') {
            $validated['desa_id'] = null;
            $validated['district_id'] = null;
        } else {
            $validated['desa_id'] = null;
            $validated['district_id'] = null;
            $validated['opd_id'] = null;
        }

        return $validated;
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreUserRequest;
use App\Http\Requests\Web\UpdateUserRequest;
use App\Http\Resources\Web\UserResource;
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
        $query = User::with(['role', 'village']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        $users = $query->latest()->paginate(10);

        return view('master-data.users.index', [
            'users' => $users,
            'roles' => Role::all(),
            'villages' => Village::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-data.users.create', [
            'roles' => Role::all(),
            'villages' => Village::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        $user = User::create($validated);

        Auth::user()->recordAuditLog(
            action: 'CREATE_USER',
            table: 'users',
            targetId: $user->id,
            newValue: $user->toArray()
        );

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
            'villages' => Village::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $oldValue = $user->toArray();

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        Auth::user()->recordAuditLog(
            action: 'UPDATE_USER',
            table: 'users',
            targetId: $user->id,
            oldValue: $oldValue,
            newValue: $user->fresh()->toArray()
        );

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

        Auth::user()->recordAuditLog(
            action: 'DELETE_USER',
            table: 'users',
            targetId: $user->id,
            oldValue: $oldValue
        );

        return redirect()->route('users.index')->with('success', "User {$user->name} telah dihapus.");
    }
}

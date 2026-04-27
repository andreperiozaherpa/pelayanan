<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreRoleRequest;
use App\Http\Requests\Web\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->get();

        return view('master-data.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('master-data.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $role = Role::create($validated);
        $role->permissions()->sync($request->permissions);

        Auth::user()->recordAuditLog(
            action: 'CREATE_ROLE',
            table: 'roles',
            targetId: $role->id,
            newValue: $role->load('permissions')->toArray()
        );

        return redirect()->route('roles.index')->with('success', "Role {$role->name} berhasil dibuat.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return $role->load('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        return view('master-data.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $oldValue = $role->load('permissions')->toArray();

        $validated = $request->validated();
        // Don't update slug for core roles if they exist
        if (! in_array($role->slug, ['superadmin', 'operatordesa', 'petugasfrontoffice'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $role->update($validated);
        $role->permissions()->sync($request->permissions);

        Auth::user()->recordAuditLog(
            action: 'UPDATE_ROLE',
            table: 'roles',
            targetId: $role->id,
            oldValue: $oldValue,
            newValue: $role->fresh()->load('permissions')->toArray()
        );

        return redirect()->route('roles.index')->with('success', "Role {$role->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $protectedRoles = ['superadmin', 'operatordesa', 'petugasfrontoffice'];

        if (in_array($role->slug, $protectedRoles)) {
            return redirect()->back()->with('error', 'Role sistem tidak dapat dihapus.');
        }

        if ($role->users()->exists()) {
            return redirect()->back()->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh pengguna.');
        }

        $oldValue = $role->load('permissions')->toArray();
        $role->delete();

        Auth::user()->recordAuditLog(
            action: 'DELETE_ROLE',
            table: 'roles',
            targetId: $role->id,
            oldValue: $oldValue
        );

        return redirect()->route('roles.index')->with('success', "Role {$role->name} telah dihapus.");
    }
}

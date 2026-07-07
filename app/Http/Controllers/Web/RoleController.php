<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreRoleRequest;
use App\Http\Requests\Web\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
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
        $groupedPermissions = $this->getGroupedPermissions();

        return view('master-data.roles.create', compact('groupedPermissions'));
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

        Audit::log('CREATE_ROLE', $role, $role->load('permissions')->toArray());

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
        $groupedPermissions = $this->getGroupedPermissions();
        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        return view('master-data.roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
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

        Audit::log('UPDATE_ROLE', $role, $role->fresh()->load('permissions')->toArray(), $oldValue);

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

        Audit::log('DELETE_ROLE', $role, null, $oldValue);

        return redirect()->route('roles.index')->with('success', "Role {$role->name} telah dihapus.");
    }

    /**
     * Get and group permissions by their module context.
     */
    private function getGroupedPermissions()
    {
        return Permission::all()->groupBy(function ($permission) {
            $slug = $permission->slug;
            if (str_starts_with($slug, 'cms.')) {
                $parts = explode('.', $slug);
                $module = $parts[1] ?? 'umum';
                $moduleNames = [
                    'articles' => 'CMS: Artikel',
                    'pages' => 'CMS: Halaman',
                    'banners' => 'CMS: Banner',
                    'faqs' => 'CMS: FAQ',
                    'testimonials' => 'CMS: Testimoni',
                    'teams' => 'CMS: Struktur Organisasi',
                    'settings' => 'CMS: Pengaturan',
                    'media' => 'CMS: Media',
                    'menus' => 'CMS: Menu Navigasi',
                    'complaints' => 'CMS: Pengaduan Masyarakat',
                ];

                return $moduleNames[$module] ?? 'CMS: '.ucfirst($module);
            }
            if (str_starts_with($slug, 'service.')) {
                return 'Pelayanan & Dokumen';
            }
            if (str_ends_with($slug, '.manage')) {
                $parts = explode('.', $slug);
                $module = $parts[0] ?? 'umum';
                $moduleNames = [
                    'citizens' => 'Pengelolaan Data Warga',
                    'users' => 'Pengguna & Otorisasi',
                    'roles' => 'Pengguna & Otorisasi',
                    'villages' => 'Pengelolaan Wilayah',
                    'districts' => 'Pengelolaan Wilayah',
                    'opds' => 'Pengelolaan OPD',
                    'maps' => 'Pengelolaan Peta SIBERUGO',
                ];

                return $moduleNames[$module] ?? 'Pengelolaan '.ucfirst($module);
            }

            $otherNames = [
                'audit.view' => 'Audit Log & Sistem',
                'system.manage' => 'Audit Log & Sistem',
                'reports.export' => 'Laporan & Ekspor',
            ];

            return $otherNames[$slug] ?? 'Lainnya';
        })->sortBy(function ($items, $key) {
            $order = [
                'Pelayanan & Dokumen' => 1,
                'Pengelolaan Data Warga' => 2,
                'Pengelolaan Wilayah' => 3,
                'Pengelolaan OPD' => 4,
                'Pengelolaan Peta SIBERUGO' => 5,
                'Pengguna & Otorisasi' => 6,
                'Audit Log & Sistem' => 7,
                'Laporan & Ekspor' => 8,
                'CMS: Menu Navigasi' => 9,
                'CMS: Pengaduan Masyarakat' => 10,
                'CMS: Artikel' => 11,
                'CMS: Halaman' => 12,
                'CMS: Banner' => 13,
                'CMS: FAQ' => 14,
                'CMS: Testimoni' => 15,
                'CMS: Struktur Organisasi' => 16,
                'CMS: Media' => 17,
                'CMS: Pengaturan' => 18,
            ];

            return $order[$key] ?? 99;
        });
    }
}

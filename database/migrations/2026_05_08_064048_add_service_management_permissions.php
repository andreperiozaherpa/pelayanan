<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Permissions
        $permissions = [
            [
                'name' => 'Manage Service Requests',
                'slug' => 'service.manage',
                'description' => 'Akses untuk mengelola (setuju/tolak) permintaan layanan dari desa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Report Service Usage',
                'slug' => 'service.report',
                'description' => 'Akses untuk melaporkan penggunaan layanan atau meminta verifikasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // 2. Assign Permissions to Roles
        $rolePermissions = [
            'operatordesa' => ['service.manage'],
            'petugasfrontoffice' => ['service.report'],
            'superadmin' => ['service.manage', 'service.report'],
        ];

        foreach ($rolePermissions as $roleSlug => $permissionSlugs) {
            $role = DB::table('roles')->where('slug', $roleSlug)->first();

            if ($role) {
                foreach ($permissionSlugs as $pSlug) {
                    $permission = DB::table('permissions')->where('slug', $pSlug)->first();
                    if ($permission) {
                        DB::table('permission_role')->updateOrInsert(
                            [
                                'role_id' => $role->id,
                                'permission_id' => $permission->id,
                            ]
                        );
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $pSlugs = ['service.manage', 'service.report'];

        $pIds = DB::table('permissions')->whereIn('slug', $pSlugs)->pluck('id');

        DB::table('permission_role')->whereIn('permission_id', $pIds)->delete();
        DB::table('permissions')->whereIn('slug', $pSlugs)->delete();
    }
};

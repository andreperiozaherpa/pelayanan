<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RBACSeeder extends Seeder
{
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            ['name' => 'Verify Poverty Status', 'slug' => 'poverty.verify'],
            ['name' => 'Print Verification Proof', 'slug' => 'poverty.print_proof'],
            ['name' => 'Report Service Given', 'slug' => 'service.report'],
            ['name' => 'Management Citizens', 'slug' => 'citizens.manage'],
            ['name' => 'View Audit Logs', 'slug' => 'audit.view'],
            ['name' => 'System Management', 'slug' => 'system.manage'],
            ['name' => 'Manage Users', 'slug' => 'users.manage'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage'],
            ['name' => 'Manage Villages', 'slug' => 'villages.manage'],
            ['name' => 'Manage Districts', 'slug' => 'districts.manage'],
            ['name' => 'Export Reports', 'slug' => 'reports.export'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // Define Roles & Assign Permissions
        $roles = [
            'SuperAdmin' => ['poverty.verify', 'poverty.print_proof', 'service.report', 'citizens.manage', 'audit.view', 'system.manage', 'users.manage', 'roles.manage', 'villages.manage', 'districts.manage', 'reports.export'],
            'OperatorDesa' => ['poverty.verify'],
            'PetugasFrontOffice' => ['poverty.verify', 'poverty.print_proof', 'service.report'],
            'Auditor' => ['audit.view', 'reports.export'],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::updateOrCreate(['slug' => strtolower($roleName)], ['name' => $roleName]);
            $permissionIds = Permission::whereIn('slug', $perms)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}

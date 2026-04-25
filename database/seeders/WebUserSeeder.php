<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WebUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('slug', 'superadmin')->first();
        $foRole = Role::where('slug', 'petugasfrontoffice')->first();

        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'admin@svldk.test'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'desa_id' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Create Front Office
        User::updateOrCreate(
            ['email' => 'fo@svldk.test'],
            [
                'name' => 'Front Office Staff',
                'password' => Hash::make('password'),
                'role_id' => $foRole->id,
                'desa_id' => 1,
                'email_verified_at' => now(),
            ]
        );
    }
}

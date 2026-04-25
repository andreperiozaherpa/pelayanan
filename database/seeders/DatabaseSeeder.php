<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan Master Data Seeders
        $this->call(RBACSeeder::class);
        $this->call(VillageSeeder::class);

        // 2. Ambil referensi Roles & Desa
        $roleAdmin = Role::where('slug', 'superadmin')->first();
        $roleFO = Role::where('slug', 'petugasfrontoffice')->first();
        $roleDesa = Role::where('slug', 'operatordesa')->first();

        $desaSukamaju = \App\Models\Village::where('name', 'Desa Sukamaju')->first();
        $desaSukaraya = \App\Models\Village::where('name', 'Desa Sukaraya')->first();

        // 3. Buat Akun Login untuk tiap Role

        // Admin Pusat
        User::factory()->create([
            'name' => 'Administrator Pusat',
            'email' => 'admin@example.com',
            'role_id' => $roleAdmin->id,
        ]);

        // Front Office
        User::factory()->create([
            'name' => 'Petugas Front Office',
            'email' => 'fo@example.com',
            'role_id' => $roleFO->id,
        ]);

        // Operator Desa 1 (Sukamaju)
        User::factory()->create([
            'name' => 'Operator Desa Sukamaju',
            'email' => 'sukamaju@example.com',
            'role_id' => $roleDesa->id,
            'desa_id' => $desaSukamaju->id,
        ]);

        // Operator Desa 2 (Sukaraya)
        User::factory()->create([
            'name' => 'Operator Desa Sukaraya',
            'email' => 'sukaraya@example.com',
            'role_id' => $roleDesa->id,
            'desa_id' => $desaSukaraya->id,
        ]);

        // 4. Seeding data warga (Core Data)
        $this->call(CitizenSeeder::class);
    }
}

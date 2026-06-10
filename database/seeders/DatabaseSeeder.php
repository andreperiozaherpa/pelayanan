<?php

namespace Database\Seeders;

use App\Models\Opd;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
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
        $this->call(CmsRBACSeeder::class);
        $this->call(OpdSeeder::class);
        $this->call(VillageSeeder::class);
        $this->call(MapRegionSeeder::class);
        $this->call(MapZoneTypeSeeder::class);
        $this->call(MapLocationCategorySeeder::class);
        $this->call(MapDataSeeder::class);

        // 2. Ambil referensi Roles & Desa
        $roleAdmin = Role::where('slug', 'superadmin')->first();
        $roleFO = Role::where('slug', 'petugasfrontoffice')->first();
        $roleDesa = Role::where('slug', 'operatordesa')->first();
        $roleOpd = Role::where('slug', 'operatoropd')->first();

        $desaSukamaju = Village::where('name', 'Desa Sukamaju')->first();
        $desaSukaraya = Village::where('name', 'Desa Sukaraya')->first();

        $opdKesehatan = Opd::where('code', '05')->first();
        $opdSosial = Opd::where('code', '09')->first();

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

        // Operator OPD Kesehatan
        User::factory()->create([
            'name' => 'Operator Dinas Kesehatan',
            'email' => 'dinkes@example.com',
            'role_id' => $roleOpd->id,
            'opd_id' => $opdKesehatan->id,
        ]);

        // Operator OPD Sosial
        User::factory()->create([
            'name' => 'Operator Dinas Sosial',
            'email' => 'dinsos@example.com',
            'role_id' => $roleOpd->id,
            'opd_id' => $opdSosial->id,
        ]);

        // 4. Seeding data warga (Core Data)
        $this->call(CitizenSeeder::class);

        // 5. Seeding CMS settings
        $this->call(CmsSettingSeeder::class);
        $this->call(DpmptspLandingPageSeeder::class);
    }
}

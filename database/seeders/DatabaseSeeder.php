<?php

namespace Database\Seeders;

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
        $this->call(OpdSeeder::class);
        $this->call(MppGeraiSeeder::class);
        $this->call(MppCounterSeeder::class);
        $this->call(MppServiceSeeder::class);
        $this->call(CmsRBACSeeder::class);
        $this->call(VillageSeeder::class);
        $this->call(MapRegionSeeder::class);
        $this->call(MapZoneTypeSeeder::class);
        $this->call(MapLocationCategorySeeder::class);
        $this->call(MapDataSeeder::class);

        // 2. Akun login & penugasan loket (Web, Queue FO & Gerai)
        $this->call(UserSeeder::class);

        // 3. Seeding data warga (Core Data)
        $this->call(CitizenSeeder::class);

        // 4. Seeding CMS settings
        $this->call(CmsSettingSeeder::class);
        $this->call(DpmptspLandingPageSeeder::class);
        $this->call(CmsSeoSeeder::class);
    }
}

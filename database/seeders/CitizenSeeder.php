<?php

namespace Database\Seeders;

use App\Models\Citizen;
use App\Models\PovertyRecord;
use App\Models\Village;
use Illuminate\Database\Seeder;

class CitizenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a FIXED resident for predictable manual testing (Fase 4 Refinement)
        $fixedVillage = Village::first();
        Citizen::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Sudarsono',
            'tgl_lahir' => '1985-05-20',
            'alamat_desa' => 'Jl. Merdeka No. 12, RT 02/RW 05, Desa Sukamaju',
            'kontak' => '081234567890',
            'desa_id' => $fixedVillage->id,
        ]);

        // 2. Create 50 more citizens for general testing
        Citizen::factory(50)->create()->each(function ($citizen) {
            // Give each citizen 1-2 poverty records
            PovertyRecord::factory(rand(1, 2))->create([
                'citizen_nik' => $citizen->nik,
                'verified_by' => 1, // SuperAdmin by default in DatabaseSeeder
            ]);
        });
    }
}

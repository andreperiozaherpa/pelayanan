<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $villages = [
            ['name' => 'Desa Sukamaju', 'code' => '3201010001', 'district_name' => 'Kecamatan Makmur'],
            ['name' => 'Desa Sukaraya', 'code' => '3201010002', 'district_name' => 'Kecamatan Makmur'],
            ['name' => 'Desa Bojong Gede', 'code' => '3201020001', 'district_name' => 'Kecamatan Jaya'],
        ];

        foreach ($villages as $village) {
            Village::updateOrCreate(['code' => $village['code']], $village);
        }
    }
}

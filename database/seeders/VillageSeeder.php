<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'Kecamatan Makmur' => ['code' => '320101', 'regency_name' => 'Kabupaten Sukses'],
            'Kecamatan Jaya' => ['code' => '320102', 'regency_name' => 'Kabupaten Sukses'],
        ];

        $districtModels = [];
        foreach ($districts as $name => $data) {
            $districtModels[$name] = District::updateOrCreate(
                ['code' => $data['code']],
                ['name' => $name, 'regency_name' => $data['regency_name']]
            );
        }

        $villages = [
            ['name' => 'Desa Sukamaju', 'code' => '3201010001', 'district_name' => 'Kecamatan Makmur'],
            ['name' => 'Desa Sukaraya', 'code' => '3201010002', 'district_name' => 'Kecamatan Makmur'],
            ['name' => 'Desa Bojong Gede', 'code' => '3201020001', 'district_name' => 'Kecamatan Jaya'],
        ];

        foreach ($villages as $village) {
            $districtName = $village['district_name'];
            unset($village['district_name']);

            $village['district_id'] = $districtModels[$districtName]->id;

            Village::updateOrCreate(['code' => $village['code']], $village);
        }
    }
}

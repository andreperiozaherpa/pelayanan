<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\DistrictLeader;
use App\Models\User;
use App\Models\Village;
use App\Models\VillageLeader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

            $district = $districtModels[$districtName];
            $village['district_id'] = $district->id;

            $villageModel = Village::updateOrCreate(['code' => $village['code']], $village);

            // Generate Human Name for Kades
            $kadesName = fake()->name('male');
            $kadesEmail = Str::slug($kadesName).'@desa.id';

            // Create User for Kades
            $user = User::updateOrCreate(
                ['email' => $kadesEmail],
                [
                    'name' => $kadesName,
                    'password' => Hash::make('password'),
                    'role_id' => 2, // operatordesa
                    'desa_id' => $villageModel->id,
                    'is_active' => true,
                ]
            );

            // Create/Update Village Leader and Sync with User
            VillageLeader::updateOrCreate(
                ['village_id' => $villageModel->id, 'is_active' => true],
                [
                    'user_id' => $user->id,
                    'name' => $kadesName,
                    'nip' => '198'.rand(100000, 999999).' 200'.rand(10, 20).' 1 001',
                    'rank' => 'Penata / III.c',
                ]
            );
        }

        // Create Sample District Leaders
        foreach ($districtModels as $district) {
            $camatName = fake()->name('male');
            $camatEmail = Str::slug($camatName).'@kecamatan.id';

            // Create User for Camat
            $user = User::updateOrCreate(
                ['email' => $camatEmail],
                [
                    'name' => $camatName,
                    'password' => Hash::make('password'),
                    'role_id' => 2, // using operatordesa for now or adjust as needed
                    'district_id' => $district->id,
                    'is_active' => true,
                ]
            );

            DistrictLeader::updateOrCreate(
                ['district_id' => $district->id, 'is_active' => true],
                [
                    'user_id' => $user->id,
                    'name' => $camatName,
                    'nip' => '197'.rand(100000, 999999).' 199'.rand(10, 20).' 1 001',
                    'rank' => 'Pembina / IV.a',
                ]
            );
        }
    }
}

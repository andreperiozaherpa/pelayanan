<?php

namespace Database\Seeders;

use App\Models\Anjungan;
use Illuminate\Database\Seeder;

class AnjunganSeeder extends Seeder
{
    public function run(): void
    {
        $anjungans = [
            ['code' => 'ANJ-001', 'name' => 'Anjungan Dinas Kependudukan & Pencatatan Sipil', 'location' => 'Lantai 1 Zona A'],
            ['code' => 'ANJ-002', 'name' => 'Anjungan Dinas Penanaman Modal & PTSP', 'location' => 'Lantai 1 Zona A'],
            ['code' => 'ANJ-003', 'name' => 'Anjungan Dinas Sosial', 'location' => 'Lantai 1 Zona B'],
            ['code' => 'ANJ-004', 'name' => 'Anjungan Dinas Kesehatan', 'location' => 'Lantai 1 Zona B'],
            ['code' => 'ANJ-005', 'name' => 'Anjungan Dinas PU & Penataan Ruang', 'location' => 'Lantai 2 Zona C'],
        ];

        foreach ($anjungans as $anjungan) {
            Anjungan::create($anjungan);
        }
    }
}

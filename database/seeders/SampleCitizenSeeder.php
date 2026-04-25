<?php

namespace Database\Seeders;

use App\Models\Citizen;
use App\Models\PovertyRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleCitizenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();

        // 1. ACTIVE Citizen
        $activeNik = '1234567890123456';
        $citizen1 = Citizen::updateOrCreate(
            ['nik' => $activeNik],
            [
                'nama_lengkap' => 'Budi Santoso',
                'tgl_lahir' => '1985-05-20',
                'alamat_desa' => 'Jl. Merdeka No. 10, Desa Maju Jaya',
                'kontak' => '081234567890',
                'desa_id' => 1
            ]
        );

        PovertyRecord::updateOrCreate(
            ['citizen_nik' => $activeNik],
            [
                'status' => 'ACTIVE',
                'income_range' => 'Rp 500.000 - Rp 1.000.000',
                'valid_from' => now()->subMonths(6),
                'valid_until' => now()->addMonths(6),
                'verified_by' => $admin->id,
                'source' => 'Manual Entry'
            ]
        );

        // 2. EXPIRED Citizen
        $expiredNik = '9876543210987654';
        Citizen::updateOrCreate(
            ['nik' => $expiredNik],
            [
                'nama_lengkap' => 'Siti Aminah',
                'tgl_lahir' => '1990-12-12',
                'alamat_desa' => 'Dusun Sejahtera RT 02, Desa Maju Jaya',
                'kontak' => '089876543210',
                'desa_id' => 1
            ]
        );

        PovertyRecord::updateOrCreate(
            ['citizen_nik' => $expiredNik],
            [
                'status' => 'EXPIRED',
                'income_range' => 'Rp 1.000.000 - Rp 2.000.000',
                'valid_from' => now()->subYear(),
                'valid_until' => now()->subMonth(),
                'verified_by' => $admin->id,
                'source' => 'Manual Entry'
            ]
        );
    }
}

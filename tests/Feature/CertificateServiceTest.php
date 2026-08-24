<?php

use App\Models\District;
use App\Models\DistrictLeader;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\Village;
use App\Models\VillageLeader;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->superAdmin = User::factory()->create([
        'role_id' => Role::where('slug', 'superadmin')->first()->id,
    ]);
});

test('super admin dapat mengakses daftar sertifikat', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('admin.certificates.index'))
        ->assertStatus(200);

    $response->assertOk();
});

test('pemimpin dengan sertifikat aktif dirender di daftar sertifikat', function () {
    $village = Village::create([
        'name' => 'Desa Sukamaju',
        'code' => '32.04.01.2010',
        'district_id' => null,
    ]);

    $leader = User::factory()->create([
        'name' => 'Kades Budi',
        'role_id' => $this->superAdmin->role_id,
    ]);

    VillageLeader::create([
        'village_id' => $village->id,
        'user_id' => $leader->id,
        'name' => 'Budi Santoso',
        'nip' => '0101010101',
        'rank' => 'Kepala Desa',
        'is_active' => true,
    ]);

    UserCertificate::create([
        'user_id' => $leader->id,
        'certificate_path' => 'certificates/budi.p12',
        'passphrase' => 'rahasia',
        'valid_until' => now()->addYear(),
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin)
        ->get(route('admin.certificates.index'))
        ->assertStatus(200)
        ->assertSee('Budi Santoso');
});

test('pemimpin kecamatan dengan sertifikat dirender di daftar sertifikat', function () {
    $district = District::create([
        'name' => 'Kecamatan Tugu',
        'code' => '32.04',
        'regency_name' => 'Kabupaten Tulang Bawang Barat',
    ]);

    $camat = User::factory()->create([
        'name' => 'Camat Ani',
        'role_id' => $this->superAdmin->role_id,
    ]);

    DistrictLeader::create([
        'district_id' => $district->id,
        'user_id' => $camat->id,
        'name' => 'Ani Rahmawati',
        'nip' => '0101010102',
        'rank' => 'Camat',
        'is_active' => true,
    ]);

    UserCertificate::create([
        'user_id' => $camat->id,
        'certificate_path' => 'certificates/ani.p12',
        'passphrase' => 'rahasia',
        'valid_until' => now()->addYear(),
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin)
        ->get(route('admin.certificates.index'))
        ->assertStatus(200)
        ->assertSee('Ani Rahmawati');
});

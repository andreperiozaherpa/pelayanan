<?php

use App\Enums\ServiceType;
use App\Models\Citizen;
use App\Models\Role;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Village;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed standard RBAC roles & permissions
    $this->seed(RBACSeeder::class);

    // Get roles
    $this->roleSuperAdmin = Role::where('slug', 'superadmin')->first();
    $this->roleOperatorDesa = Role::where('slug', 'operatordesa')->first();
    $this->roleFrontOffice = Role::where('slug', 'petugasfrontoffice')->first();

    // Create Villages
    $this->villageA = Village::create(['name' => 'KARTA', 'code' => '1812012001']);
    $this->villageB = Village::create(['name' => 'MULYA JAYA', 'code' => '1812012002']);

    // Create FO User
    $this->userFo = User::factory()->create([
        'role_id' => $this->roleFrontOffice->id,
        'desa_id' => null,
    ]);

    // Create Village Operator User for Village A
    $this->userDesaA = User::factory()->create([
        'role_id' => $this->roleOperatorDesa->id,
        'desa_id' => $this->villageA->id,
    ]);

    // Create Citizens
    $this->citizenA = Citizen::factory()->create([
        'desa_id' => $this->villageA->id,
        'nama_lengkap' => 'BUDI SANTOSO',
        'nik' => '1234567890123456',
    ]);
    $this->citizenB = Citizen::factory()->create([
        'desa_id' => $this->villageB->id,
        'nama_lengkap' => 'ANI SETIAWATI',
        'nik' => '9876543210987654',
    ]);

    // Create Service Requests
    $this->reqA = ServiceRequest::create([
        'citizen_nik' => $this->citizenA->nik,
        'service_type' => ServiceType::POVERTY->value,
        'status' => 'PENDING',
        'notes' => 'Permohonan SKTM untuk sekolah Budi',
        'front_office_user_id' => $this->userFo->id,
    ]);

    $this->reqB = ServiceRequest::create([
        'citizen_nik' => $this->citizenB->nik,
        'service_type' => ServiceType::DOMICILE->value,
        'status' => 'APPROVED',
        'notes' => 'Surat domisili Budi di desa B',
        'front_office_user_id' => $this->userFo->id,
    ]);
});

test('tamu tidak dapat mengakses halaman seluruh pengajuan', function () {
    $this->get(route('services.requests.all'))
        ->assertRedirect(route('login'));
});

test('front office dapat melihat seluruh pengajuan dari semua desa', function () {
    $response = $this->actingAs($this->userFo)
        ->get(route('services.requests.all'));

    $response->assertSuccessful();
    $response->assertSee('BUDI SANTOSO');
    $response->assertSee('ANI SETIAWATI');
});

test('operator desa hanya dapat melihat pengajuan dari desanya sendiri', function () {
    $response = $this->actingAs($this->userDesaA)
        ->get(route('services.requests.all'));

    $response->assertSuccessful();
    $response->assertSee('BUDI SANTOSO');
    $response->assertDontSee('ANI SETIAWATI');
});

test('dapat memfilter pengajuan berdasarkan status', function () {
    $response = $this->actingAs($this->userFo)
        ->get(route('services.requests.all', ['status' => 'APPROVED']));

    $response->assertSuccessful();
    $response->assertDontSee('BUDI SANTOSO'); // PENDING
    $response->assertSee('ANI SETIAWATI');   // APPROVED
});

test('dapat memfilter pengajuan berdasarkan jenis layanan', function () {
    $response = $this->actingAs($this->userFo)
        ->get(route('services.requests.all', ['type' => ServiceType::DOMICILE->value]));

    $response->assertSuccessful();
    $response->assertDontSee('BUDI SANTOSO'); // POVERTY
    $response->assertSee('ANI SETIAWATI');   // DOMICILE
});

test('dapat memfilter pengajuan berdasarkan desa', function () {
    $response = $this->actingAs($this->userFo)
        ->get(route('services.requests.all', ['village_id' => $this->villageB->id]));

    $response->assertSuccessful();
    $response->assertDontSee('BUDI SANTOSO'); // Desa A
    $response->assertSee('ANI SETIAWATI');   // Desa B
});

test('dapat mencari pengajuan berdasarkan nama atau NIK', function () {
    // Cari NIK
    $response = $this->actingAs($this->userFo)
        ->get(route('services.requests.all', ['search' => '1234567890123456']));

    $response->assertSuccessful();
    $response->assertSee('BUDI SANTOSO');
    $response->assertDontSee('ANI SETIAWATI');

    // Cari Nama
    $response2 = $this->actingAs($this->userFo)
        ->get(route('services.requests.all', ['search' => 'ANI']));

    $response2->assertSuccessful();
    $response2->assertDontSee('BUDI SANTOSO');
    $response2->assertSee('ANI SETIAWATI');
});

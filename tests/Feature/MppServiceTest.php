<?php

use App\Models\Citizen;
use App\Models\DeathRecord;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->roleSuperAdmin = Role::where('slug', 'superadmin')->first();
    $this->roleOperatorDesa = Role::where('slug', 'operatordesa')->first();

    // Give operator desa role the service.report permission for testing
    $permission = Permission::where('slug', 'service.report')->first();
    $this->roleOperatorDesa->permissions()->syncWithoutDetaching([$permission->id]);

    $this->superAdmin = User::factory()->create([
        'role_id' => $this->roleSuperAdmin->id,
    ]);

    $this->village = Village::create(['name' => 'KARTA', 'code' => '1812012001']);

    $this->operatorDesa = User::factory()->create([
        'role_id' => $this->roleOperatorDesa->id,
        'desa_id' => $this->village->id,
    ]);
});

test('tamu tidak dapat mengakses manajemen pelayanan mpp', function () {
    $this->get(route('mpp-services.index'))
        ->assertRedirect(route('login'));
});

test('operator desa tidak dapat mengakses manajemen pelayanan mpp', function () {
    $this->actingAs($this->operatorDesa)
        ->get(route('mpp-services.index'))
        ->assertStatus(403);
});

test('super admin dapat mengakses manajemen pelayanan mpp', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('mpp-services.index'));

    $response->assertStatus(200);
});

test('super admin dapat membuat pelayanan baru dengan form builder', function () {
    Storage::fake('public');

    $logo = UploadedFile::fake()->image('logo-layanan.png');

    $payload = [
        'name' => 'Layanan Izin Usaha Mikro',
        'description' => 'Persyaratan izin usaha mikro dan kecil tingkat kecamatan.',
        'logo' => $logo,
        'is_active' => '1',
        'fields' => [
            [
                'label' => 'Nama Pemilik Usaha',
                'type' => 'text',
                'required' => 'true',
            ],
            [
                'label' => 'Modal Usaha',
                'type' => 'number',
                'required' => 'false',
            ],
            [
                'label' => 'Jenis Komoditas',
                'type' => 'select',
                'required' => 'true',
                'options' => ['Pertanian', 'Perdagangan', 'Jasa'],
            ],
        ],
    ];

    $response = $this->actingAs($this->superAdmin)
        ->post(route('mpp-services.store'), $payload);

    $response->assertRedirect(route('mpp-services.index'));

    $this->assertDatabaseHas('mpp_services', [
        'name' => 'Layanan Izin Usaha Mikro',
        'slug' => 'layanan-izin-usaha-mikro',
        'is_active' => true,
    ]);

    $service = MppService::first();
    expect($service->fields)->toBeArray()
        ->toHaveCount(3);

    expect($service->fields[0]['name'])->toBe('nama_pemilik_usaha');
    expect($service->fields[0]['required'])->toBeTrue();
    expect($service->fields[2]['options'])->toBe(['Pertanian', 'Perdagangan', 'Jasa']);
});

test('super admin dapat mengubah pelayanan dan form builder', function () {
    $service = MppService::create([
        'name' => 'Layanan Awal',
        'slug' => 'layanan-awal',
        'fields' => [
            [
                'id' => 'field_1',
                'name' => 'input_awal',
                'label' => 'Input Awal',
                'type' => 'text',
                'required' => true,
            ],
        ],
        'is_active' => true,
    ]);

    $payload = [
        'name' => 'Layanan Baru',
        'description' => 'Deskripsi Baru',
        'fields' => [
            [
                'id' => 'field_1',
                'label' => 'Input Awal Terubah',
                'type' => 'text',
                'required' => '1',
            ],
            [
                'label' => 'Input Tambahan',
                'type' => 'file',
                'required' => '0',
            ],
        ],
    ];

    $response = $this->actingAs($this->superAdmin)
        ->put(route('mpp-services.update', $service->id), $payload);

    $response->assertRedirect(route('mpp-services.index'));

    $service->refresh();
    expect($service->name)->toBe('Layanan Baru');
    expect($service->description)->toBe('Deskripsi Baru');
    expect($service->fields)->toHaveCount(2);
    expect($service->fields[0]['label'])->toBe('Input Awal Terubah');
    expect($service->fields[1]['type'])->toBe('file');
});

test('super admin dapat menghapus pelayanan', function () {
    $service = MppService::create([
        'name' => 'Layanan Dihapus',
        'slug' => 'layanan-dihapus',
        'fields' => [],
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->delete(route('mpp-services.destroy', $service->id));

    $response->assertRedirect(route('mpp-services.index'));
    $this->assertDatabaseMissing('mpp_services', [
        'id' => $service->id,
    ]);
});

test('tamu tidak dapat mengakses form permohonan pelayanan mpp', function () {
    $service = MppService::create([
        'name' => 'Layanan Publik',
        'slug' => 'layanan-publik',
        'fields' => [],
        'is_active' => true,
    ]);

    $this->get(route('mpp-requests.create', $service->slug))
        ->assertRedirect(route('login'));
});

test('operator desa dengan permission service.report dapat mengakses form permohonan', function () {
    $service = MppService::create([
        'name' => 'Layanan Publik',
        'slug' => 'layanan-publik',
        'fields' => [],
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->operatorDesa)
        ->get(route('mpp-requests.create', $service->slug));

    $response->assertStatus(200);
});

test('pengajuan permohonan mpp berhasil dengan data valid', function () {
    $service = MppService::create([
        'name' => 'Layanan Usaha',
        'slug' => 'layanan-usaha',
        'fields' => [
            [
                'id' => 'f1',
                'name' => 'nama_usaha',
                'label' => 'Nama Usaha',
                'type' => 'text',
                'required' => true,
            ],
            [
                'id' => 'f2',
                'name' => 'jumlah_modal',
                'label' => 'Jumlah Modal',
                'type' => 'number',
                'required' => false,
            ],
        ],
        'is_active' => true,
    ]);

    $payload = [
        'form_data' => [
            'nama_usaha' => 'Toko Kelontong Sejahtera',
            'jumlah_modal' => 5000000,
        ],
        'notes' => 'Catatan tambahan pengajuan',
    ];

    $response = $this->actingAs($this->operatorDesa)
        ->post(route('mpp-requests.store', $service->slug), $payload);

    $response->assertRedirect(route('mpp-requests.index'));

    $this->assertDatabaseHas('mpp_service_requests', [
        'mpp_service_id' => $service->id,
        'status' => 'PENDING',
        'notes' => 'Catatan tambahan pengajuan',
    ]);

    $requestRecord = MppServiceRequest::where('mpp_service_id', $service->id)->first();
    expect($requestRecord->submitted_form_data)->toBeArray();
    expect($requestRecord->submitted_form_data['nama_usaha']['value'])->toBe('Toko Kelontong Sejahtera');
    expect($requestRecord->submitted_form_data['jumlah_modal']['value'])->toBe(5000000);
});

test('pengajuan permohonan mpp sukses bahkan jika NIK warga terdaftar meninggal karena terdecouple', function () {
    $service = MppService::create([
        'name' => 'Layanan Usaha',
        'slug' => 'layanan-usaha',
        'fields' => [],
        'is_active' => true,
    ]);

    $citizen = Citizen::factory()->create([
        'desa_id' => $this->operatorDesa->desa_id,
    ]);

    // Mark as deceased
    DeathRecord::create([
        'citizen_nik' => $citizen->nik,
        'status' => 'ACTIVE',
        'recorded_by' => $this->superAdmin->id,
        'date_of_death' => now()->toDateString(),
        'place_of_death' => 'Hospital',
        'cause_of_death' => 'Old age',
    ]);

    $payload = [
        'form_data' => [],
    ];

    $response = $this->actingAs($this->operatorDesa)
        ->from(route('mpp-requests.create', $service->slug))
        ->post(route('mpp-requests.store', $service->slug), $payload);

    $response->assertRedirect(route('mpp-requests.index'));
    $this->assertDatabaseHas('mpp_service_requests', [
        'mpp_service_id' => $service->id,
    ]);
    // Verify no citizen was required or linked
    $this->assertDatabaseMissing('mpp_service_requests', [
        'mpp_service_id' => $service->id,
        'status' => 'REJECTED',
    ]);
});

test('pengajuan permohonan mpp gagal jika input wajib tidak diisi', function () {
    $service = MppService::create([
        'name' => 'Layanan Usaha',
        'slug' => 'layanan-usaha',
        'fields' => [
            [
                'id' => 'f1',
                'name' => 'nama_usaha',
                'label' => 'Nama Usaha',
                'type' => 'text',
                'required' => true,
            ],
        ],
        'is_active' => true,
    ]);

    $payload = [
        'form_data' => [
            'nama_usaha' => '', // Empty for required field
        ],
    ];

    $response = $this->actingAs($this->operatorDesa)
        ->from(route('mpp-requests.create', $service->slug))
        ->post(route('mpp-requests.store', $service->slug), $payload);

    $response->assertRedirect(route('mpp-requests.create', $service->slug));
    $response->assertSessionHasErrors(['form_data.nama_usaha']);
});

test('api get citizen detail mengembalikan data yang benar', function () {
    $citizen = Citizen::factory()->create([
        'desa_id' => $this->operatorDesa->desa_id,
        'nama_lengkap' => 'Budi Santoso',
    ]);

    $response = $this->actingAs($this->operatorDesa)
        ->get(route('citizen.json', $citizen->nik));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'citizen' => [
                'nik' => $citizen->nik,
                'name' => 'Budi Santoso',
                'is_deceased' => false,
            ],
        ]);
});

test('tamu tidak dapat mengunggah berkas permohonan mpp', function () {
    $response = $this->postJson(route('mpp-requests.upload'), [
        'file' => UploadedFile::fake()->create('document.pdf', 100),
    ]);

    $response->assertStatus(401);
});

test('operator desa dapat mengunggah berkas permohonan mpp via dropzone', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->operatorDesa)
        ->postJson(route('mpp-requests.upload'), [
            'file' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $path = $response->json('path');
    Storage::disk('public')->assertExists($path);
});

test('pengajuan permohonan mpp berhasil dengan data upload berkas string path dropzone', function () {
    $service = MppService::create([
        'name' => 'Layanan Kependudukan',
        'slug' => 'layanan-kependudukan',
        'fields' => [
            [
                'id' => 'f1',
                'name' => 'berkas_pendukung',
                'label' => 'Berkas Pendukung',
                'type' => 'file',
                'required' => true,
            ],
        ],
        'is_active' => true,
    ]);

    $payload = [
        'form_data' => [
            'berkas_pendukung' => 'mpp-attachments/fake_document.pdf',
        ],
    ];

    $response = $this->actingAs($this->operatorDesa)
        ->post(route('mpp-requests.store', $service->slug), $payload);

    $response->assertRedirect(route('mpp-requests.index'));

    $this->assertDatabaseHas('mpp_service_requests', [
        'mpp_service_id' => $service->id,
    ]);

    $requestRecord = MppServiceRequest::where('mpp_service_id', $service->id)->first();
    expect($requestRecord->submitted_form_data['berkas_pendukung']['value'])->toBe('mpp-attachments/fake_document.pdf');
});

test('tamu tidak dapat mengunggah logo mpp', function () {
    $response = $this->postJson(route('mpp-services.upload-logo'), [
        'file' => UploadedFile::fake()->image('logo.png'),
    ]);

    $response->assertStatus(401);
});

test('operator desa tidak dapat mengunggah logo mpp', function () {
    $response = $this->actingAs($this->operatorDesa)
        ->postJson(route('mpp-services.upload-logo'), [
            'file' => UploadedFile::fake()->image('logo.png'),
        ]);

    $response->assertStatus(403);
});

test('super admin dapat mengunggah logo mpp via dropzone', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->superAdmin)
        ->postJson(route('mpp-services.upload-logo'), [
            'file' => UploadedFile::fake()->image('logo.png'),
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $path = $response->json('path');
    Storage::disk('public')->assertExists($path);
});

test('super admin dapat membuat pelayanan baru dengan logo string path dropzone', function () {
    Storage::fake('public');

    $payload = [
        'name' => 'Layanan Ketenagakerjaan Baru',
        'description' => 'Deskripsi layanan ketenagakerjaan.',
        'logo' => 'mpp-logos/fake_logo.png',
        'is_active' => '1',
        'fields' => [
            [
                'label' => 'Nama Karyawan',
                'type' => 'text',
                'required' => 'true',
            ],
        ],
    ];

    $response = $this->actingAs($this->superAdmin)
        ->post(route('mpp-services.store'), $payload);

    $response->assertRedirect(route('mpp-services.index'));

    $this->assertDatabaseHas('mpp_services', [
        'name' => 'Layanan Ketenagakerjaan Baru',
        'logo' => 'mpp-logos/fake_logo.png',
    ]);
});

test('super admin dapat memperbarui pelayanan dengan logo string path dropzone', function () {
    Storage::fake('public');

    $service = MppService::create([
        'name' => 'Layanan Kesehatan',
        'slug' => 'layanan-kesehatan',
        'logo' => 'mpp-logos/old_logo.png',
        'fields' => [
            [
                'id' => 'field_1',
                'name' => 'nama',
                'label' => 'Nama',
                'type' => 'text',
                'required' => true,
            ],
        ],
        'is_active' => true,
    ]);

    $payload = [
        'name' => 'Layanan Kesehatan Terbaru',
        'description' => 'Deskripsi baru.',
        'logo' => 'mpp-logos/new_logo.png',
        'is_active' => '1',
        'fields' => [
            [
                'label' => 'Nama',
                'type' => 'text',
                'required' => 'true',
            ],
        ],
    ];

    $response = $this->actingAs($this->superAdmin)
        ->put(route('mpp-services.update', $service->id), $payload);

    $response->assertRedirect(route('mpp-services.index'));

    $this->assertDatabaseHas('mpp_services', [
        'id' => $service->id,
        'name' => 'Layanan Kesehatan Terbaru',
        'logo' => 'mpp-logos/new_logo.png',
    ]);
});

test('pengajuan permohonan mpp tidak membuat warga baru', function () {
    $service = MppService::create([
        'name' => 'Layanan Usaha',
        'slug' => 'layanan-usaha',
        'fields' => [],
        'is_active' => true,
    ]);

    $newNik = '9999888877776666';

    $payload = [
        'form_data' => [],
        'notes' => 'Catatan warga baru',
    ];

    $response = $this->actingAs($this->operatorDesa)
        ->post(route('mpp-requests.store', $service->slug), $payload);

    $response->assertRedirect(route('mpp-requests.index'));

    $this->assertDatabaseMissing('citizens', [
        'nik' => $newNik,
    ]);

    $this->assertDatabaseHas('mpp_service_requests', [
        'mpp_service_id' => $service->id,
        'notes' => 'Catatan warga baru',
    ]);
});

test('pengajuan permohonan mpp tidak mengubah data warga', function () {
    $service = MppService::create([
        'name' => 'Layanan Usaha',
        'slug' => 'layanan-usaha',
        'fields' => [],
        'is_active' => true,
    ]);

    $citizen = Citizen::factory()->create([
        'desa_id' => $this->operatorDesa->desa_id,
        'nama_lengkap' => 'Nama Lama',
    ]);

    $payload = [
        'form_data' => [],
        'notes' => 'Catatan pembaruan',
    ];

    $response = $this->actingAs($this->operatorDesa)
        ->post(route('mpp-requests.store', $service->slug), $payload);

    $response->assertRedirect(route('mpp-requests.index'));

    $this->assertDatabaseHas('citizens', [
        'nik' => $citizen->nik,
        'nama_lengkap' => 'Nama Lama',
    ]);
});

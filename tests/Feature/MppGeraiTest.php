<?php

use App\Models\Gerai;
use App\Models\Opd;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->roleSuperAdmin = Role::where('slug', 'superadmin')->first();
    $this->roleOperatorDesa = Role::where('slug', 'operatordesa')->first();

    $this->superAdmin = User::factory()->create([
        'role_id' => $this->roleSuperAdmin->id,
    ]);

    $this->operatorDesa = User::factory()->create([
        'role_id' => $this->roleOperatorDesa->id,
    ]);

    $this->opd = Opd::create([
        'code' => '11',
        'name' => 'Dinas Kependudukan dan Pencatatan Sipil',
    ]);
});

test('tamu tidak dapat mengakses manajemen gerai', function () {
    $this->get(route('gerais.index'))
        ->assertRedirect(route('login'));
});

test('operator desa tidak dapat mengakses manajemen gerai', function () {
    $this->actingAs($this->operatorDesa)
        ->get(route('gerais.index'))
        ->assertStatus(403);
});

test('super admin dapat mengakses daftar gerai', function () {
    Gerai::factory()->create(['opd_id' => $this->opd->id, 'code' => 'Y', 'name' => 'Gerai Dukcapil']);

    $this->actingAs($this->superAdmin)
        ->get(route('gerais.index'))
        ->assertStatus(200)
        ->assertSee('Gerai Dukcapil');
});

test('super admin dapat mengakses form tambah gerai', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('gerais.create'))
        ->assertStatus(200)
        ->assertSee('Tambah Gerai');
});

test('super admin dapat mengakses form edit gerai dengan preview logo', function () {
    $gerai = Gerai::create([
        'opd_id' => $this->opd->id,
        'code' => 'Y',
        'name' => 'Gerai Edit',
        'logo' => 'mpp-logos/gerai.png',
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin)
        ->get(route('gerais.edit', $gerai))
        ->assertStatus(200)
        ->assertSee('Gerai Edit')
        ->assertSee('gerai-logo');
});

test('super admin dapat membuat gerai baru', function () {
    $payload = [
        'opd_id' => $this->opd->id,
        'code' => 'Y',
        'name' => 'Gerai Dukcapil II',
        'location' => 'Lantai 2 Zona B',
        'is_active' => '1',
    ];

    $this->actingAs($this->superAdmin)
        ->post(route('gerais.store'), $payload)
        ->assertRedirect(route('gerais.index'));

    $this->assertDatabaseHas('mpp_gerais', [
        'opd_id' => $this->opd->id,
        'code' => 'Y',
        'name' => 'Gerai Dukcapil II',
        'is_active' => true,
    ]);
});

test('super admin dapat mengubah gerai', function () {
    $gerai = Gerai::create([
        'opd_id' => $this->opd->id,
        'code' => 'Y',
        'name' => 'Gerai Lama',
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin)
        ->put(route('gerais.update', $gerai), [
            'opd_id' => $this->opd->id,
            'code' => 'Y',
            'name' => 'Gerai Baru',
            'location' => 'Lantai 1',
            'is_active' => '0',
        ])
        ->assertRedirect(route('gerais.index'));

    $gerai->refresh();
    expect($gerai->name)->toBe('Gerai Baru');
    expect($gerai->is_active)->toBeFalse();
});

test('super admin dapat menghapus gerai', function () {
    $gerai = Gerai::create([
        'opd_id' => $this->opd->id,
        'code' => 'Y',
        'name' => 'Gerai Hapus',
        'is_active' => true,
    ]);

    $this->actingAs($this->superAdmin)
        ->delete(route('gerais.destroy', $gerai))
        ->assertRedirect(route('gerais.index'));

    $this->assertDatabaseMissing('mpp_gerais', ['id' => $gerai->id]);
});

test('super admin dapat membuat gerai dengan upload logo', function () {
    Storage::fake('public');

    $logo = UploadedFile::fake()->image('logo-gerai.png');

    $this->actingAs($this->superAdmin)
        ->post(route('gerais.store'), [
            'opd_id' => $this->opd->id,
            'code' => 'Y',
            'name' => 'Gerai Dengan Logo',
            'logo' => $logo,
            'is_active' => '1',
        ])
        ->assertRedirect(route('gerais.index'));

    $gerai = Gerai::where('code', 'Y')->first();
    expect($gerai)->not->toBeNull();
    expect($gerai->logo)->not->toBeNull();
    Storage::disk('public')->assertExists($gerai->logo);
});

test('tamu tidak dapat mengunggah logo gerai', function () {
    $this->postJson(route('gerais.upload-logo'), [
        'file' => UploadedFile::fake()->image('logo.png'),
    ])->assertStatus(401);
});

test('operator desa tidak dapat mengunggah logo gerai', function () {
    $this->actingAs($this->operatorDesa)
        ->postJson(route('gerais.upload-logo'), [
            'file' => UploadedFile::fake()->image('logo.png'),
        ])->assertStatus(403);
});

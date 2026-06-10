<?php

use App\Models\Opd;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Database\Seeders\VillageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);
    $this->seed(VillageSeeder::class);

    $this->superAdmin = User::factory()->create([
        'role_id' => Role::where('slug', 'superadmin')->first()->id,
    ]);

    $this->operatorDesa = User::factory()->create([
        'role_id' => Role::where('slug', 'operatordesa')->first()->id,
    ]);
});

test('super admin can access opd management index', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('opds.index'));
    $response->assertStatus(200);
});

test('operator desa cannot access opd management index', function () {
    $response = $this->actingAs($this->operatorDesa)->get(route('opds.index'));
    $response->assertStatus(403);
});

test('super admin can create a new opd', function () {
    $opdData = [
        'name' => 'Dinas Pekerjaan Umum',
        'code' => '07',
        'description' => 'Dinas PU Tubaba',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('opds.store'), $opdData);

    $response->assertRedirect(route('opds.index'));
    $this->assertDatabaseHas('opds', ['code' => '07', 'name' => 'Dinas Pekerjaan Umum']);
});

test('super admin can update an opd', function () {
    $opd = Opd::create([
        'name' => 'Dinas Perhubungan',
        'code' => '08',
    ]);

    $updateData = [
        'name' => 'Dinas Perhubungan Tubaba',
        'code' => '08-A',
        'description' => 'Updated Description',
    ];

    $response = $this->actingAs($this->superAdmin)->put(route('opds.update', $opd->id), $updateData);

    $response->assertRedirect(route('opds.index'));
    $this->assertDatabaseHas('opds', ['id' => $opd->id, 'name' => 'Dinas Perhubungan Tubaba', 'code' => '08-A']);
});

test('opd with users cannot be deleted', function () {
    $opd = Opd::create([
        'name' => 'Dinas Komunikasi dan Informatika',
        'code' => '09',
    ]);

    User::factory()->create([
        'role_id' => Role::where('slug', 'operatoropd')->first()->id,
        'opd_id' => $opd->id,
    ]);

    $response = $this->actingAs($this->superAdmin)->delete(route('opds.destroy', $opd->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('opds', ['id' => $opd->id]);
});

test('opd without users can be deleted', function () {
    $opd = Opd::create([
        'name' => 'Dinas Kehutanan',
        'code' => '10',
    ]);

    $response = $this->actingAs($this->superAdmin)->delete(route('opds.destroy', $opd->id));

    $response->assertRedirect(route('opds.index'));
    $this->assertDatabaseMissing('opds', ['id' => $opd->id]);
});

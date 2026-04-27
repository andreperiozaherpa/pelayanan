<?php

use App\Models\Citizen;
use App\Models\District;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
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
});

test('super admin can access village management index', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('villages.index'));
    $response->assertStatus(200);
});

test('super admin can create a new village', function () {
    $district = District::first();
    $villageData = [
        'name' => 'Desa Sukamaju',
        'code' => '32.04.01.2010',
        'district_id' => $district->id,
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('villages.store'), $villageData);

    $response->assertRedirect(route('villages.index'));
    $this->assertDatabaseHas('villages', ['code' => '32.04.01.2010']);
});

test('super admin can update a village', function () {
    $village = Village::first();

    $updateData = [
        'name' => 'Nama Desa Updated',
        'code' => $village->code,
        'district_id' => $village->district_id,
    ];

    $response = $this->actingAs($this->superAdmin)->put(route('villages.update', $village->id), $updateData);

    $response->assertRedirect(route('villages.index'));
    $this->assertDatabaseHas('villages', ['id' => $village->id, 'name' => 'Nama Desa Updated']);
});

test('village with citizens cannot be deleted', function () {
    $village = Village::first();
    Citizen::factory()->create(['desa_id' => $village->id]);

    $response = $this->actingAs($this->superAdmin)->delete(route('villages.destroy', $village->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('villages', ['id' => $village->id]);
});

test('new village without relations can be deleted', function () {
    $district = District::first();
    $village = Village::create([
        'name' => 'Desa Baru',
        'code' => '99.99.99.9999',
        'district_id' => $district->id,
    ]);

    $response = $this->actingAs($this->superAdmin)->delete(route('villages.destroy', $village->id));

    $response->assertRedirect(route('villages.index'));
    $this->assertDatabaseMissing('villages', ['id' => $village->id]);
});

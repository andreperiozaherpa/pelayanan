<?php

use App\Models\District;
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
});

test('super admin can access district management index', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('districts.index'));
    $response->assertStatus(200);
});

test('super admin can create a new district', function () {
    $districtData = [
        'name' => 'Kecamatan Baru',
        'code' => '99.99.99',
        'regency_name' => 'Kabupaten Baru',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('districts.store'), $districtData);

    $response->assertRedirect(route('districts.index'));
    $this->assertDatabaseHas('districts', ['code' => '99.99.99']);
});

test('super admin can update a district', function () {
    $district = District::first();

    $updateData = [
        'name' => 'Kecamatan Updated',
        'code' => $district->code,
        'regency_name' => 'Kabupaten Updated',
    ];

    $response = $this->actingAs($this->superAdmin)->put(route('districts.update', $district->id), $updateData);

    $response->assertRedirect(route('districts.index'));
    $this->assertDatabaseHas('districts', ['id' => $district->id, 'name' => 'Kecamatan Updated']);
});

test('district with villages cannot be deleted', function () {
    $district = District::first();

    $response = $this->actingAs($this->superAdmin)->delete(route('districts.destroy', $district->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('districts', ['id' => $district->id]);
});

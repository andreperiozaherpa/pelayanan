<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed roles
    $this->roleSuperAdmin = Role::create(['name' => 'SuperAdmin', 'slug' => 'superadmin']);
    $this->roleOperatorDesa = Role::create(['name' => 'OperatorDesa', 'slug' => 'operatordesa']);
    $this->roleFrontOffice = Role::create(['name' => 'PetugasFrontOffice', 'slug' => 'petugasfrontoffice']);
});

test('operator desa dapat mengakses dashboard desa', function () {
    $user = User::factory()->create(['role_id' => $this->roleOperatorDesa->id]);

    $this->actingAs($user)
        ->get(route('dashboard.desa'))
        ->assertStatus(200);
});

test('admin front office tidak dapat mengakses dashboard desa', function () {
    $user = User::factory()->create(['role_id' => $this->roleFrontOffice->id]);

    $this->actingAs($user)
        ->get(route('dashboard.desa'))
        ->assertStatus(403);
});

test('super admin tidak dapat mengakses dashboard desa', function () {
    $user = User::factory()->create(['role_id' => $this->roleSuperAdmin->id]);

    $this->actingAs($user)
        ->get(route('dashboard.desa'))
        ->assertStatus(403);
});

test('tamu tidak dapat mengakses dashboard desa', function () {
    $this->get(route('dashboard.desa'))
        ->assertRedirect(route('login'));
});

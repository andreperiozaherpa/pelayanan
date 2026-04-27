<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Village;
use Database\Seeders\RBACSeeder;
use Database\Seeders\VillageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * @property string $superAdmin
 * @property string $operatorDesa
 */
beforeEach(function () {
    $this->seed(RBACSeeder::class);
    $this->seed(VillageSeeder::class);

    $this->superAdmin = User::factory()->create([
        'role_id' => Role::where('slug', 'superadmin')->first()->id,
    ]);

    $this->operatorDesa = User::factory()->create([
        'role_id' => Role::where('slug', 'operatordesa')->first()->id,
        'desa_id' => Village::first()->id,
    ]);
});

test('super admin can access user management index', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('users.index'));
    $response->assertStatus(200);
});

test('operator desa cannot access user management index', function () {
    $response = $this->actingAs($this->operatorDesa)->get(route('users.index'));
    $response->assertStatus(403);
});

test('super admin can create a new operator desa user with village', function () {
    $village = Village::first();
    $role = Role::where('slug', 'operatordesa')->first();

    $userData = [
        'name' => 'New Operator',
        'email' => 'operator@example.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'role_id' => $role->id,
        'desa_id' => $village->id,
        'is_active' => 1,
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('users.store'), $userData);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', ['email' => 'operator@example.com', 'desa_id' => $village->id]);
});

test('creating operator desa fails without village', function () {
    $role = Role::where('slug', 'operatordesa')->first();

    $userData = [
        'name' => 'Invalid Operator',
        'email' => 'invalid@example.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'role_id' => $role->id,
        'desa_id' => '', // Empty village
        'is_active' => 1,
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('users.store'), $userData);

    $response->assertSessionHasErrors(['desa_id']);
});

test('super admin can update a user', function () {
    $user = User::factory()->create([
        'role_id' => Role::where('slug', 'petugasfrontoffice')->first()->id,
    ]);

    $updateData = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'role_id' => $user->role_id,
        'is_active' => 1,
    ];

    $response = $this->actingAs($this->superAdmin)->put(route('users.update', $user->id), $updateData);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
});

test('super admin cannot delete themselves', function () {
    $response = $this->actingAs($this->superAdmin)->delete(route('users.destroy', $this->superAdmin->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
});

test('super admin can delete other users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->superAdmin)->delete(route('users.destroy', $user->id));

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

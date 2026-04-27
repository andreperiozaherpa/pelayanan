<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->superAdmin = User::factory()->create([
        'role_id' => Role::where('slug', 'superadmin')->first()->id,
    ]);
});

test('super admin can access role management index', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('roles.index'));
    $response->assertStatus(200);
});

test('super admin can create a new role with permissions', function () {
    $permissions = Permission::limit(3)->pluck('id')->toArray();

    $roleData = [
        'name' => 'Manager Baru',
        'description' => 'Role deskripsi test',
        'permissions' => $permissions,
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('roles.store'), $roleData);

    $response->assertRedirect(route('roles.index'));
    $this->assertDatabaseHas('roles', ['name' => 'Manager Baru']);

    $role = Role::where('name', 'Manager Baru')->first();
    expect($role->permissions)->toHaveCount(3);
});

test('super admin can update a role and permissions', function () {
    $role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
    $permissions = Permission::limit(2)->pluck('id')->toArray();

    $updateData = [
        'name' => 'Editor Updated',
        'description' => 'Updated description',
        'permissions' => $permissions,
    ];

    $response = $this->actingAs($this->superAdmin)->put(route('roles.update', $role->id), $updateData);

    $response->assertRedirect(route('roles.index'));
    $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'Editor Updated']);
    expect($role->fresh()->permissions)->toHaveCount(2);
});

test('core roles cannot be deleted', function () {
    $role = Role::where('slug', 'superadmin')->first();

    $response = $this->actingAs($this->superAdmin)->delete(route('roles.destroy', $role->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('roles', ['id' => $role->id]);
});

test('roles with users cannot be deleted', function () {
    $role = Role::create(['name' => 'Active Role', 'slug' => 'active-role']);
    User::factory()->create(['role_id' => $role->id]);

    $response = $this->actingAs($this->superAdmin)->delete(route('roles.destroy', $role->id));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('roles', ['id' => $role->id]);
});

<?php

use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);
});

test('user can login and audit log is recorded', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create([
        'email' => 'admin@svldk.test',
        'password' => 'password',
        'role_id' => $role->id,
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@svldk.test',
        'password' => 'password',
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'data' => [
                'token',
                'user'
            ]
        ]);

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'action' => 'login_success'
    ]);
});

test('failed login attempt is audited', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create([
        'email' => 'admin@svldk.test',
        'password' => 'password',
        'role_id' => $role->id,
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@svldk.test',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'action' => 'login_failed'
    ]);
});

test('token refresh rotates the token', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create(['role_id' => $role->id]);
    $token = $user->createToken('initial-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/auth/refresh');

    $response->assertSuccessful()
        ->assertJsonStructure(['status', 'data' => ['token']]);

    $newToken = $response->json('data.token');
    
    expect($newToken)->not->toBe($token);
    expect($user->tokens()->count())->toBe(1); // Old token should be deleted
});

test('authenticated user can logout and audit log is recorded', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create(['role_id' => $role->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/auth/logout');

    $response->assertSuccessful();

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'action' => 'logout'
    ]);

    expect($user->tokens()->count())->toBe(0);
});

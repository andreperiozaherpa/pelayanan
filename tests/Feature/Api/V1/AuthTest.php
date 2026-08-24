<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Database\Seeders\VillageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);
    $this->seed(VillageSeeder::class);
});

test('user can login and audit log is recorded', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create([
        'email' => 'admin@pelayanan.test',
        'name' => 'admin',
        'password' => 'password',
        'role_id' => $role->id,
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'username' => 'admin',
        'password' => 'password',
    ]);

    $response->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'data' => [
                'access_token',
                'refresh_token',
                'user',
            ],
        ]);

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'action' => 'login_success',
    ]);
});

test('failed login attempt is audited', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create([
        'email' => 'admin@pelayanan.test',
        'name' => 'admin',
        'password' => 'password',
        'role_id' => $role->id,
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'username' => 'admin',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);

    $this->assertDatabaseHas('audit_logs', [
        'action' => 'login_failed',
    ]);
});

test('token refresh rotates the token', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create([
        'email' => 'admin@pelayanan.test',
        'name' => 'admin',
        'password' => 'password',
        'role_id' => $role->id,
    ]);

    $login = $this->postJson('/api/v1/auth/login', [
        'username' => 'admin',
        'password' => 'password',
    ]);

    $login->assertSuccessful();
    $oldRefreshToken = $login->json('data.refresh_token');
    $oldAccessToken = $login->json('data.access_token');

    $response = $this->postJson('/api/v1/auth/refresh', [
        'refresh_token' => $oldRefreshToken,
    ]);

    $response->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['data' => ['access_token', 'refresh_token']]);

    $newAccessToken = $response->json('data.access_token');
    $newRefreshToken = $response->json('data.refresh_token');

    expect($newAccessToken)->not->toBe($oldAccessToken);
    expect($newRefreshToken)->not->toBe($oldRefreshToken);

    $oldRefreshId = (int) explode('|', $oldRefreshToken)[0];
    expect(PersonalAccessToken::find($oldRefreshId))->toBeNull();
});

test('authenticated user can logout and audit log is recorded', function () {
    $role = Role::where('slug', 'superadmin')->first();
    $user = User::factory()->create(['role_id' => $role->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/v1/auth/logout');

    $response->assertSuccessful();

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'action' => 'logout',
    ]);

    expect($user->tokens()->count())->toBe(0);
});

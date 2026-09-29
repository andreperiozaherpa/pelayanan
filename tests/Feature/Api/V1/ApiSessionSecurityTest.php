<?php

use App\Models\Role;
use App\Models\User;
use App\Services\ApiSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\PersonalAccessToken;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'Superadmin', 'slug' => 'superadmin']);
    $this->user = User::factory()->create(['password' => 'password']);
    $this->tokens = app(ApiSessionService::class)->create($this->user);
});

function apiSessionBearerRequest(string $method, string $uri, string $token, array $data = []): TestResponse
{
    Auth::forgetGuards();

    return test()->json($method, $uri, $data, ['Authorization' => 'Bearer '.$token]);
}

test('refresh verifies the secret instead of trusting a token id', function (string $suffix) {
    $id = explode('|', $this->tokens['refresh_token'])[0];

    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $id.$suffix])
        ->assertUnauthorized()->assertJsonPath('success', false);

    expect(PersonalAccessToken::findToken($this->tokens['refresh_token']))->not->toBeNull();
})->with(['id only' => '', 'forged secret' => '|incorrect-secret']);

test('refresh token cannot authenticate business or logout endpoints', function (string $method, string $path) {
    apiSessionBearerRequest($method, $path, $this->tokens['refresh_token'])
        ->assertUnauthorized()->assertJsonPath('success', false);
})->with([
    ['GET', '/api/v1/gerai'],
    ['POST', '/api/v1/auth/logout'],
    ['POST', '/api/v1/auth/logout-all'],
]);

test('access token cannot be exchanged as a refresh token', function () {
    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $this->tokens['access_token']])->assertUnauthorized();
});

test('legacy tokens require a new login', function (string $name) {
    $token = $this->user->createToken($name, ['*'], now()->addDays(30))->plainTextToken;

    apiSessionBearerRequest('GET', '/api/v1/gerai', $token)->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $token])->assertUnauthorized();
})->with(['auth_token_legacy', 'refresh_auth_token_legacy']);

test('expired tokens are rejected', function () {
    foreach ($this->tokens as $token) {
        PersonalAccessToken::findToken($token)->forceFill(['expires_at' => now()->subSecond()])->save();
    }

    apiSessionBearerRequest('GET', '/api/v1/gerai', $this->tokens['access_token'])->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $this->tokens['refresh_token']])->assertUnauthorized();
});

test('rotation invalidates both old tokens and rejects replay', function () {
    $otherSession = app(ApiSessionService::class)->create($this->user);
    $response = $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $this->tokens['refresh_token']])->assertSuccessful();

    apiSessionBearerRequest('GET', '/api/v1/gerai', $this->tokens['access_token'])->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $this->tokens['refresh_token']])->assertUnauthorized();
    apiSessionBearerRequest('GET', '/api/v1/gerai', $response->json('data.access_token'))->assertSuccessful();
    apiSessionBearerRequest('GET', '/api/v1/gerai', $otherSession['access_token'])->assertSuccessful();
    expect($this->user->tokens()->count())->toBe(4);
});

test('logout revokes its token pair without affecting another session', function () {
    $otherSession = app(ApiSessionService::class)->create($this->user);

    apiSessionBearerRequest('POST', '/api/v1/auth/logout', $this->tokens['access_token'])->assertSuccessful();
    apiSessionBearerRequest('GET', '/api/v1/gerai', $this->tokens['access_token'])->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $this->tokens['refresh_token']])->assertUnauthorized();
    apiSessionBearerRequest('GET', '/api/v1/gerai', $otherSession['access_token'])->assertSuccessful();
    expect($this->user->tokens()->count())->toBe(2);
});

test('logout all revokes every session of only the current user', function () {
    $secondSession = app(ApiSessionService::class)->create($this->user);
    $anotherUser = User::factory()->create();
    $otherTokens = app(ApiSessionService::class)->create($anotherUser);

    apiSessionBearerRequest('POST', '/api/v1/auth/logout-all', $this->tokens['access_token'])->assertSuccessful();
    apiSessionBearerRequest('GET', '/api/v1/gerai', $secondSession['access_token'])->assertUnauthorized();
    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $secondSession['refresh_token']])->assertUnauthorized();
    apiSessionBearerRequest('GET', '/api/v1/gerai', $otherTokens['access_token'])->assertSuccessful();
    expect($this->user->tokens()->count())->toBe(0);
    $this->assertDatabaseHas('audit_logs', ['user_id' => $this->user->id, 'action' => 'logout_all']);
});

test('logout authenticated just before rotation still revokes the rotated session', function () {
    $authenticatedUser = $this->user->withAccessToken(PersonalAccessToken::findToken($this->tokens['access_token']));
    $rotated = app(ApiSessionService::class)->refresh($this->tokens['refresh_token']);

    app(ApiSessionService::class)->revoke($authenticatedUser);

    expect(PersonalAccessToken::findToken($rotated['access_token']))->toBeNull()
        ->and(PersonalAccessToken::findToken($rotated['refresh_token']))->toBeNull();
});

test('deactivated accounts cannot login refresh or use the API', function () {
    $this->user->update(['is_active' => false]);

    apiSessionBearerRequest('GET', '/api/v1/gerai', $this->tokens['access_token'])->assertForbidden();
    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => $this->tokens['refresh_token']])->assertForbidden();
    $this->postJson('/api/v1/auth/login', ['username' => $this->user->name, 'password' => 'password'])->assertForbidden();
});

test('inactive web sessions cannot bypass API account checks', function () {
    $this->user->update(['is_active' => false]);

    $this->actingAs($this->user, 'web')->getJson('/api/v1/gerai')->assertForbidden();
});

test('API login does not authenticate the web session', function () {
    $this->postJson('/api/v1/auth/login', ['username' => $this->user->name, 'password' => 'password'])
        ->assertSuccessful()->assertJsonStructure(['data' => ['access_token', 'refresh_token', 'user']]);

    $this->assertGuest('web');
    Auth::forgetGuards();
    $this->getJson('/api/v1/gerai')->assertUnauthorized();
});

test('login attempts are throttled with a retry header', function () {
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->postJson('/api/v1/auth/login', ['username' => $this->user->name, 'password' => 'wrong'])->assertUnauthorized();
    }

    $this->postJson('/api/v1/auth/login', ['username' => $this->user->name, 'password' => 'wrong'])
        ->assertTooManyRequests()->assertHeader('Retry-After')->assertJsonPath('success', false);
});

test('malformed login identifiers return validation errors', function (string $field) {
    $this->postJson('/api/v1/auth/login', [$field => ['invalid'], 'password' => 'password'])
        ->assertUnprocessable()->assertJsonValidationErrors($field);
})->with(['username', 'login']);

test('refresh attempts are throttled', function () {
    for ($attempt = 0; $attempt < 60; $attempt++) {
        $this->postJson('/api/v1/auth/refresh', ['refresh_token' => 'invalid'])->assertUnauthorized();
    }

    $this->postJson('/api/v1/auth/refresh', ['refresh_token' => 'invalid'])
        ->assertTooManyRequests()->assertHeader('Retry-After')->assertJsonPath('success', false);
});

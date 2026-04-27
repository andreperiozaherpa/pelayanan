<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Citizen;
use App\Models\PovertyRecord;
use Database\Seeders\RBACSeeder;
use Database\Seeders\VillageSeeder;
use App\Models\Village;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);
    $this->seed(VillageSeeder::class);
});

test('poverty status check returns active for valid record', function () {
    $petugas = User::factory()->create([
        'role_id' => Role::where('slug', 'petugasfrontoffice')->first()->id
    ]);
    
    $citizen = Citizen::factory()->create([
        'nik' => '1234567890123456',
        'desa_id' => Village::first()->id
    ]);
    PovertyRecord::factory()->create([
        'citizen_nik' => $citizen->nik,
        'status' => 'ACTIVE',
        'valid_until' => now()->addYear(),
    ]);

    $response = $this->actingAs($petugas, 'sanctum')
        ->getJson("/api/v1/poverty/status/{$citizen->nik}");

    $response->assertSuccessful()
        ->assertJson([
            'status' => 'success',
            'data' => [
                'status' => 'ACTIVE'
            ]
        ]);
});

test('poverty status results are cached in redis', function () {
    $petugas = User::factory()->create([
        'role_id' => Role::where('slug', 'petugasfrontoffice')->first()->id
    ]);
    
    $citizen = Citizen::factory()->create([
        'nik' => '9999999999999999',
        'desa_id' => Village::first()->id
    ]);
    PovertyRecord::factory()->create([
        'citizen_nik' => $citizen->nik,
        'status' => 'ACTIVE',
        'valid_until' => now()->addYear(),
    ]);

    $cacheKey = "poverty_status_9999999999999999";
    
    // First call: Should populate cache
    $this->actingAs($petugas, 'sanctum')
        ->getJson("/api/v1/poverty/status/{$citizen->nik}");
        
    expect(Cache::has($cacheKey))->toBeTrue();
    
    // Modify status in DB but cache should still be old
    PovertyRecord::where('citizen_nik', $citizen->nik)->update(['status' => 'EXPIRED']);
    
    $response = $this->actingAs($petugas, 'sanctum')
        ->getJson("/api/v1/poverty/status/{$citizen->nik}");

    // Should still return ACTIVE from cache
    $response->assertSuccessful()
        ->assertJsonPath('data.status', 'ACTIVE');
});

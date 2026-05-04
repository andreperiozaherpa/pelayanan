<?php

use App\Models\Citizen;
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
});

test('operator desa can only see citizens in their village', function () {
    $operatorA = User::factory()->create([
        'role_id' => Role::where('slug', 'operatordesa')->first()->id,
        'desa_id' => Village::first()->id,
    ]);

    $citizenA = Citizen::factory()->create(['desa_id' => Village::first()->id]);
    $citizenB = Citizen::factory()->create(['desa_id' => Village::skip(1)->first()->id]);

    $response = $this->actingAs($operatorA, 'sanctum')
        ->getJson('/api/v1/citizens');

    $response->assertSuccessful();
    $data = $response->json('data');

    expect($data)->toHaveCount(1);
    expect($data[0]['nik'])->toBe($citizenA->nik);
});

test('data masking works for unauthorized users', function () {
    $petugas = User::factory()->create([
        'role_id' => Role::where('slug', 'petugasfrontoffice')->first()->id,
    ]);

    $alamatAsli = 'Jl. Merdeka No. 123';
    $kontakAsli = '08123456789';

    $citizen = Citizen::factory()->create([
        'desa_id' => Village::first()->id,
        'alamat_desa' => $alamatAsli,
        'kontak' => $kontakAsli,
    ]);

    $response = $this->actingAs($petugas, 'sanctum')
        ->getJson("/api/v1/citizens/{$citizen->nik}");

    $response->assertSuccessful();

    // ✅ Test dinamis sesuai implementasi resource
    $maskedAddress = $response->json('data.alamat_desa');
    $maskedPhone = $response->json('data.kontak');

    // Assert format alamat: 3 chars + space + stars
    expect(substr($maskedAddress, 0, 3))->toBe('Jl.');
    expect($maskedAddress[3])->toBe(' '); // Ada spasi di index ke-3
    expect(substr($maskedAddress, 4))->toBe(str_repeat('*', strlen($alamatAsli) - 3));

    // Assert format telepon: 3 digits + stars (no space)
    expect(substr($maskedPhone, 0, 3))->toBe('081');
    expect(substr($maskedPhone, 3))->toBe(str_repeat('*', strlen($kontakAsli) - 3));

    // Assert tidak sama dengan aslinya
    expect($maskedAddress)->not->toBe($alamatAsli);
    expect($maskedPhone)->not->toBe($kontakAsli);
});

test('operator desa sees unmasked data in their own village', function () {
    $operator = User::factory()->create([
        'role_id' => Role::where('slug', 'operatordesa')->first()->id,
        'desa_id' => Village::first()->id,
    ]);

    $citizen = Citizen::factory()->create([
        'desa_id' => Village::first()->id,
        'alamat_desa' => 'Jl. Merdeka No. 123',
        'kontak' => '08123456789',
    ]);

    $response = $this->actingAs($operator, 'sanctum')
        ->getJson("/api/v1/citizens/{$citizen->nik}");

    $response->assertSuccessful();
    expect($response->json('data.alamat_desa'))->toBe('Jl. Merdeka No. 123');
    expect($response->json('data.kontak'))->toBe('08123456789');
});

test('masking preserves data length consistency', function () {
    $petugas = User::factory()->create([
        'role_id' => Role::where('slug', 'petugasfrontoffice')->first()->id,
    ]);

    // Test dengan berbagai panjang data
    $testCases = [
        ['alamat' => 'Jl. A', 'kontak' => '081'],
        ['alamat' => 'Jl. AB', 'kontak' => '0812'],
        ['alamat' => 'Jl. ABC', 'kontak' => '08123'],
        ['alamat' => 'Jl. ABCD', 'kontak' => '081234'],
        ['alamat' => 'Jl. ABCDE', 'kontak' => '0812345'],
    ];

    foreach ($testCases as $case) {
        $citizen = Citizen::factory()->create([
            'desa_id' => Village::first()->id,
            'alamat_desa' => $case['alamat'],
            'kontak' => $case['kontak'],
        ]);

        $response = $this->actingAs($petugas, 'sanctum')
            ->getJson("/api/v1/citizens/{$citizen->nik}");

        $maskedAddress = $response->json('data.alamat_desa');
        $maskedPhone = $response->json('data.kontak');

        // Assert panjang masked sama dengan asli
        expect(strlen($maskedAddress))->toBe(strlen($case['alamat']) + 1); // +1 untuk spasi
        expect(strlen($maskedPhone))->toBe(strlen($case['kontak']));
    }
});

<?php

use App\Models\Counter;
use App\Models\Gerai;
use App\Models\MppService;
use Database\Seeders\MppCounterSeeder;
use Database\Seeders\MppGeraiSeeder;
use Database\Seeders\MppServiceSeeder;
use Database\Seeders\OpdSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('seeder gerai menghasilkan kode huruf A-Z', function () {
    $this->seed(OpdSeeder::class);
    $this->seed(MppGeraiSeeder::class);

    $codes = Gerai::pluck('code')->all();

    expect(Gerai::count())->toBe(25);
    expect($codes)->each->toMatch('/^[A-Z]+$/');
    expect(count($codes))->toBe(count(array_unique($codes)));
    expect(Gerai::whereNull('opd_id')->count())->toBe(0);
});

test('seeder pelayanan menautkan layanan ke instansi melalui kode huruf', function () {
    $this->seed(OpdSeeder::class);
    $this->seed(MppGeraiSeeder::class);
    $this->seed(MppServiceSeeder::class);

    $gerai = Gerai::where('code', 'A')->first();

    expect($gerai)->not->toBeNull();
    expect($gerai->opd?->services)->not->toBeEmpty();
    expect($gerai->opd?->services->pluck('name'))->toContain('KTP Elektronik');
    expect(MppService::whereNull('opd_id')->count())->toBe(0);
});

test('seeder loket menghasilkan kode angka urut', function () {
    $this->seed(OpdSeeder::class);
    $this->seed(MppGeraiSeeder::class);
    $this->seed(MppCounterSeeder::class);

    $codes = Counter::pluck('code')->map(fn ($code) => (int) $code)->sort()->values()->all();

    expect(Counter::count())->toBe(25);
    expect(Counter::pluck('code'))->each->toMatch('/^\d+$/');
    expect($codes)->toBe(range(1, 25));
    expect(Counter::whereNull('gerai_id')->count())->toBe(0);
});

<?php

use App\Models\Citizen;
use App\Models\PovertyRecord;
use App\Models\Village;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->village = Village::create(['name' => 'Desa Test', 'code' => '001']);
    $this->citizen = Citizen::factory()->create(['desa_id' => $this->village->id]);
});

test('halaman publik cek surat dapat diakses tanpa login', function () {
    $this->get(route('public.verify'))
        ->assertStatus(200)
        ->assertSee('Cek Keabsahan Surat');
});

test('halaman publik menampilkan form manual pengecekan', function () {
    $this->get(route('public.verify'))
        ->assertStatus(200)
        ->assertSee('Nomor Induk Kependudukan');
});

test('halaman publik menampilkan dokumen sah jika record aktif', function () {
    PovertyRecord::create([
        'citizen_nik' => $this->citizen->nik,
        'status' => 'ACTIVE',
        'valid_from' => now()->subDays(10),
        'valid_until' => now()->addMonths(6),
        'source' => 'VILLAGE_VERIFICATION',
    ]);

    $this->get(route('public.verify', ['q' => $this->citizen->nik, 'type' => 'poverty']))
        ->assertStatus(200)
        ->assertSee('Dokumen Sah & Aktif')
        ->assertSee($this->citizen->nama_lengkap);
});

test('halaman publik menampilkan tidak ditemukan jika nik tidak ada', function () {
    $this->get(route('public.verify', ['q' => '9999999999999999', 'type' => 'poverty']))
        ->assertStatus(200)
        ->assertSee('Dokumen Tidak Ditemukan');
});

test('halaman publik menampilkan kadaluarsa jika valid_until sudah lewat', function () {
    PovertyRecord::create([
        'citizen_nik' => $this->citizen->nik,
        'status' => 'ACTIVE',
        'valid_from' => now()->subYear(),
        'valid_until' => now()->subDays(1),
        'source' => 'VILLAGE_VERIFICATION',
    ]);

    $this->get(route('public.verify', ['q' => $this->citizen->nik, 'type' => 'poverty']))
        ->assertStatus(200)
        ->assertSee('Kadaluarsa');
});

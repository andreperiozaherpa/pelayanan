<?php

use App\Models\Counter;
use App\Models\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('riwayat display memuat seluruh tiket hari ini dengan status sesuai', function () {
    $done = Queue::factory()->done()->create([
        'number' => 'A-001',
        'counter_name' => 'Loket 1 - Pendaftaran',
        'created_at' => now()->subMinutes(30),
        'done_at' => now()->subMinutes(25),
    ]);

    Queue::factory()->rejected()->create([
        'number' => 'FO-002',
        'created_at' => now()->subMinutes(20),
        'fo_finished_at' => now()->subMinutes(15),
    ]);

    $counter = Counter::factory()->create(['name' => 'Loket 2 - Verifikasi']);

    Queue::factory()->create([
        'number' => 'A-003',
        'status' => Queue::STATUS_CALLING_GERAI,
        'counter_id' => $counter->id,
        'counter_name' => $counter->name,
        'called_at' => now()->subMinutes(10),
        'created_at' => now()->subMinutes(10),
    ]);

    Queue::factory()->waitingFo()->create([
        'number' => 'A-004',
        'created_at' => now()->subMinutes(5),
    ]);

    $response = $this->getJson('/api/v1/display/history');

    $response->assertOk()
        ->assertJsonPath('success', true);

    $data = $response->json('data');

    expect($data)->toHaveCount(4);
    expect($data[0])->toMatchArray([
        'queue_number' => 'A-004',
        'status' => 'Menunggu',
    ]);
    expect($data[1])->toMatchArray([
        'queue_number' => 'A-003',
        'status' => 'Dipanggil',
    ]);
    expect($data[2])->toMatchArray([
        'queue_number' => 'FO-002',
        'gerai_name' => 'Front Office',
        'status' => 'Tidak Hadir',
    ]);
    expect($data[3])->toMatchArray([
        'queue_number' => 'A-001',
        'gerai_name' => 'Loket 1 - Pendaftaran',
        'status' => 'Selesai',
    ]);
    expect($data[3]['timestamp'])->toBeInt();
});

test('riwayat display mengabaikan tiket dari hari sebelumnya', function () {
    Queue::factory()->done()->create([
        'number' => 'A-099',
        'counter_name' => 'Loket 1 - Pendaftaran',
        'created_at' => now()->subDay(),
        'done_at' => now()->subDay(),
    ]);

    $this->getJson('/api/v1/display/history')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

test('riwayat display diurutkan dari yang paling baru', function () {
    Queue::factory()->done()->create([
        'number' => 'A-001',
        'counter_name' => 'Loket 1 - Pendaftaran',
        'created_at' => now()->subMinutes(30),
        'done_at' => now()->subMinutes(25),
    ]);
    Queue::factory()->done()->create([
        'number' => 'A-002',
        'counter_name' => 'Loket 2 - Verifikasi',
        'created_at' => now()->subMinutes(5),
        'done_at' => now(),
    ]);

    $numbers = $this->getJson('/api/v1/display/history')->json('data.*.queue_number');

    expect($numbers)->toBe(['A-002', 'A-001']);
});

<?php

use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\Gerai;
use App\Models\MppService;
use App\Models\Opd;
use App\Models\Queue;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->roleFo = Role::where('slug', 'petugasfrontoffice')->first();
    $this->roleGerai = Role::where('slug', 'gerai')->first();

    $this->opd = Opd::create([
        'code' => '11',
        'name' => 'Dinas Kependudukan dan Pencatatan Sipil',
    ]);

    $this->gerai = Gerai::create([
        'opd_id' => $this->opd->id,
        'code' => 'A',
        'name' => 'Gerai Dukcapil',
        'location' => 'Lantai 1 Zona A',
        'is_active' => true,
    ]);

    $this->counter = Counter::create([
        'gerai_id' => $this->gerai->id,
        'code' => 'A',
        'name' => 'Loket 1 - Pendaftaran',
        'location' => 'Lantai 1 Zona A',
        'is_active' => true,
    ]);

    $this->service = MppService::create([
        'name' => 'KTP Elektronik',
        'slug' => 'ktp-elektronik',
        'opd_id' => $this->opd->id,
        'fields' => [],
        'is_active' => true,
    ]);
});

function makeFoUser(Role $role): User
{
    return User::factory()->create(['role_id' => $role->id]);
}

function makeGeraiUser(Role $role, Counter $counter): User
{
    $user = User::factory()->create(['role_id' => $role->id]);
    CounterUser::create([
        'counter_id' => $counter->id,
        'user_id' => $user->id,
        'is_active' => true,
    ]);

    return $user;
}

test('guest can list active services with instansi gerai kode', function () {
    MppService::factory()->inactive()->create();

    $this->getJson('/api/v1/services')
        ->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonPath('data.0.id', $this->service->id)
        ->assertJsonPath('data.0.nama', 'KTP Elektronik')
        ->assertJsonPath('data.0.instansi.kode', '11')
        ->assertJsonPath('data.0.instansi.gerai.kode', 'A')
        ->assertJsonCount(1, 'data');
});

test('guest can take a queue number with gerai code prefix', function () {
    $response = $this->postJson('/api/v1/tickets', [
        'service_id' => $this->service->id,
    ]);

    $response->assertOk()
        ->assertJson(['success' => true])
        ->assertJsonPath('data.nomor_antrian', 'A-001')
        ->assertJsonPath('data.status', 'waiting_fo')
        ->assertJsonPath('data.service_name', 'KTP Elektronik');

    $this->assertDatabaseHas('mpp_queues', [
        'number' => 'A-001',
        'service_id' => $this->service->id,
        'status' => 'waiting_fo',
    ]);
});

test('queue number increments per service', function () {
    $this->postJson('/api/v1/tickets', ['service_id' => $this->service->id]);

    $response = $this->postJson('/api/v1/tickets', ['service_id' => $this->service->id]);

    $response->assertOk()
        ->assertJsonPath('data.nomor_antrian', 'A-002');
});

test('queue number is unique across services of the same gerai', function () {
    $this->service->update(['gerai_id' => $this->gerai->id]);

    $secondService = MppService::create([
        'name' => 'Kartu Keluarga',
        'slug' => 'kartu-keluarga',
        'opd_id' => $this->opd->id,
        'gerai_id' => $this->gerai->id,
        'fields' => [],
        'is_active' => true,
    ]);

    $numbers = [];
    foreach (range(1, 4) as $i) {
        $serviceId = $i % 2 === 0 ? $secondService->id : $this->service->id;
        $numbers[] = $this->postJson('/api/v1/tickets', ['service_id' => $serviceId])
            ->assertOk()
            ->json('data.nomor_antrian');
    }

    expect($numbers)->toBe(['A-001', 'A-002', 'A-003', 'A-004']);

    $distinct = Queue::whereIn('service_id', [$this->service->id, $secondService->id])->count();
    expect($distinct)->toBe(4);
});

test('queue number uses each gerai own prefix and counter', function () {
    $this->service->update(['gerai_id' => $this->gerai->id]);

    $geraiAb = Gerai::create([
        'opd_id' => $this->opd->id,
        'code' => 'AB',
        'name' => 'Gerai Pencetakan - Dukcapil',
        'is_active' => true,
    ]);

    $serviceAb = MppService::create([
        'name' => 'Akte Kelahiran',
        'slug' => 'akte-kelahiran',
        'opd_id' => $this->opd->id,
        'gerai_id' => $geraiAb->id,
        'fields' => [],
        'is_active' => true,
    ]);

    $this->postJson('/api/v1/tickets', ['service_id' => $this->service->id])->assertJsonPath('data.nomor_antrian', 'A-001');
    $this->postJson('/api/v1/tickets', ['service_id' => $serviceAb->id])->assertJsonPath('data.nomor_antrian', 'AB-001');
    $this->postJson('/api/v1/tickets', ['service_id' => $this->service->id])->assertJsonPath('data.nomor_antrian', 'A-002');
    $this->postJson('/api/v1/tickets', ['service_id' => $serviceAb->id])->assertJsonPath('data.nomor_antrian', 'AB-002');
});

test('queue number resets to 1 the next day', function () {
    $this->postJson('/api/v1/tickets', ['service_id' => $this->service->id]);
    $this->assertDatabaseHas('mpp_queues', ['number' => 'A-001']);

    $this->travelTo(now()->addDay());

    $response = $this->postJson('/api/v1/tickets', ['service_id' => $this->service->id]);

    $response->assertOk()
        ->assertJsonPath('data.nomor_antrian', 'A-001');

    $this->travelBack();
});

test('taking ticket for inactive service is rejected', function () {
    $this->service->update(['is_active' => false]);

    $this->postJson('/api/v1/tickets', ['service_id' => $this->service->id])
        ->assertStatus(422);
});

test('queue endpoints require authentication', function () {
    $this->getJson('/api/v1/fo/waiting')->assertUnauthorized();
    $this->getJson('/api/v1/gerai')->assertUnauthorized();
    $this->postJson('/api/v1/gerai/call', ['petugas_id' => 1])->assertUnauthorized();
});

test('fo can call next ticket to calling_fo', function () {
    $fo = makeFoUser($this->roleFo);
    $ticket = Queue::factory()->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/call')
        ->assertOk()
        ->assertJsonPath('data.nomor_antrian', $ticket->number)
        ->assertJsonPath('data.status', 'calling_fo')
        ->assertJsonPath('data.fo_petugas_id', $fo->id);

    expect($ticket->fresh()->status)->toBe('calling_fo');
});

test('fo waiting and calling lists', function () {
    $fo = makeFoUser($this->roleFo);
    Queue::factory()->create(['service_id' => $this->service->id]);
    Queue::factory()->create(['service_id' => $this->service->id]);
    $calling = Queue::factory()->callingFo($fo->id)->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->getJson('/api/v1/fo/waiting')
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $this->getJson('/api/v1/fo/calling')
        ->assertOk()
        ->assertJsonPath('data.id', $calling->id)
        ->assertJsonPath('data.status', 'calling_fo');
});

test('fo can forward calling ticket to gerai without selecting a loket', function () {
    $fo = makeFoUser($this->roleFo);
    $ticket = Queue::factory()->callingFo($fo->id)->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/forward', [
        'ticket_id' => $ticket->id,
        'catatan' => 'Berkas lengkap',
    ])->assertOk()
        ->assertJsonPath('data.status', 'waiting_gerai')
        ->assertJsonPath('data.counter_tujuan_id', null)
        ->assertJsonPath('data.counter_tujuan', null)
        ->assertJsonPath('data.catatan', 'Berkas lengkap');

    expect($ticket->fresh()->counter_id)->toBeNull();
});

test('fo can reject a calling ticket', function () {
    $fo = makeFoUser($this->roleFo);
    $ticket = Queue::factory()->callingFo($fo->id)->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/reject', [
        'ticket_id' => $ticket->id,
        'alasan' => 'Berkas tidak lengkap',
    ])->assertOk()
        ->assertJsonPath('data.status', 'rejected')
        ->assertJsonPath('data.alasan_reject', 'Berkas tidak lengkap');
});

test('fo can skip a calling ticket back to waiting_fo', function () {
    $fo = makeFoUser($this->roleFo);
    $ticket = Queue::factory()->callingFo($fo->id)->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/skip', ['ticket_id' => $ticket->id])
        ->assertOk()
        ->assertJsonPath('data.status', 'waiting_fo')
        ->assertJsonPath('data.fo_petugas_id', null);
});

test('fo can forward a waiting ticket directly to gerai without calling', function () {
    $fo = makeFoUser($this->roleFo);
    $ticket = Queue::factory()->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/forward', [
        'ticket_id' => $ticket->id,
        'catatan' => 'Langsung lanjut',
    ])->assertOk()
        ->assertJsonPath('data.status', 'waiting_gerai')
        ->assertJsonPath('data.catatan', 'Langsung lanjut');

    expect($ticket->fresh()->status)->toBe('waiting_gerai');
});

test('fo can reject a waiting ticket', function () {
    $fo = makeFoUser($this->roleFo);
    $ticket = Queue::factory()->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/reject', [
        'ticket_id' => $ticket->id,
        'alasan' => 'Tidak hadir',
    ])->assertOk()
        ->assertJsonPath('data.status', 'rejected')
        ->assertJsonPath('data.alasan_reject', 'Tidak hadir');
});

test('fo cannot forward a ticket that already left the front office', function () {
    $fo = makeFoUser($this->roleFo);
    $ticket = Queue::factory()->create([
        'service_id' => $this->service->id,
        'status' => Queue::STATUS_WAITING_GERAI,
    ]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/forward', [
        'ticket_id' => $ticket->id,
    ])->assertStatus(422);
});

test('gerai sees waiting tickets of its instansi and calling only for own counter', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);

    Queue::factory()->create(['service_id' => $this->service->id, 'status' => Queue::STATUS_WAITING_GERAI, 'counter_id' => null]);
    $otherCounter = Counter::create(['gerai_id' => $this->gerai->id, 'code' => 'B', 'name' => 'Loket 2', 'is_active' => true]);
    Queue::factory()->create(['service_id' => $this->service->id, 'status' => Queue::STATUS_WAITING_GERAI, 'counter_id' => null]);
    $calling = Queue::factory()->callingGerai($this->counter, $petugas->id)->create(['service_id' => $this->service->id]);

    $otherOpd = Opd::create(['code' => '22', 'name' => 'Dinas Lain']);
    $otherGerai = Gerai::create(['opd_id' => $otherOpd->id, 'code' => 'B', 'name' => 'Gerai Lain', 'location' => 'Lantai 2', 'is_active' => true]);
    $otherCounter2 = Counter::create(['gerai_id' => $otherGerai->id, 'code' => 'C', 'name' => 'Loket Lain', 'is_active' => true]);
    $otherService = MppService::create(['name' => 'Izin Usaha', 'slug' => 'izin-usaha', 'opd_id' => $otherOpd->id, 'fields' => [], 'is_active' => true]);
    Queue::factory()->create(['service_id' => $otherService->id, 'status' => Queue::STATUS_WAITING_GERAI, 'counter_id' => null]);

    Sanctum::actingAs($petugas);

    $this->getJson("/api/v1/gerai/{$this->counter->id}/waiting")
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonMissingPath('data.2');

    $this->getJson("/api/v1/gerai/{$otherCounter->id}/waiting")
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $this->getJson("/api/v1/gerai/{$this->counter->id}/calling")
        ->assertOk()
        ->assertJsonPath('data.id', $calling->id);
});

test('gerai can call next waiting ticket of its instansi', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);
    $ticket = Queue::factory()->create(['service_id' => $this->service->id, 'status' => Queue::STATUS_WAITING_GERAI, 'counter_id' => null]);

    Sanctum::actingAs($petugas);

    $this->postJson('/api/v1/gerai/call')
        ->assertOk()
        ->assertJsonPath('data.id', $ticket->id)
        ->assertJsonPath('data.status', 'calling_gerai')
        ->assertJsonPath('data.gerai_petugas_id', $petugas->id);

    $ticket->refresh();
    expect($ticket->counter_id)->toBe($this->counter->id)
        ->and($ticket->counter_name)->toBe($this->counter->name);
});

test('ticket disappears from other gerai waiting after being called', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);
    $otherCounter = Counter::create(['gerai_id' => $this->gerai->id, 'code' => 'B', 'name' => 'Loket 2', 'is_active' => true]);
    $ticket = Queue::factory()->create(['service_id' => $this->service->id, 'status' => Queue::STATUS_WAITING_GERAI, 'counter_id' => null]);

    Sanctum::actingAs($petugas);

    $this->postJson('/api/v1/gerai/call')->assertOk();

    $this->getJson("/api/v1/gerai/{$otherCounter->id}/waiting")
        ->assertOk()
        ->assertJsonCount(0, 'data');

    $this->getJson("/api/v1/gerai/{$this->counter->id}/calling")
        ->assertOk()
        ->assertJsonPath('data.id', $ticket->id);
});

test('gerai can complete a calling ticket', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);
    $ticket = Queue::factory()->callingGerai($this->counter, $petugas->id)->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($petugas);

    $this->postJson('/api/v1/gerai/complete', [
        'ticket_id' => $ticket->id,
        'catatan' => 'Selesai',
    ])->assertOk()
        ->assertJsonPath('data.status', 'done')
        ->assertJsonPath('data.catatan', 'Selesai');

    expect($ticket->fresh()->done_at)->not->toBeNull();
});

test('gerai can recall a calling ticket when applicant is absent', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);
    $ticket = Queue::factory()->callingGerai($this->counter, $petugas->id)->create(['service_id' => $this->service->id]);
    $ticket->forceFill(['gerai_called_at' => now()->subMinutes(5)])->save();

    Sanctum::actingAs($petugas);

    $this->postJson('/api/v1/gerai/recall', [
        'ticket_id' => $ticket->id,
    ])->assertOk()
        ->assertJsonPath('data.id', $ticket->id)
        ->assertJsonPath('data.status', 'calling_gerai');

    $ticket->refresh();
    expect($ticket->status)->toBe('calling_gerai')
        ->and($ticket->gerai_called_at)->not->toBeNull()
        ->and($ticket->gerai_called_at->greaterThanOrEqualTo(now()->subMinute()))->toBeTrue();
});

test('gerai cannot recall a calling ticket assigned to another counter', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);
    $otherCounter = Counter::create(['gerai_id' => $this->gerai->id, 'code' => 'B', 'name' => 'Loket 2', 'is_active' => true]);
    $ticket = Queue::factory()->callingGerai($otherCounter, $petugas->id)->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($petugas);

    $this->postJson('/api/v1/gerai/recall', [
        'ticket_id' => $ticket->id,
    ])->assertStatus(422)
        ->assertJsonPath('success', false);

    expect($ticket->fresh()->status)->toBe('calling_gerai');
});

test('gerai cannot complete a calling ticket assigned to another counter', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);
    $otherCounter = Counter::create(['gerai_id' => $this->gerai->id, 'code' => 'B', 'name' => 'Loket 2', 'is_active' => true]);
    $ticket = Queue::factory()->callingGerai($otherCounter, $petugas->id)->create(['service_id' => $this->service->id]);

    Sanctum::actingAs($petugas);

    $this->postJson('/api/v1/gerai/complete', [
        'ticket_id' => $ticket->id,
    ])->assertStatus(422)
        ->assertJsonPath('success', false);

    expect($ticket->fresh()->status)->toBe('calling_gerai');
});

test('fo role cannot access gerai endpoints', function () {
    $fo = makeFoUser($this->roleFo);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/gerai/call')->assertForbidden();
});

test('gerai list returns active counters with lokasi', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);

    Sanctum::actingAs($petugas);

    $this->getJson('/api/v1/gerai')
        ->assertOk()
        ->assertJsonPath('data.0.id', $this->counter->id)
        ->assertJsonPath('data.0.nama', 'Loket 1 - Pendaftaran')
        ->assertJsonPath('data.0.lokasi', 'Lantai 1 Zona A');
});

test('login via username key returns fo role and gerai info', function () {
    $petugas = makeGeraiUser($this->roleGerai, $this->counter);
    $petugas->update(['name' => 'gerai1', 'password' => 'lerd123']);

    $this->postJson('/api/v1/auth/login', [
        'username' => 'gerai1',
        'password' => 'lerd123',
    ])->assertOk()
        ->assertJsonPath('data.user.role', 'gerai')
        ->assertJsonPath('data.user.counter_id', $this->counter->id)
        ->assertJsonPath('data.user.counter_nama', 'Loket 1 - Pendaftaran');
});

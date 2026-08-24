<?php

use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\Gerai;
use App\Models\MppService;
use App\Models\MppServiceRequest;
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

function makeSyncFoUser(Role $role): User
{
    return User::factory()->create(['role_id' => $role->id]);
}

function makeSyncGeraiUser(Role $role, Counter $counter): User
{
    $user = User::factory()->create(['role_id' => $role->id]);
    CounterUser::create([
        'counter_id' => $counter->id,
        'user_id' => $user->id,
        'is_active' => true,
    ]);

    return $user;
}

/**
 * Buat tiket antrian yang terhubung ke pengajuan layanan (queue_id).
 */
function makeLinkedTicket(MppService $service): array
{
    $ticket = Queue::factory()->create(['service_id' => $service->id]);

    $request = MppServiceRequest::factory()->create([
        'mpp_service_id' => $service->id,
        'queue_id' => $ticket->id,
    ]);

    return [$ticket, $request];
}

test('fo call marks pending request as processed', function () {
    $fo = makeSyncFoUser($this->roleFo);
    [$ticket, $request] = makeLinkedTicket($this->service);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/call')->assertOk();

    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_PROCESSED);
});

test('request follows full flow until completed', function () {
    $fo = makeSyncFoUser($this->roleFo);
    $petugasGerai = makeSyncGeraiUser($this->roleGerai, $this->counter);
    [$ticket, $request] = makeLinkedTicket($this->service);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/call')->assertOk();
    $this->postJson('/api/v1/fo/forward', [
        'ticket_id' => $ticket->id,
    ])->assertOk();

    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_PROCESSED);

    Sanctum::actingAs($petugasGerai);

    $this->postJson('/api/v1/gerai/call')->assertOk();
    $this->postJson('/api/v1/gerai/complete', [
        'ticket_id' => $ticket->id,
    ])->assertOk();

    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_COMPLETED);
});

test('fo reject marks pending request as rejected', function () {
    $fo = makeSyncFoUser($this->roleFo);
    [$ticket, $request] = makeLinkedTicket($this->service);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/call')->assertOk();
    $this->postJson('/api/v1/fo/reject', [
        'ticket_id' => $ticket->id,
        'alasan' => 'Berkas tidak lengkap',
    ])->assertOk();

    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_REJECTED);
});

test('fo skip returns request status back to pending', function () {
    $fo = makeSyncFoUser($this->roleFo);
    [$ticket, $request] = makeLinkedTicket($this->service);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/call')->assertOk();
    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_PROCESSED);

    $this->postJson('/api/v1/fo/skip', ['ticket_id' => $ticket->id])->assertOk();

    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_PENDING);
});

test('queue without linked request leaves mpp request untouched', function () {
    $fo = makeSyncFoUser($this->roleFo);
    $ticket = Queue::factory()->create(['service_id' => $this->service->id]);
    $other = MppServiceRequest::factory()->create(['mpp_service_id' => $this->service->id]);

    Sanctum::actingAs($fo);

    $this->postJson('/api/v1/fo/call')->assertOk();

    expect($other->fresh()->status)->toBe(MppServiceRequest::STATUS_PENDING);
});

test('request status follows queue status even when updated directly via model', function () {
    [$ticket, $request] = makeLinkedTicket($this->service);

    $ticket->update(['status' => Queue::STATUS_DONE, 'done_at' => now()]);
    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_COMPLETED);

    $ticket->update(['status' => Queue::STATUS_REJECTED]);
    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_REJECTED);

    $ticket->update(['status' => Queue::STATUS_WAITING_FO]);
    expect($request->fresh()->status)->toBe(MppServiceRequest::STATUS_PENDING);
});

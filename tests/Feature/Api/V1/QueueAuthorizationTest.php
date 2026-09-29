<?php

use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\Gerai;
use App\Models\MppService;
use App\Models\Opd;
use App\Models\Queue;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['services.firebase.credentials' => null, 'services.firebase.database_url' => null]);
    $this->foRole = Role::create(['name' => 'Front Office', 'slug' => 'petugasfrontoffice']);
    $this->geraiRole = Role::create(['name' => 'Gerai', 'slug' => 'gerai']);
    $this->adminRole = Role::create(['name' => 'Superadmin', 'slug' => 'superadmin']);
    $this->opd = Opd::factory()->create();
    $this->gerai = Gerai::factory()->create(['opd_id' => $this->opd->id, 'is_active' => true]);
    $this->counter = Counter::factory()->create(['gerai_id' => $this->gerai->id, 'is_active' => true]);
    $this->service = MppService::factory()->create(['opd_id' => $this->opd->id, 'gerai_id' => $this->gerai->id]);
    $this->operator = User::factory()->create(['role_id' => $this->geraiRole->id]);
    $this->assignment = CounterUser::create(['user_id' => $this->operator->id, 'counter_id' => $this->counter->id, 'is_active' => true]);
});

test('FO cannot mutate another officers calling ticket', function (string $action) {
    $owner = User::factory()->create(['role_id' => $this->foRole->id]);
    $other = User::factory()->create(['role_id' => $this->foRole->id]);
    $ticket = Queue::factory()->callingFo($owner->id)->create(['service_id' => $this->service->id]);
    Sanctum::actingAs($other);

    $this->postJson('/api/v1/fo/'.$action, ['ticket_id' => $ticket->id, 'alasan' => 'Tidak hadir'])
        ->assertForbidden()->assertJsonPath('success', false);

    expect($ticket->fresh()->status)->toBe(Queue::STATUS_CALLING_FO)
        ->and($ticket->fresh()->fo_petugas_id)->toBe($owner->id);
})->with(['forward', 'reject', 'recall', 'skip']);

test('FO sees only its own current ticket even when another call is newer', function () {
    $owner = User::factory()->create(['role_id' => $this->foRole->id]);
    $other = User::factory()->create(['role_id' => $this->foRole->id]);
    $ownTicket = Queue::factory()->callingFo($owner->id)->create(['service_id' => $this->service->id, 'called_at' => now()->subMinute()]);
    Queue::factory()->callingFo($other->id)->create(['service_id' => $this->service->id]);
    Sanctum::actingAs($owner);

    $this->getJson('/api/v1/fo/calling')->assertSuccessful()->assertJsonPath('data.id', $ownTicket->id);
});

test('gerai rejects missing or inactive assignments counters and gerai', function (string $disabled, string $action) {
    $ticket = Queue::factory()->callingGerai($this->counter, $this->operator->id)->create(['service_id' => $this->service->id]);
    match ($disabled) {
        'missing' => $this->assignment->delete(),
        'assignment' => $this->assignment->update(['is_active' => false]),
        'counter' => $this->counter->update(['is_active' => false]),
        'gerai' => $this->gerai->update(['is_active' => false]),
    };
    Sanctum::actingAs($this->operator);

    if (in_array($action, ['waiting', 'calling'], true)) {
        $this->getJson('/api/v1/gerai/'.$this->counter->id.'/'.$action)->assertForbidden();
    } else {
        $this->postJson('/api/v1/gerai/'.$action, ['ticket_id' => $ticket->id])->assertForbidden();
    }

    expect($ticket->fresh()->status)->toBe(Queue::STATUS_CALLING_GERAI);
})->with(['missing', 'assignment', 'counter', 'gerai'])->with(['waiting', 'calling', 'call', 'complete', 'recall']);

test('gerai cannot read or mutate another counter tickets', function (string $action) {
    $otherCounter = Counter::factory()->create(['gerai_id' => $this->gerai->id, 'is_active' => true]);
    $ticket = Queue::factory()->callingGerai($otherCounter)->create(['service_id' => $this->service->id]);
    Sanctum::actingAs($this->operator);

    if (in_array($action, ['waiting', 'calling'], true)) {
        $this->getJson('/api/v1/gerai/'.$otherCounter->id.'/'.$action)->assertForbidden();
    } else {
        $this->postJson('/api/v1/gerai/'.$action, ['ticket_id' => $ticket->id])->assertForbidden();
    }

    expect($ticket->fresh()->status)->toBe(Queue::STATUS_CALLING_GERAI);
})->with(['waiting', 'calling', 'complete', 'recall']);

test('superadmin override is explicit and audited', function (string $stage, string $action) {
    $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
    $ticket = $stage === 'fo'
        ? Queue::factory()->callingFo()->create(['service_id' => $this->service->id])
        : Queue::factory()->callingGerai($this->counter, $this->operator->id)->create(['service_id' => $this->service->id]);
    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/'.$stage.'/'.$action, ['ticket_id' => $ticket->id, 'alasan' => 'Koreksi supervisor'])->assertSuccessful();

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $admin->id,
        'action' => 'queue_supervisor_override',
        'target_table' => 'mpp_queues',
        'target_id' => (string) $ticket->id,
    ]);
})->with([['fo', 'forward'], ['fo', 'reject'], ['fo', 'skip'], ['fo', 'recall'], ['gerai', 'complete'], ['gerai', 'recall']]);

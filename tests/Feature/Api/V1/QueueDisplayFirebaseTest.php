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
use App\Services\FirebaseService;
use App\Services\QueueService;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

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

    $this->app->instance(FirebaseService::class, $this->firebaseMock = mockFirebase());
});

function mockFirebase(): MockInterface
{
    $mock = Mockery::mock(FirebaseService::class);
    $mock->shouldReceive('broadcastQueueEvent')->zeroOrMoreTimes();
    $mock->shouldReceive('publishCurrentCall')->zeroOrMoreTimes();
    $mock->shouldReceive('setActiveCounter')->zeroOrMoreTimes();
    $mock->shouldReceive('appendRecentHistory')->zeroOrMoreTimes();

    return $mock;
}

function makeDisplayFoUser(Role $role): User
{
    return User::factory()->create(['role_id' => $role->id]);
}

function makeDisplayGeraiUser(Role $role, Counter $counter): User
{
    $user = User::factory()->create(['role_id' => $role->id]);
    CounterUser::create([
        'counter_id' => $counter->id,
        'user_id' => $user->id,
        'is_active' => true,
    ]);

    return $user;
}

function makeDisplayLinkedTicket(MppService $service): Queue
{
    $ticket = Queue::factory()->create(['service_id' => $service->id]);

    MppServiceRequest::factory()->create([
        'mpp_service_id' => $service->id,
        'queue_id' => $ticket->id,
    ]);

    return $ticket;
}

test('createTicket menambahkan riwayat Menunggu ke recent_history', function () {
    $ticket = app(QueueService::class)->takeTicket($this->service->id);

    expect($ticket->status)->toBe(Queue::STATUS_WAITING_FO);

    $this->firebaseMock->shouldHaveReceived('appendRecentHistory')->once()->with(Mockery::on(function ($entry) use ($ticket) {
        return $entry['queue_number'] === $ticket->number
            && $entry['status'] === 'Menunggu'
            && $entry['gerai_name'] === $this->gerai->name
            && is_int($entry['timestamp']);
    }));
});

test('foCall mempublikasikan current_call dan kartu loket fo', function () {
    $fo = makeDisplayFoUser($this->roleFo);
    $ticket = makeDisplayLinkedTicket($this->service);
    $service = app(QueueService::class);

    $called = $service->foCall($fo);

    expect($called->number)->toBe($ticket->number);

    $this->firebaseMock->shouldHaveReceived('publishCurrentCall')->once()->with(Mockery::on(function ($payload) use ($ticket) {
        return $payload['queue_number'] === $ticket->number
            && $payload['gerai_name'] === 'Front Office'
            && $payload['service_type'] === $this->service->name
            && $payload['agency'] === $this->opd->name;
    }));
    $this->firebaseMock->shouldHaveReceived('setActiveCounter')->once()->with('fo', Mockery::on(function ($data) {
        return is_array($data) && $data['label'] === 'Front Office' && is_int($data['timestamp']);
    }));
});

test('foReject menambahkan riwayat Tidak Hadir', function () {
    $fo = makeDisplayFoUser($this->roleFo);
    $ticket = makeDisplayLinkedTicket($this->service);
    $service = app(QueueService::class);

    $service->foCall($fo);
    $service->foReject($ticket->fresh(), $fo, 'Berkas tidak lengkap');

    expect($ticket->fresh()->status)->toBe(Queue::STATUS_REJECTED);

    $this->firebaseMock->shouldHaveReceived('appendRecentHistory')->once()->with(Mockery::on(function ($entry) {
        return $entry['status'] === 'Tidak Hadir'
            && $entry['gerai_name'] === 'Front Office';
    }));
});

test('foSkip menghapus kartu loket fo', function () {
    $fo = makeDisplayFoUser($this->roleFo);
    $ticket = makeDisplayLinkedTicket($this->service);
    $service = app(QueueService::class);

    $service->foCall($fo);
    $service->foSkip($ticket->fresh());

    expect($ticket->fresh()->status)->toBe(Queue::STATUS_WAITING_FO);

    $this->firebaseMock->shouldHaveReceived('setActiveCounter')->with('fo', null);
});

test('geraiCall mempublikasikan current_call dan kartu loket gerai', function () {
    $fo = makeDisplayFoUser($this->roleFo);
    $petugas = makeDisplayGeraiUser($this->roleGerai, $this->counter);
    $ticket = makeDisplayLinkedTicket($this->service);
    $service = app(QueueService::class);

    $service->foCall($fo);
    $service->foForward($ticket->fresh(), $fo);
    $service->geraiCall($petugas);

    expect($ticket->fresh()->status)->toBe(Queue::STATUS_CALLING_GERAI);

    $this->firebaseMock->shouldHaveReceived('publishCurrentCall')->with(Mockery::on(function ($payload) {
        return $payload['gerai_name'] === $this->counter->name
            && $payload['service_type'] === $this->service->name
            && $payload['agency'] === $this->opd->name;
    }));
    $this->firebaseMock->shouldHaveReceived('setActiveCounter')->with('gerai-'.$this->counter->id, Mockery::on(function ($data) {
        return is_array($data) && $data['label'] === $this->counter->name;
    }));
});

test('geraiComplete menghapus kartu loket dan menambahkan riwayat Selesai', function () {
    $fo = makeDisplayFoUser($this->roleFo);
    $petugas = makeDisplayGeraiUser($this->roleGerai, $this->counter);
    $ticket = makeDisplayLinkedTicket($this->service);
    $service = app(QueueService::class);

    $service->foCall($fo);
    $service->foForward($ticket->fresh(), $fo);
    $service->geraiCall($petugas);
    $service->geraiComplete($ticket->fresh(), $petugas);

    expect($ticket->fresh()->status)->toBe(Queue::STATUS_DONE);

    $this->firebaseMock->shouldHaveReceived('setActiveCounter')->with('gerai-'.$this->counter->id, null);
    $this->firebaseMock->shouldHaveReceived('appendRecentHistory')->with(Mockery::on(function ($entry) {
        return $entry['status'] === 'Selesai'
            && $entry['gerai_name'] === $this->counter->name;
    }));
});

test('geraiRecall mempublikasikan ulang current_call dengan nomor yang sama', function () {
    $fo = makeDisplayFoUser($this->roleFo);
    $petugas = makeDisplayGeraiUser($this->roleGerai, $this->counter);
    $ticket = makeDisplayLinkedTicket($this->service);
    $service = app(QueueService::class);

    $service->foCall($fo);
    $service->foForward($ticket->fresh(), $fo);
    $service->geraiCall($petugas);

    $recalled = $service->geraiRecall($ticket->fresh());

    expect($recalled->number)->toBe($ticket->number);

    $this->firebaseMock->shouldHaveReceived('publishCurrentCall')->with(Mockery::on(function ($payload) use ($recalled) {
        return $payload['queue_number'] === $recalled->number
            && $payload['gerai_name'] === $this->counter->name;
    }));
});

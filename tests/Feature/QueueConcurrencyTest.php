<?php

use App\Models\MppService;
use App\Models\Opd;
use App\Models\Queue;
use App\Models\Role;
use App\Models\User;
use App\Services\QueueService;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

uses(DatabaseTruncation::class);

afterEach(function () {
    $this->truncateTablesForAllConnections();
});

function concurrentFoCallTask(string $connectionName, array $connectionConfig, int $userId): Closure
{
    return static function () use ($connectionName, $connectionConfig, $userId): string {
        config(['database.default' => $connectionName, 'database.connections.'.$connectionName => $connectionConfig]);
        DB::purge($connectionName);

        try {
            $ticket = app(QueueService::class)->foCall(User::query()->findOrFail($userId));

            return 'called:'.$ticket->id;
        } catch (ValidationException) {
            return 'blocked';
        }
    };
}

function concurrentTicketIssueTask(string $connectionName, array $connectionConfig, int $serviceId, string $key): Closure
{
    return static function () use ($connectionName, $connectionConfig, $serviceId, $key): string {
        config(['database.default' => $connectionName, 'database.connections.'.$connectionName => $connectionConfig]);
        DB::purge($connectionName);

        [$ticket, $replayed] = app(QueueService::class)->takeTicketIdempotently($serviceId, false, null, $key);

        return ($replayed ? 'replayed:' : 'issued:').$ticket->id;
    };
}

test('two simultaneous FO calls assign only one active ticket to the same officer', function () {
    expect(DB::connection()->getDriverName())->toBe('mysql');
    config(['services.firebase.credentials' => null, 'services.firebase.database_url' => null]);

    $role = Role::create(['name' => 'Front Office', 'slug' => 'petugasfrontoffice']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $opd = Opd::create(['code' => '11', 'name' => 'Dinas Kependudukan']);
    $service = MppService::create(['name' => 'KTP', 'slug' => 'ktp', 'opd_id' => $opd->id, 'fields' => [], 'is_active' => true]);
    $ticket = Queue::factory()->create(['service_id' => $service->id]);
    $connectionName = config('database.default');
    $connectionConfig = config('database.connections.'.$connectionName);

    $results = Concurrency::driver('process')->run([
        concurrentFoCallTask($connectionName, $connectionConfig, $user->id),
        concurrentFoCallTask($connectionName, $connectionConfig, $user->id),
    ]);
    sort($results);

    expect($results)->toBe(['blocked', 'called:'.$ticket->id])
        ->and(Queue::query()->callingFo()->where('fo_petugas_id', $user->id)->count())->toBe(1)
        ->and($ticket->fresh()->status)->toBe(Queue::STATUS_CALLING_FO);
});

test('two simultaneous ticket retries with one idempotency key issue one ticket', function () {
    expect(DB::connection()->getDriverName())->toBe('mysql');
    config(['services.firebase.credentials' => null, 'services.firebase.database_url' => null]);

    $opd = Opd::create(['code' => '11', 'name' => 'Dinas Kependudukan']);
    $service = MppService::create(['name' => 'KTP', 'slug' => 'ktp', 'opd_id' => $opd->id, 'fields' => [], 'is_active' => true]);
    $connectionName = config('database.default');
    $connectionConfig = config('database.connections.'.$connectionName);
    $key = 'kiosk-retry-'.fake()->uuid();

    $results = Concurrency::driver('process')->run([
        concurrentTicketIssueTask($connectionName, $connectionConfig, $service->id, $key),
        concurrentTicketIssueTask($connectionName, $connectionConfig, $service->id, $key),
    ]);
    sort($results);

    $ticketId = Queue::query()->sole()->id;

    expect($results)->toBe(['issued:'.$ticketId, 'replayed:'.$ticketId])
        ->and(Queue::query()->count())->toBe(1)
        ->and(DB::table('mpp_ticket_idempotency_keys')->where('key', $key)->value('queue_id'))->toBe($ticketId);
});

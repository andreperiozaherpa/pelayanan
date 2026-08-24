<?php

use App\Http\Resources\V1\QueueTicketResource;
use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\Gerai;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Models\Opd;
use App\Models\Queue;
use App\Models\Role;
use App\Models\User;
use App\Services\QueueService;
use Carbon\Carbon;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

function makeDurationFoUser(Role $role): User
{
    return User::factory()->create(['role_id' => $role->id]);
}

function makeDurationGeraiUser(Role $role, Counter $counter): User
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
 * Buat tiket antrian yang terhubung ke pengajuan layanan.
 */
function makeDurationLinkedTicket(MppService $service): array
{
    $ticket = Queue::factory()->create(['service_id' => $service->id]);

    $request = MppServiceRequest::factory()->create([
        'mpp_service_id' => $service->id,
        'queue_id' => $ticket->id,
    ]);

    return [$ticket, $request];
}

test('durasi_fo dihitung dari fo_called_at ke fo_finished_at saat forward', function () {
    $fo = makeDurationFoUser($this->roleFo);
    $service = app(QueueService::class);

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 0, 0));
    [$ticket] = makeDurationLinkedTicket($this->service);

    $service->foCall($fo);
    $ticket->refresh();
    $this->travelTo(Carbon::create(2026, 8, 11, 9, 5, 30));
    $service->foForward($ticket, $fo);

    $ticket->refresh();
    expect($ticket->durasi_fo)->toBe('5 menit 30 detik')
        ->and($ticket->durasi_fo_detik)->toBe(330);
});

test('durasi_gerai dan durasi_total dihitung sampai gerai selesai', function () {
    $fo = makeDurationFoUser($this->roleFo);
    $petugasGerai = makeDurationGeraiUser($this->roleGerai, $this->counter);
    $service = app(QueueService::class);

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 0, 0));
    [$ticket] = makeDurationLinkedTicket($this->service);

    $service->foCall($fo);
    $ticket->refresh();
    $service->foForward($ticket, $fo);
    $ticket->refresh();

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 6, 0));
    $service->geraiCall($petugasGerai);

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 10, 0));
    $service->geraiComplete($ticket, $petugasGerai);

    $ticket->refresh();
    expect($ticket->durasi_gerai)->toBe('4 menit 0 detik')
        ->and($ticket->durasi_gerai_detik)->toBe(240)
        ->and($ticket->durasi_total)->toBe('10 menit 0 detik')
        ->and($ticket->durasi_total_detik)->toBe(600);
});

test('reject dan skip mengisi fo_finished_at', function () {
    $fo = makeDurationFoUser($this->roleFo);
    $service = app(QueueService::class);

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 0, 0));
    [$ticket] = makeDurationLinkedTicket($this->service);

    $service->foCall($fo);
    $ticket->refresh();
    $this->travelTo(Carbon::create(2026, 8, 11, 9, 2, 0));
    $service->foReject($ticket, $fo, 'Berkas tidak lengkap');

    expect($ticket->fresh()->durasi_fo)->toBe('2 menit 0 detik');

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 4, 0));
    [$skipTicket] = makeDurationLinkedTicket($this->service);
    $service->foCall($fo);
    $skipTicket->refresh();
    $this->travelTo(Carbon::create(2026, 8, 11, 9, 6, 30));
    $service->foSkip($skipTicket);

    expect($skipTicket->fresh()->durasi_fo)->toBe('2 menit 30 detik');
});

test('dipanggil ulang setelah skip mereset fo_finished_at sehingga durasi berjalan dari nol', function () {
    $fo = makeDurationFoUser($this->roleFo);
    $service = app(QueueService::class);

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 0, 0));
    [$ticket] = makeDurationLinkedTicket($this->service);

    $service->foCall($fo);
    $ticket->refresh();
    $this->travelTo(Carbon::create(2026, 8, 11, 9, 3, 0));
    $service->foSkip($ticket);

    expect($ticket->fresh()->durasi_fo)->toBe('3 menit 0 detik');

    $this->travelTo(Carbon::create(2026, 8, 11, 9, 5, 0));
    $service->foCall($fo);

    $ticket->refresh();
    expect($ticket->fo_finished_at)->toBeNull()
        ->and($ticket->durasi_fo)->toBeNull();
});

test('durasi bernilai null selama tahap belum selesai', function () {
    $this->travelTo(Carbon::create(2026, 8, 11, 9, 0, 0));
    [$ticket] = makeDurationLinkedTicket($this->service);

    expect($ticket->durasi_fo)->toBeNull()
        ->and($ticket->durasi_gerai)->toBeNull()
        ->and($ticket->durasi_total)->toBeNull();
});

test('QueueTicketResource memuat field timestamp dan durasi', function () {
    $this->travelTo(Carbon::create(2026, 8, 11, 9, 0, 0));
    [$ticket] = makeDurationLinkedTicket($this->service);
    $ticket->forceFill([
        'fo_called_at' => Carbon::create(2026, 8, 11, 9, 0, 0),
        'fo_finished_at' => Carbon::create(2026, 8, 11, 9, 5, 0),
        'gerai_called_at' => Carbon::create(2026, 8, 11, 9, 6, 0),
        'done_at' => Carbon::create(2026, 8, 11, 9, 10, 0),
    ])->save();

    $data = (new QueueTicketResource($ticket))->resolve();

    expect($data['fo_called_at'])->not->toBeNull()
        ->and($data['fo_finished_at'])->not->toBeNull()
        ->and($data['gerai_called_at'])->not->toBeNull()
        ->and($data['durasi_fo'])->toBe('5 menit 0 detik')
        ->and($data['durasi_fo_detik'])->toBe(300)
        ->and($data['durasi_gerai'])->toBe('4 menit 0 detik')
        ->and($data['durasi_gerai_detik'])->toBe(240)
        ->and($data['durasi_total'])->toBe('10 menit 0 detik')
        ->and($data['durasi_total_detik'])->toBe(600);
});

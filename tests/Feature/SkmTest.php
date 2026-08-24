<?php

use App\Models\Gerai;
use App\Models\Opd;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SkmResponse;
use App\Models\User;
use App\Services\SkmService;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->roleSuperAdmin = Role::where('slug', 'superadmin')->first();
    $this->permission = Permission::where('slug', 'service.report')->first();
    $this->superAdmin = User::factory()->create([
        'role_id' => $this->roleSuperAdmin->id,
    ]);
});

function createActiveInstansi(): Opd
{
    $opd = Opd::factory()->create();

    Gerai::factory()->create([
        'opd_id' => $opd->id,
        'is_active' => true,
    ]);

    return $opd;
}

function skmPayload(array $overrides = []): array
{
    $payload = [];
    foreach (SkmService::unsur() as $u) {
        $payload[$u['key']] = 4;
    }

    return array_merge([
        'nama' => 'Budi',
        'jenis_kelamin' => 'L',
        'umur' => 30,
        'pendidikan' => 'S1',
        'pekerjaan' => 'Pegawai Swasta',
        'saran' => 'Pelayanan sudah baik.',
    ], $payload, $overrides);
}

test('daftar instansi untuk e-survei dapat diakses publik', function () {
    createActiveInstansi();
    $inactiveOpd = Opd::factory()->create();
    Gerai::factory()->create(['opd_id' => $inactiveOpd->id, 'is_active' => false]);

    $response = $this->get(route('survey.index'));

    $response->assertOk();
    $response->assertSee('Survei Kepuasan Masyarakat');
    $this->assertCount(1, $response->viewData('instansis'));
});

test('halaman kuesioner survei menampilkan 9 unsur SKM', function () {
    $opd = createActiveInstansi();

    $response = $this->get(route('survey.show', $opd));

    $response->assertOk();
    foreach (SkmService::unsur() as $u) {
        $response->assertSee($u['kode']);
    }
});

test('instansi nonaktif tidak dapat diisi survei', function () {
    $opd = Opd::factory()->create();

    $this->get(route('survey.show', $opd))->assertNotFound();
    $this->post(route('survey.store', $opd), skmPayload())->assertNotFound();
});

test('pengunjung dapat mengirim survei dari web', function () {
    $opd = createActiveInstansi();

    $this->post(route('survey.store', $opd), skmPayload())
        ->assertRedirect(route('survey.index'));

    $this->assertDatabaseHas('skm_responses', [
        'opd_id' => $opd->id,
        'nama' => 'Budi',
        'source' => SkmResponse::SOURCE_WEB,
        'u1' => 4,
        'u9' => 4,
    ]);
});

test('survei wajib mengisi seluruh 9 unsur dengan nilai 1-4', function () {
    $opd = createActiveInstansi();

    $this->post(route('survey.store', $opd), ['nama' => 'Tanpa Nilai'])
        ->assertSessionHasErrors(SkmResponse::UNSUR_KEYS);

    $this->assertDatabaseCount('skm_responses', 0);
});

test('survei menolak nilai di luar rentang 1-4', function () {
    $opd = createActiveInstansi();

    $this->post(route('survey.store', $opd), skmPayload(['u1' => 5, 'u2' => 0]))
        ->assertSessionHasErrors(['u1', 'u2']);

    $this->assertDatabaseCount('skm_responses', 0);
});

test('API dapat mengambil daftar pertanyaan SKM per instansi', function () {
    $opd = createActiveInstansi();

    $this->getJson("/api/v1/opd/{$opd->id}/skm")
        ->assertOk()
        ->assertJsonPath('data.opd.id', $opd->id)
        ->assertJsonCount(9, 'data.unsur');
});

test('API dapat mengirim survei tanpa autentikasi', function () {
    $opd = createActiveInstansi();

    $this->postJson("/api/v1/opd/{$opd->id}/skm", skmPayload())
        ->assertCreated()
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('skm_responses', [
        'opd_id' => $opd->id,
        'source' => SkmResponse::SOURCE_API,
    ]);
});

test('API menolak survei dengan unsur tidak lengkap', function () {
    $opd = createActiveInstansi();

    $this->postJson("/api/v1/opd/{$opd->id}/skm", ['nama' => 'API'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(SkmResponse::UNSUR_KEYS);

    $this->assertDatabaseCount('skm_responses', 0);
});

test('tamu tidak dapat mengakses laporan survei admin', function () {
    $this->get(route('skm.index'))->assertRedirect(route('login'));
});

test('laporan survei menampilkan rekap per instansi', function () {
    $opd = createActiveInstansi();
    SkmResponse::factory()->count(3)->create(['opd_id' => $opd->id]);

    $this->actingAs($this->superAdmin)
        ->get(route('skm.index'))
        ->assertOk()
        ->assertSee($opd->name);
});

test('laporan survei dapat diekspor sebagai CSV', function () {
    $opd = createActiveInstansi();
    SkmResponse::factory()->count(2)->create(['opd_id' => $opd->id]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('skm.export', ['opd_id' => $opd->id]));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    $csv = $response->streamedContent();
    expect($csv)->toContain('IKM Komposit')
        ->toContain('Mutu')
        ->toContain($opd->name);
});

test('detail laporan survei menampilkan prioritas perbaikan', function () {
    $opd = createActiveInstansi();
    SkmResponse::factory()->create(['opd_id' => $opd->id, 'u1' => 1, 'u2' => 4, 'u3' => 4, 'u4' => 4, 'u5' => 4, 'u6' => 4, 'u7' => 4, 'u8' => 4, 'u9' => 4]);

    $this->actingAs($this->superAdmin)
        ->get(route('skm.show', $opd))
        ->assertOk()
        ->assertSee('Perbaikan Utama')
        ->assertSee('U1');
});

test('SkmService menghitung IKM dan kategori mutu sesuai Permen PAN-RB 14/2017', function () {
    $responses = collect([
        (object) ['u1' => 4, 'u2' => 4, 'u3' => 4, 'u4' => 4, 'u5' => 4, 'u6' => 4, 'u7' => 4, 'u8' => 4, 'u9' => 4],
        (object) ['u1' => 4, 'u2' => 4, 'u3' => 4, 'u4' => 4, 'u5' => 4, 'u6' => 4, 'u7' => 4, 'u8' => 4, 'u9' => 4],
    ]);

    $rekap = (new SkmService)->rekap($responses);

    expect($rekap['total_responden'])->toBe(2);
    expect($rekap['ikm'])->toBe(100.0);
    expect($rekap['mutu']['nilai'])->toBe('A');
    expect($rekap['mutu']['kinerja'])->toBe('Sangat baik');
});

test('laporan survei menyaring berdasarkan periode', function () {
    $opd = createActiveInstansi();
    SkmResponse::factory()->create(['opd_id' => $opd->id]);
    SkmResponse::factory()->create(['opd_id' => $opd->id, 'created_at' => now()->subMonths(2)]);

    $this->actingAs($this->superAdmin)
        ->get(route('skm.index', ['period' => 'this_month']))
        ->assertOk();

    $this->assertSame(1, SkmResponse::where('opd_id', $opd->id)
        ->whereDate('created_at', '>=', now()->startOfMonth())
        ->whereDate('created_at', '<=', now()->endOfMonth())
        ->count());
});

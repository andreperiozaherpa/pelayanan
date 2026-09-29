<?php

use App\Models\Gerai;
use App\Models\MppKioskDevice;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Models\MppTicketTemplate;
use App\Models\MppTicketTemplateVersion;
use App\Models\Opd;
use App\Models\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    MppKioskDevice::create(['name' => 'Test kiosk', 'token_hash' => hash('sha256', 'test-kiosk-token')]);
    $this->withToken('test-kiosk-token');
    $this->opd = Opd::create([
        'code' => '11',
        'name' => 'Dinas Kependudukan dan Pencatatan Sipil',
    ]);

    $this->gerai = Gerai::create([
        'code' => 'A',
        'name' => 'Gerai Dukcapil',
        'location' => 'Lantai 1 Zona A',
        'opd_id' => $this->opd->id,
        'is_active' => true,
    ]);

    $this->service = MppService::create([
        'name' => 'Layanan KTP',
        'slug' => 'layanan-ktp',
        'opd_id' => $this->opd->id,
        'fields' => [
            [
                'id' => 'field_1',
                'name' => 'nik',
                'label' => 'NIK',
                'type' => 'number',
                'required' => true,
            ],
            [
                'id' => 'field_2',
                'name' => 'nama_lengkap',
                'label' => 'Nama Lengkap',
                'type' => 'text',
                'required' => true,
            ],
            [
                'id' => 'field_3',
                'name' => 'jenis_kelamin',
                'label' => 'Jenis Kelamin',
                'type' => 'select',
                'required' => true,
                'options' => ['L', 'P'],
            ],
            [
                'id' => 'field_4',
                'name' => 'foto_ktp',
                'label' => 'Foto KTP',
                'type' => 'file',
                'required' => false,
            ],
        ],
        'is_active' => true,
    ]);
});

test('guest dapat melihat detail pelayanan beserta fields form', function () {
    $this->getJson("/api/v1/services/{$this->service->id}")
        ->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonPath('data.id', $this->service->id)
        ->assertJsonPath('data.nama', 'Layanan KTP')
        ->assertJsonPath('data.instansi.gerai.kode', 'A')
        ->assertJsonPath('data.fields.0.name', 'nik')
        ->assertJsonPath('data.fields.0.required', true)
        ->assertJsonPath('data.fields.2.options', ['L', 'P']);
});

test('guest tidak dapat melihat detail pelayanan yang tidak aktif', function () {
    $this->service->update(['is_active' => false]);

    $this->getJson("/api/v1/services/{$this->service->id}")
        ->assertStatus(404);
});

test('guest dapat mengisi form pelayanan via api', function () {
    $response = $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
        'notes' => 'Berkas lengkap',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonPath('data.status', 'waiting_fo')
        ->assertJsonPath('data.nomor_antrian', 'A-001')
        ->assertJsonPath('data.mpp_service_id', $this->service->id)
        ->assertJsonPath('data.receipt.nomor_antrian', 'A-001')
        ->assertJsonPath('data.receipt.service_name', 'Layanan KTP')
        ->assertJsonPath('data.receipt.instansi_name', 'Dinas Kependudukan dan Pencatatan Sipil')
        ->assertJsonPath('data.receipt.gerai_name', 'Gerai Dukcapil')
        ->assertJsonPath('data.receipt.remaining_queue', 0);

    expect($response->json('data.receipt.queue_id'))->toBe($response->json('data.queue_id'));

    $this->assertDatabaseHas('mpp_service_requests', [
        'mpp_service_id' => $this->service->id,
        'status' => 'PENDING',
    ]);

    $this->assertDatabaseHas('mpp_queues', [
        'service_id' => $this->service->id,
        'number' => 'A-001',
        'status' => 'waiting_fo',
    ]);

    $request = MppServiceRequest::first();
    expect($request->nomor_antrian)->toBe('A-001');
    expect($request->queue_id)->not->toBeNull();
    expect($request->queue_id)->toBe(Queue::where('number', 'A-001')->value('id'));
    expect($request->submitted_form_data['nik']['value'])->toBe('1234567890123456');
    expect($request->submitted_form_data['nama_lengkap']['value'])->toBe('Budi Santoso');
    expect($request->submitted_form_data['jenis_kelamin']['value'])->toBe('L');
    expect($request->submitted_form_data['foto_ktp']['value'])->toBeNull();
});

test('kiosk menerima template aktif dan tiket mengikat versi template saat diterbitkan', function () {
    $layout = [
        'schema' => 1,
        'paper_width_mm' => 58,
        'print_width_mm' => 55,
        'blocks' => [['type' => 'queue_number', 'align' => 'center']],
    ];
    $template = MppTicketTemplate::create(['draft_layout' => $layout]);
    $version = MppTicketTemplateVersion::create([
        'mpp_ticket_template_id' => $template->id,
        'version' => 1,
        'layout' => $layout,
        'checksum' => hash('sha256', json_encode($layout, JSON_THROW_ON_ERROR)),
        'published_at' => now(),
    ]);
    $template->update(['active_version_id' => $version->id]);

    $this->getJson('/api/v1/ticket-template')
        ->assertOk()
        ->assertHeader('ETag', '"'.$version->checksum.'"')
        ->assertJsonPath('data.id', $version->id)
        ->assertJsonPath('data.layout.blocks.0.type', 'queue_number');

    $response = $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.receipt.template_version_id', $version->id);

    expect(Queue::query()->sole()->mpp_ticket_template_version_id)->toBe($version->id);
});

test('retry pengajuan kiosk dengan idempotency key mengembalikan tiket yang sama', function () {
    $key = 'kiosk-request-'.fake()->uuid();
    $payload = [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
        'notes' => 'Berkas lengkap',
    ];

    $first = $this->withHeaders(['Idempotency-Key' => $key])
        ->postJson("/api/v1/services/{$this->service->id}/requests", $payload);

    $first->assertCreated()
        ->assertHeader('Idempotency-Replayed', 'false');

    $second = $this->withHeaders(['Idempotency-Key' => $key])
        ->postJson("/api/v1/services/{$this->service->id}/requests", $payload);

    $second->assertOk()
        ->assertHeader('Idempotency-Replayed', 'true')
        ->assertJsonPath('data.id', $first->json('data.id'))
        ->assertJsonPath('data.queue_id', $first->json('data.queue_id'));

    $this->assertDatabaseCount('mpp_service_requests', 1);
    $this->assertDatabaseCount('mpp_queues', 1);
});

test('idempotency key pengajuan kiosk menolak payload yang berbeda', function () {
    $key = 'kiosk-request-'.fake()->uuid();

    $this->withHeaders(['Idempotency-Key' => $key])
        ->postJson("/api/v1/services/{$this->service->id}/requests", [
            'form_data' => [
                'nik' => '1234567890123456',
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
            ],
        ])
        ->assertCreated();

    $this->withHeaders(['Idempotency-Key' => $key])
        ->postJson("/api/v1/services/{$this->service->id}/requests", [
            'form_data' => [
                'nik' => '1234567890123456',
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'P',
            ],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('idempotency_key');

    $this->assertDatabaseCount('mpp_service_requests', 1);
    $this->assertDatabaseCount('mpp_queues', 1);
});

test('isi form api memvalidasi field wajib', function () {
    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
        ],
    ])->assertStatus(422);

    $this->assertDatabaseCount('mpp_service_requests', 0);
});

test('isi form api memvalidasi nilai select', function () {
    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'X',
        ],
    ])->assertStatus(422);
});

test('isi form api mendukung field checkbox', function () {
    $this->service->update([
        'fields' => array_merge($this->service->fields, [
            [
                'id' => 'field_5',
                'name' => 'kelengkapan_berkas',
                'label' => 'Kelengkapan Berkas',
                'type' => 'checkbox',
                'required' => true,
                'options' => ['KK', 'KTP', 'Surat Pindah'],
            ],
        ]),
    ]);

    $response = $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'kelengkapan_berkas' => ['KK', 'KTP'],
        ],
    ]);

    $response->assertStatus(201);

    $request = MppServiceRequest::first();
    expect($request->submitted_form_data['kelengkapan_berkas']['value'])->toBe(['KK', 'KTP']);
});

test('isi form api menolak nilai checkbox di luar opsi', function () {
    $this->service->update([
        'fields' => array_merge($this->service->fields, [
            [
                'id' => 'field_5',
                'name' => 'kelengkapan_berkas',
                'label' => 'Kelengkapan Berkas',
                'type' => 'checkbox',
                'required' => true,
                'options' => ['KK', 'KTP'],
            ],
        ]),
    ]);

    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'kelengkapan_berkas' => ['KK', 'SERTIFIKAT'],
        ],
    ])->assertStatus(422);
});

test('isi form api mendukung upload file', function () {
    Storage::fake('public');

    $response = $this->post("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'foto_ktp' => UploadedFile::fake()->image('ktp.png'),
        ],
        'notes' => 'Ada lampiran',
    ]);

    $response->assertStatus(201);

    $request = MppServiceRequest::first();
    expect($request->submitted_form_data['foto_ktp']['value'])->not->toBeNull();
    expect($request->submitted_form_data['foto_ktp']['type'])->toBe('file');
    Storage::disk('public')->assertExists($request->submitted_form_data['foto_ktp']['value']);
});

test('isi form api tidak aktif untuk pelayanan yang tidak aktif', function () {
    $this->service->update(['is_active' => false]);

    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
    ])->assertStatus(404);
});

test('nomor antrian api berbagi urutan dengan pengajuan web', function () {
    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
    ])->assertStatus(201);

    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Ani Setiawati',
            'jenis_kelamin' => 'P',
        ],
    ])->assertStatus(201);

    $numbers = MppServiceRequest::orderBy('id')->pluck('nomor_antrian')->all();

    expect($numbers)->toBe(['A-001', 'A-002']);
});

test('nomor antrian pengajuan berbagi urutan antar layanan satu gerai', function () {
    $this->service->update(['gerai_id' => $this->gerai->id]);

    $secondService = MppService::create([
        'name' => 'Layanan KK',
        'slug' => 'layanan-kk',
        'opd_id' => $this->opd->id,
        'gerai_id' => $this->gerai->id,
        'fields' => [
            [
                'id' => 'field_1',
                'name' => 'nama_kepala_keluarga',
                'label' => 'Nama Kepala Keluarga',
                'type' => 'text',
                'required' => true,
            ],
        ],
        'is_active' => true,
    ]);

    foreach (range(1, 4) as $i) {
        $serviceId = $i % 2 === 0 ? $secondService->id : $this->service->id;

        $this->postJson("/api/v1/services/{$serviceId}/requests", [
            'form_data' => [
                'nik' => '1234567890123456',
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'nama_kepala_keluarga' => 'Budi Santoso',
            ],
        ])->assertStatus(201);
    }

    $numbers = MppServiceRequest::orderBy('id')->pluck('nomor_antrian')->all();

    expect($numbers)->toBe(['A-001', 'A-002', 'A-003', 'A-004']);
});

test('isi form api prioritas menghasilkan nomor AP dan flag prioritas', function () {
    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
    ])->assertStatus(201);

    $response = $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'priority' => true,
        'priority_type' => 'ibu_hamil',
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Ani Setiawati',
            'jenis_kelamin' => 'P',
        ],
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.nomor_antrian', 'AP-002');

    $this->assertDatabaseHas('mpp_service_requests', [
        'nomor_antrian' => 'AP-002',
        'is_priority' => true,
        'priority_type' => 'ibu_hamil',
    ]);

    $this->assertDatabaseHas('mpp_queues', [
        'number' => 'AP-002',
        'is_priority' => true,
        'priority_type' => 'ibu_hamil',
    ]);
});

test('isi form api prioritas wajib menyertakan kategori valid', function () {
    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'priority' => true,
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
    ])->assertStatus(422);

    $this->postJson("/api/v1/services/{$this->service->id}/requests", [
        'priority' => true,
        'priority_type' => 'invalid',
        'form_data' => [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ],
    ])->assertStatus(422);
});

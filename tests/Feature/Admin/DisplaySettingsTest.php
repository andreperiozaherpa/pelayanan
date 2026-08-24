<?php

use App\Models\Role;
use App\Models\User;
use App\Services\FirebaseService;
use App\Services\TtsService;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->superAdmin = User::factory()->create([
        'role_id' => Role::where('slug', 'superadmin')->first()->id,
    ]);

    $this->operator = User::factory()->create([
        'role_id' => Role::where('slug', 'operatordesa')->first()->id,
    ]);

    $this->firebase = mockDisplayFirebase();
    $this->app->instance(FirebaseService::class, $this->firebase);
});

function mockDisplayFirebase(): MockInterface
{
    $mock = Mockery::mock(FirebaseService::class);
    $mock->shouldReceive('isReady')->andReturn(true);
    $mock->shouldReceive('updateDisplaySettings')->zeroOrMoreTimes();
    $mock->shouldReceive('publishCurrentCall')->zeroOrMoreTimes();
    $mock->shouldReceive('setActiveCounter')->zeroOrMoreTimes();

    return $mock;
}

test('super admin dapat membuka halaman pengaturan display', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('display-settings.index'));

    $response->assertStatus(200)
        ->assertSee('Pengaturan Display Caller')
        ->assertSee('Simulasi Test Panggilan')
        ->assertSee('name="tts_rate"', false)
        ->assertSee('name="tts_pitch"', false)
        ->assertSee('name="chime_sound"', false);
});

test('petugas tanpa izin tidak dapat membuka halaman pengaturan display', function () {
    $response = $this->actingAs($this->operator)->get(route('display-settings.index'));

    $response->assertStatus(403);
});

test('super admin dapat menyimpan pengaturan display', function () {
    $payload = [
        'header_title' => 'MPP TUBABA',
        'header_subtitle' => 'Subtitle baru',
        'running_text' => 'Selamat datang.',
        'youtube_url' => 'https://www.youtube.com/watch?v=abc12345678',
        'tts_enabled' => '1',
        'tts_rate' => '1',
        'tts_pitch' => '1',
        'tts_voice' => 'gadis',
        'chime_sound' => 'tubular-bell',
        'color_bg' => '#000000',
        'color_bg_card' => '#111111',
        'color_border' => '#222222',
        'color_text' => '#eeeeee',
        'color_text_muted' => '#aaaaaa',
        'color_accent' => '#ffcc00',
        'color_number' => '#ffcc00',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.update'), $payload);

    $response->assertRedirect();

    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'header_title', 'value' => 'MPP TUBABA']);
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'tts_rate', 'value' => '1']);
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'tts_voice', 'value' => 'gadis']);
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'chime_sound', 'value' => 'tubular-bell']);
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'color_accent', 'value' => '#ffcc00']);

    $this->firebase->shouldHaveReceived('updateDisplaySettings')->once()->with(Mockery::on(function ($payload) {
        return ($payload['tts']['voice'] ?? null) === 'gadis'
            && ($payload['tts']['enabled'] ?? null) === true
            && ($payload['tts']['rate'] ?? null) === 1.0
            && ($payload['chime_sound'] ?? null) === 'tubular-bell';
    }));
});

test('penyimpanan menerima chime_sound none (tanpa bel)', function () {
    $payload = [
        'header_title' => 'MPP TUBABA',
        'tts_enabled' => '1',
        'tts_rate' => '1',
        'tts_pitch' => '1',
        'tts_voice' => 'ardi',
        'chime_sound' => 'none',
        'color_bg' => '#000000',
        'color_bg_card' => '#111111',
        'color_border' => '#222222',
        'color_text' => '#eeeeee',
        'color_text_muted' => '#aaaaaa',
        'color_accent' => '#ffcc00',
        'color_number' => '#ffcc00',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.update'), $payload);

    $response->assertRedirect();
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'chime_sound', 'value' => 'none']);
    $this->firebase->shouldHaveReceived('updateDisplaySettings')->once()->with(Mockery::on(fn ($p) => ($p['chime_sound'] ?? null) === 'none'));
});

test('penyimpanan menerima chime_sound announcement', function () {
    $payload = [
        'header_title' => 'MPP TUBABA',
        'tts_enabled' => '1',
        'tts_rate' => '1',
        'tts_pitch' => '1',
        'tts_voice' => 'google',
        'chime_sound' => 'announcement',
        'color_bg' => '#000000',
        'color_bg_card' => '#111111',
        'color_border' => '#222222',
        'color_text' => '#eeeeee',
        'color_text_muted' => '#aaaaaa',
        'color_accent' => '#ffcc00',
        'color_number' => '#ffcc00',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.update'), $payload);

    $response->assertRedirect();
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'chime_sound', 'value' => 'announcement']);
    $this->firebase->shouldHaveReceived('updateDisplaySettings')->once()->with(Mockery::on(fn ($p) => ($p['chime_sound'] ?? null) === 'announcement'));
});

test('penyimpanan menolak chime_sound tidak dikenal', function () {
    $payload = [
        'header_title' => 'MPP TUBABA',
        'tts_rate' => '1',
        'tts_pitch' => '1',
        'tts_voice' => 'google',
        'chime_sound' => 'my-sfx',
        'color_bg' => '#000000',
        'color_bg_card' => '#111111',
        'color_border' => '#222222',
        'color_text' => '#eeeeee',
        'color_text_muted' => '#aaaaaa',
        'color_accent' => '#ffcc00',
        'color_number' => '#ffcc00',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.update'), $payload);

    $response->assertSessionHasErrors('chime_sound');
});

test('penyimpanan pengaturan gagal bila warna tidak valid', function () {
    $payload = [
        'header_title' => 'MPP TUBABA',
        'tts_rate' => '1',
        'tts_pitch' => '1',
        'tts_voice' => 'google',
        'chime_sound' => 'airport-3tone',
        'color_bg' => 'merah',
        'color_bg_card' => '#111111',
        'color_border' => '#222222',
        'color_text' => '#eeeeee',
        'color_text_muted' => '#aaaaaa',
        'color_accent' => '#ffcc00',
        'color_number' => '#ffcc00',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.update'), $payload);

    $response->assertSessionHasErrors('color_bg');
});

test('super admin dapat mengirim test call simulasi', function () {
    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.simulate'), [
        'queue_number' => 'A-001',
        'gerai_name' => 'Gerai 1',
        'agency' => 'Dinas Kependudukan',
        'service_type' => 'KTP',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('audit_logs', ['action' => 'DISPLAY_TEST_CALL']);
});

test('super admin dapat preview suara TTS dengan voice terpilih', function () {
    $audio = "ID3\x03\x00\x00mp3-fake";

    $this->mock(TtsService::class)->shouldReceive('synthesize')
        ->once()
        ->with(Mockery::on(fn ($text) => str_contains($text, 'D-1')), 'gadis', 1.0, 1.0)
        ->andReturn($audio);

    $response = $this->actingAs($this->superAdmin)->postJson(route('display-settings.preview'), [
        'text' => 'Nomor antrian D-1, silahkan menuju Gerai 11.',
        'voice' => 'gadis',
        'rate' => '1',
        'pitch' => '1',
    ]);

    $response->assertOk()->assertJson([
        'audio' => 'data:audio/mpeg;base64,'.base64_encode($audio),
    ]);
});

test('preview suara menolak voice tidak valid', function () {
    $response = $this->actingAs($this->superAdmin)->postJson(route('display-settings.preview'), [
        'text' => 'Tes',
        'voice' => 'siri',
        'rate' => '1',
        'pitch' => '1',
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors('voice');
});

test('petugas tanpa izin tidak dapat preview suara', function () {
    $response = $this->actingAs($this->operator)->postJson(route('display-settings.preview'), [
        'text' => 'Tes',
        'voice' => 'google',
        'rate' => '1',
        'pitch' => '1',
    ]);

    $response->assertStatus(403);
});

test('preview suara mengembalikan 422 bila sintesis gagal', function () {
    $this->mock(TtsService::class)->shouldReceive('synthesize')->once()->andReturnNull();

    $response = $this->actingAs($this->superAdmin)->postJson(route('display-settings.preview'), [
        'text' => 'Tes',
        'voice' => 'ardi',
        'rate' => '1',
        'pitch' => '1',
    ]);

    $response->assertStatus(422)->assertJson(['error' => 'Gagal menghasilkan suara.']);
});

test('penyimpanan menerima voice google dan tetap tersimpan', function () {
    $payload = [
        'header_title' => 'MPP TUBABA',
        'tts_enabled' => '1',
        'tts_rate' => '1',
        'tts_pitch' => '1',
        'tts_voice' => 'google',
        'chime_sound' => 'airport-3tone',
        'color_bg' => '#000000',
        'color_bg_card' => '#111111',
        'color_border' => '#222222',
        'color_text' => '#eeeeee',
        'color_text_muted' => '#aaaaaa',
        'color_accent' => '#ffcc00',
        'color_number' => '#ffcc00',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.update'), $payload);

    $response->assertRedirect();
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'tts_voice', 'value' => 'google']);
    $this->firebase->shouldHaveReceived('updateDisplaySettings')->once()->with(Mockery::on(fn ($p) => ($p['tts']['voice'] ?? null) === 'google'));
});

test('menyimpan tanpa tts_enabled (saklar off) menyimpan suara nonaktif', function () {
    $payload = [
        'header_title' => 'MPP TUBABA',
        'tts_rate' => '1',
        'tts_pitch' => '1',
        'tts_voice' => 'ardi',
        'chime_sound' => 'airport-3tone',
        'color_bg' => '#000000',
        'color_bg_card' => '#111111',
        'color_border' => '#222222',
        'color_text' => '#eeeeee',
        'color_text_muted' => '#aaaaaa',
        'color_accent' => '#ffcc00',
        'color_number' => '#ffcc00',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('display-settings.update'), $payload);

    $response->assertRedirect();
    $this->assertDatabaseHas('cms_settings', ['group' => 'display', 'key' => 'tts_enabled', 'value' => '0']);
    $this->firebase->shouldHaveReceived('updateDisplaySettings')->once()->with(Mockery::on(fn ($p) => ($p['tts']['enabled'] ?? null) === false));
});

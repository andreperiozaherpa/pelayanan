<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use App\Services\FirebaseService;
use App\Services\TtsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DisplayController extends Controller
{
    private const GROUP = 'display';

    private const DEFAULTS = [
        'header_title' => 'SIBERUGO MPP TUBABA',
        'header_subtitle' => 'Sistem Informasi Bersama Antar Gerai MPP Tulang Bawang Barat',
        'running_text' => 'Selamat Datang di Mal Pelayanan Publik (MPP) Tulang Bawang Barat. Silakan antri sesuai nomor yang dipanggil.',
        'youtube_url' => '',
        'tts_enabled' => true,
        'tts_rate' => '0.9',
        'tts_pitch' => '1',
        'tts_voice' => 'google',
        'chime_sound' => 'airport-3tone',
        'color_bg' => '#0f172a',
        'color_bg_card' => '#1e293b',
        'color_border' => '#334155',
        'color_text' => '#f8fafc',
        'color_text_muted' => '#94a3b8',
        'color_accent' => '#f8ab3a',
        'color_number' => '#f8ab3a',
        'call_number_size' => '180',
        'call_text_size' => '36',
        'header_title_size' => '30', 'header_subtitle_size' => '16', 'clock_size' => '40',
        'call_label_size' => '18', 'call_agency_size' => '26', 'call_service_size' => '20',
        'counter_title_size' => '18', 'counter_text_size' => '16', 'marquee_size' => '20',
    ];

    private const COLOR_KEYS = [
        'color_bg', 'color_bg_card', 'color_border', 'color_text',
        'color_text_muted', 'color_accent', 'color_number',
    ];

    public function __construct(protected FirebaseService $firebase, protected TtsService $tts) {}

    public function preview(Request $request)
    {
        Gate::authorize('mpp.display.settings');

        $validated = $request->validate([
            'text' => ['required', 'string', 'max:500'],
            'voice' => ['required', 'in:google,gadis,ardi'],
            'rate' => ['required', 'numeric', 'min:0.5', 'max:2'],
            'pitch' => ['required', 'numeric', 'min:0.5', 'max:2'],
        ]);

        $audio = $this->tts->synthesize(
            $validated['text'],
            $validated['voice'],
            (float) $validated['rate'],
            (float) $validated['pitch'],
        );

        if ($audio === null) {
            return response()->json(['error' => 'Gagal menghasilkan suara.'], 422);
        }

        return response()->json(['audio' => 'data:audio/mpeg;base64,'.base64_encode($audio)]);
    }

    public function index()
    {
        Gate::authorize('mpp.display.settings');

        $stored = [];
        foreach (CmsSetting::where('group', self::GROUP)->get() as $setting) {
            $stored[$setting->key] = $setting->value;
        }

        return view('services.admin.mpp.pengaturan-display', [
            'settings' => array_merge(self::DEFAULTS, $stored),
            'firebase_ready' => $this->firebase->isReady(),
        ]);
    }

    public function update(Request $request)
    {
        Gate::authorize('mpp.display.settings');

        $rules = [
            'header_title' => ['required', 'string', 'max:120'],
            'header_subtitle' => ['nullable', 'string', 'max:255'],
            'running_text' => ['nullable', 'string', 'max:500'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'tts_enabled' => ['nullable', 'boolean'],
            'tts_rate' => ['required', 'numeric', 'min:0.5', 'max:2'],
            'tts_pitch' => ['required', 'numeric', 'min:0.5', 'max:2'],
            'tts_voice' => ['required', 'in:google,gadis,ardi'],
            'chime_sound' => ['required', 'in:ding-dong-2tone,airport-3tone,tubular-bell,announcement,none'],
            'call_number_size' => ['required', 'integer', 'min:100', 'max:260'],
            'call_text_size' => ['required', 'integer', 'min:20', 'max:56'],
        ];
        foreach (self::COLOR_KEYS as $key) {
            $rules[$key] = ['required', 'regex:/^#[0-9a-fA-F]{6}$/'];
        }
        foreach (['header_title_size' => [18, 56], 'header_subtitle_size' => [10, 32], 'clock_size' => [24, 72], 'call_label_size' => [12, 36], 'call_agency_size' => [14, 44], 'call_service_size' => [12, 32], 'counter_title_size' => [12, 32], 'counter_text_size' => [10, 28], 'marquee_size' => [12, 32]] as $key => [$min, $max]) {
            $rules[$key] = ['required', 'integer', "min:{$min}", "max:{$max}"];
        }

        $validated = $request->validate($rules);

        $payload = [
            'header_title' => $validated['header_title'],
            'header_subtitle' => $validated['header_subtitle'] ?? '',
            'running_text' => $validated['running_text'] ?? '',
            'youtube_url' => $validated['youtube_url'] ?? '',
            'tts' => [
                'enabled' => (bool) ($validated['tts_enabled'] ?? false),
                'rate' => (float) $validated['tts_rate'],
                'pitch' => (float) $validated['tts_pitch'],
                'voice' => $validated['tts_voice'],
            ],
            'chime_sound' => $validated['chime_sound'],
            'font_sizes' => ['call_number' => (int) $validated['call_number_size'], 'call_text' => (int) $validated['call_text_size']],
            'colors' => array_combine(
                array_map(fn (string $k) => str_replace('color_', '', $k), self::COLOR_KEYS),
                array_map(fn (string $k) => $validated[$k], self::COLOR_KEYS),
            ),
        ];
        foreach (['header_title_size', 'header_subtitle_size', 'clock_size', 'call_label_size', 'call_agency_size', 'call_service_size', 'counter_title_size', 'counter_text_size', 'marquee_size'] as $key) {
            $payload['font_sizes'][str_replace('_size', '', $key)] = (int) $validated[$key];
        }

        $flat = [
            'header_title' => $validated['header_title'],
            'header_subtitle' => $validated['header_subtitle'] ?? '',
            'running_text' => $validated['running_text'] ?? '',
            'youtube_url' => $validated['youtube_url'] ?? '',
            'tts_enabled' => ($validated['tts_enabled'] ?? false) ? '1' : '0',
            'tts_rate' => (string) $validated['tts_rate'],
            'tts_pitch' => (string) $validated['tts_pitch'],
            'tts_voice' => $validated['tts_voice'],
            'chime_sound' => $validated['chime_sound'],
            'call_number_size' => (string) $validated['call_number_size'],
            'call_text_size' => (string) $validated['call_text_size'],
        ];
        foreach (self::COLOR_KEYS as $key) {
            $flat[$key] = $validated[$key];
        }
        foreach (['header_title_size', 'header_subtitle_size', 'clock_size', 'call_label_size', 'call_agency_size', 'call_service_size', 'counter_title_size', 'counter_text_size', 'marquee_size'] as $key) {
            $flat[$key] = (string) $validated[$key];
        }

        foreach ($flat as $key => $value) {
            CmsSetting::updateOrCreate(['key' => $key], [
                'group' => self::GROUP,
                'value' => $value,
                'type' => 'string',
            ]);
        }

        $this->firebase->updateDisplaySettings($payload);
        Audit::log('DISPLAY_UPDATE_SETTINGS', 'display_settings', $payload);

        return back()->with('success', 'Pengaturan display diperbarui dan dipublikasikan secara realtime.');
    }

    public function simulate(Request $request)
    {
        Gate::authorize('mpp.display.settings');

        $validated = $request->validate([
            'queue_number' => ['required', 'string', 'max:20'],
            'gerai_name' => ['required', 'string', 'max:120'],
            'agency' => ['nullable', 'string', 'max:255'],
            'service_type' => ['nullable', 'string', 'max:255'],
        ]);

        $call = [
            'queue_number' => $validated['queue_number'],
            'gerai_name' => $validated['gerai_name'],
            'agency' => $validated['agency'],
            'service_type' => $validated['service_type'],
            'timestamp' => now()->timestamp,
        ];

        $this->firebase->publishCurrentCall($call);
        $this->firebase->setActiveCounter('simulasi', [
            'label' => $validated['gerai_name'],
            'number' => $validated['queue_number'],
            'timestamp' => now()->timestamp,
        ]);

        Audit::log('DISPLAY_TEST_CALL', 'display_settings', $call);

        return back()->with('success', 'Test panggilan terkirim: '.$validated['queue_number'].' → '.$validated['gerai_name'].'.');
    }
}

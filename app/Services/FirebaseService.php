<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;

class FirebaseService
{
    private readonly ?Database $database;

    public function __construct()
    {
        $this->database = $this->initialize();
    }

    private function initialize(): ?Database
    {
        $credentialsPath = config('services.firebase.credentials');
        $databaseUri = config('services.firebase.database_url');

        if (! $credentialsPath || ! $databaseUri) {
            return null;
        }

        if (! str_starts_with($credentialsPath, '/')) {
            $credentialsPath = base_path($credentialsPath);
        }

        if (! file_exists($credentialsPath)) {
            return null;
        }

        try {
            $factory = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri($databaseUri);

            return $factory->createDatabase();
        } catch (\Throwable $e) {
            Log::error('Firebase initialization failed', ['exception' => $e]);

            return null;
        }
    }

    public function broadcastQueueEvent(string $event, array $data): bool
    {
        if (! $this->database) {
            return false;
        }

        try {
            $ref = $this->database->getReference("queue-events/{$event}");
            $ref->push([
                'data' => $data,
                'timestamp' => now()->toIso8601String(),
            ]);

            return true;
        } catch (FirebaseException $exception) {
            Log::warning('Firebase queue event publication failed', ['event' => $event, 'exception' => $exception]);

            return false;
        }
    }

    public function syncQueues(array $queues): void
    {
        if (! $this->database) {
            return;
        }

        try {
            $ref = $this->database->getReference('queues');
            $ref->set($queues);
        } catch (FirebaseException $exception) {
            Log::warning('Firebase queue sync failed', ['exception' => $exception]);
        }
    }

    public function isReady(): bool
    {
        return $this->database !== null;
    }

    /**
     * Baca nilai satu node Firebase (null bila belum ada / Firebase nonaktif).
     */
    public function getValue(string $path): ?array
    {
        if (! $this->database) {
            return null;
        }

        try {
            $value = $this->database->getReference($path)->getSnapshot()->getValue();

            return is_array($value) ? $value : null;
        } catch (FirebaseException $exception) {
            Log::warning('Firebase value read failed', ['path' => $path, 'exception' => $exception]);

            return null;
        }
    }

    /**
     * Simpan seluruh pengaturan tampilan display (running text, youtube, header, TTS, warna).
     */
    public function updateDisplaySettings(array $settings): void
    {
        $this->setValue('display_settings', $settings);
    }

    /**
     * Publikasikan panggilan aktif yang sedang ditampilkan di layar TV.
     */
    public function publishCurrentCall(array $call): void
    {
        $this->setValue('current_call', $call);
    }

    /**
     * Tambahkan baris riwayat antrian (Selesai / Tidak Hadir) dan potong ke limit terakhir.
     */
    public function appendRecentHistory(array $entry, int $limit = 20): void
    {
        if (! $this->database) {
            return;
        }

        try {
            $ref = $this->database->getReference('recent_history');
            $history = $ref->getSnapshot()->getValue();

            $history = is_array($history) ? array_values($history) : [];
            array_unshift($history, $entry);
            $history = array_slice($history, 0, $limit);

            $ref->set($history);
        } catch (FirebaseException $exception) {
            Log::warning('Firebase recent history publication failed', ['exception' => $exception]);
        }
    }

    /**
     * Perbarui / hapus satu kartu loket aktif di bawah layar TV.
     * Data null akan menghapus kartu dengan key bersangkutan.
     */
    public function setActiveCounter(string $key, ?array $data): void
    {
        if (! $this->database) {
            return;
        }

        try {
            $ref = $this->database->getReference("active_counters/{$key}");
            $data === null ? $ref->remove() : $ref->set($data);
        } catch (FirebaseException $exception) {
            Log::warning('Firebase active counter publication failed', ['key' => $key, 'exception' => $exception]);
        }
    }

    private function setValue(string $path, array $value): void
    {
        if (! $this->database) {
            return;
        }

        try {
            $this->database->getReference($path)->set($value);
        } catch (FirebaseException $exception) {
            Log::warning('Firebase value publication failed', ['path' => $path, 'exception' => $exception]);
        }
    }
}

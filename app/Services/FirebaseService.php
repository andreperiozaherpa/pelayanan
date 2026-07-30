<?php

namespace App\Services;

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

        try {
            $factory = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri($databaseUri);

            return $factory->createDatabase();
        } catch (\Throwable) {
            return null;
        }
    }

    public function broadcastQueueEvent(string $event, array $data): void
    {
        if (! $this->database) {
            return;
        }

        try {
            $ref = $this->database->getReference("queue-events/{$event}");
            $ref->push([
                'data' => $data,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (FirebaseException) {
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
        } catch (FirebaseException) {
        }
    }
}

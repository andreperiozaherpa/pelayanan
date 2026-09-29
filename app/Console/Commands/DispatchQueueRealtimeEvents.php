<?php

namespace App\Console\Commands;

use App\Models\QueueRealtimeEvent;
use App\Services\FirebaseService;
use Illuminate\Console\Command;

class DispatchQueueRealtimeEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:dispatch-realtime-events {--limit=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim ulang event realtime antrian yang sebelumnya gagal';

    /**
     * Execute the console command.
     */
    public function handle(FirebaseService $firebase): int
    {
        $events = QueueRealtimeEvent::query()
            ->whereNull('dispatched_at')
            ->where('next_attempt_at', '<=', now())
            ->orderBy('id')
            ->limit((int) $this->option('limit'))
            ->get();

        foreach ($events as $event) {
            if ($firebase->broadcastQueueEvent($event->event, $event->payload)) {
                $event->update(['dispatched_at' => now()]);

                continue;
            }

            $attempts = $event->attempts + 1;
            $event->update([
                'attempts' => $attempts,
                'next_attempt_at' => now()->addSeconds(min(3600, 30 * (2 ** min($attempts, 7)))),
            ]);
        }

        return self::SUCCESS;
    }
}

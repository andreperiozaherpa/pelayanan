<?php

namespace App\Services;

use App\Models\Counter;
use App\Models\Gerai;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Models\MppTicketIdempotencyKey;
use App\Models\MppTicketTemplate;
use App\Models\Opd;
use App\Models\Queue;
use App\Models\QueueRealtimeEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QueueService
{
    public function __construct(private readonly FirebaseService $firebase) {}

    /**
     * Ambil nomor antrian untuk sebuah layanan.
     *
     * Nomor otomatis reset ke 1 setiap hari karena dihitung dari jumlah
     * tiket pada tanggal yang sama. Transaksi + row lock mencegah dua kiosk
     * mendapat nomor yang sama secara bersamaan.
     */
    public function takeTicket(int $serviceId, bool $priority = false, ?string $priorityType = null): Queue
    {
        return DB::transaction(
            fn (): Queue => $this->takeTicketLocked($serviceId, $priority, $priorityType),
            attempts: 3,
        );
    }

    /**
     * @return array{0: Queue, 1: bool}
     */
    public function takeTicketIdempotently(int $serviceId, bool $priority, ?string $priorityType, string $key): array
    {
        $payloadHash = hash('sha256', json_encode([
            'service_id' => $serviceId,
            'priority' => $priority,
            'priority_type' => $priorityType,
        ], JSON_THROW_ON_ERROR));

        return DB::transaction(function () use ($serviceId, $priority, $priorityType, $key, $payloadHash): array {
            $created = DB::table('mpp_ticket_idempotency_keys')->insertOrIgnore([
                'operation' => 'ticket',
                'key' => $key,
                'payload_hash' => $payloadHash,
                'service_id' => $serviceId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $idempotencyKey = MppTicketIdempotencyKey::query()
                ->where('operation', 'ticket')
                ->where('key', $key)
                ->lockForUpdate()
                ->firstOrFail();

            if (! hash_equals($idempotencyKey->payload_hash, $payloadHash)) {
                throw ValidationException::withMessages([
                    'idempotency_key' => 'Kunci idempotensi sudah digunakan untuk permintaan yang berbeda.',
                ]);
            }

            if (! $created) {
                return [
                    Queue::query()->whereKey($idempotencyKey->queue_id)->lockForUpdate()->firstOrFail(),
                    true,
                ];
            }

            $ticket = $this->takeTicketLocked($serviceId, $priority, $priorityType);

            $idempotencyKey->update(['queue_id' => $ticket->id]);

            return [$ticket, false];
        }, attempts: 3);
    }

    private function takeTicketLocked(int $serviceId, bool $priority, ?string $priorityType): Queue
    {
        $service = MppService::query()
            ->with('opd.gerais')
            ->whereKey($serviceId)
            ->lockForUpdate()
            ->first();

        if (! $service) {
            throw (new ModelNotFoundException)->setModel(MppService::class, [$serviceId]);
        }

        if (! $service->is_active) {
            throw ValidationException::withMessages(['service_id' => 'Layanan tidak aktif.']);
        }

        $number = $this->generateNomorAntrian($service, $priority);

        return $this->createTicket($service, $number, $priority, $priorityType);
    }

    /**
     * Buat tiket antrian baru berstatus waiting_fo untuk sebuah layanan.
     *
     * Dipakai oleh `POST /tickets` maupun saat pengajuan kiosk
     * (`POST /services/{service}/requests`) sehingga nomor kiosk selalu
     * muncul di antrian Front Office. Panggil dalam transaksi bersama
     * penghasil nomor agar urutan tidak bertabrakan.
     */
    public function createTicket(MppService $service, string $number, bool $priority = false, ?string $priorityType = null): Queue
    {
        $ticket = Queue::create([
            'number' => $number,
            'service_id' => $service->id,
            'mpp_ticket_template_version_id' => MppTicketTemplate::query()->value('active_version_id'),
            'status' => Queue::STATUS_WAITING_FO,
            'is_priority' => $priority,
            'priority_type' => $priority ? $priorityType : null,
        ]);

        $this->broadcast('ticket:new', ['ticket' => $ticket->id, 'number' => $number]);

        $this->firebase->appendRecentHistory([
            'queue_number' => $number,
            'gerai_name' => $this->geraiNameFor($service),
            'status' => 'Menunggu',
            'is_priority' => $priority,
            'timestamp' => $ticket->created_at?->timestamp,
        ]);

        return $ticket;
    }

    private function geraiNameFor(MppService $service): string
    {
        $service->loadMissing('gerai', 'opd.gerais');

        return $service->gerai?->name
            ?? $service->opd?->gerais?->first()?->name
            ?? 'Gerai';
    }

    /**
     * Generate nomor antrian berikutnya untuk sebuah layanan.
     *
     * Nomor dihitung per gerai (lintas semua layanan pada gerai yang sama)
     * sehingga selalu unik dalam satu gerai. Prefix diambil dari kode gerai.
     * Kiosk & web berbagi urutan yang sama. Reset otomatis ke 1 setiap hari.
     * Panggil dalam transaksi; baris gerai di-lock agar dua layanan pada gerai
     * yang sama tidak mendapat nomor yang sama secara bersamaan.
     *
     * Tiket prioritas berbagi urutan yang sama dengan tiket reguler, hanya
     * prefix-nya disisipi huruf "P" (mis. gerai "A" → prioritas "AP").
     */
    public function generateNomorAntrian(MppService $service, bool $priority = false): string
    {
        $service->loadMissing('gerai', 'opd.gerais');

        $gerai = $service->gerai;

        if ($gerai) {
            Gerai::query()->whereKey($gerai->id)->lockForUpdate()->first();

            $serviceIds = MppService::query()->where('gerai_id', $gerai->id)->pluck('id');
            $prefix = explode('-', $gerai->code)[0] ?? null;
        } else {
            $gerai = $service->opd?->gerais?->first();
            if ($gerai) {
                Gerai::query()->whereKey($gerai->id)->lockForUpdate()->first();
            } elseif ($service->opd_id) {
                Opd::query()->whereKey($service->opd_id)->lockForUpdate()->first();
            }

            $serviceIds = $service->opd_id
                ? MppService::query()->where('opd_id', $service->opd_id)->pluck('id')
                : collect([$service->id]);
            $prefix = $gerai ? explode('-', $gerai->code)[0] : null;
        }

        $sequence = max(
            Queue::query()->whereIn('service_id', $serviceIds)->whereDate('created_at', today())->count(),
            MppServiceRequest::query()->whereIn('mpp_service_id', $serviceIds)->whereDate('created_at', today())->count(),
        ) + 1;

        $number = str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);

        if ($priority && $prefix) {
            $prefix .= 'P';
        }

        return $prefix ? $prefix.'-'.$number : $number;
    }

    public function foCall(User $petugas): Queue
    {
        $ticket = DB::transaction(function () use ($petugas) {
            User::query()->whereKey($petugas->id)->lockForUpdate()->firstOrFail();

            $activeTicket = Queue::query()
                ->callingFo()
                ->where('fo_petugas_id', $petugas->id)
                ->lockForUpdate()
                ->first();

            if ($activeTicket) {
                throw ValidationException::withMessages([
                    'queue' => 'Petugas masih melayani antrian lain.',
                ]);
            }

            $ticket = Queue::query()->waitingFo()->priorityFirst()->lockForUpdate()->first();

            if (! $ticket) {
                throw new \RuntimeException('Tidak ada antrian.');
            }

            $ticket->update([
                'status' => Queue::STATUS_CALLING_FO,
                'fo_petugas_id' => $petugas->id,
                'called_at' => now(),
                'fo_called_at' => now(),
                'fo_finished_at' => null,
            ]);

            return $ticket;
        }, attempts: 3);

        $this->broadcast('fo:call', ['ticket' => $ticket->id, 'number' => $ticket->number]);
        $this->broadcastDisplayCall($ticket, 'Front Office');

        $this->publishDisplayCall($ticket, 'Front Office', 'fo');

        return $ticket;
    }

    public function foForward(Queue $ticket, User $petugas, ?string $catatan = null): Queue
    {
        $ticket = DB::transaction(function () use ($ticket, $petugas, $catatan): Queue {
            $ticket = $this->lockedTicket($ticket);
            $this->ensureLockedStatus($ticket, [Queue::STATUS_WAITING_FO, Queue::STATUS_CALLING_FO], 'Tiket tidak dapat dilanjutkan ke gerai.');

            $ticket->update([
                'status' => Queue::STATUS_WAITING_GERAI,
                'counter_id' => null,
                'counter_name' => null,
                'fo_petugas_id' => $petugas->id,
                'notes' => $catatan ?: null,
                'called_at' => null,
                'fo_finished_at' => now(),
            ]);

            return $ticket;
        }, attempts: 3);

        $ticket->loadMissing('service.opd');

        $this->broadcast('fo:forward', [
            'ticket' => $ticket->id,
            'number' => $ticket->number,
            'instansi' => $ticket->service?->opd?->name,
        ]);

        // FO selesai melayani tiket ini — hapus kartu "fo" agar tidak ada
        // nomor tersisa di panel loket saat tidak ada layanan berjalan.
        $this->firebase->setActiveCounter('fo', null);

        return $ticket;
    }

    public function foReject(Queue $ticket, User $petugas, string $alasan): Queue
    {
        $ticket = DB::transaction(function () use ($ticket, $petugas, $alasan): Queue {
            $ticket = $this->lockedTicket($ticket);
            $this->ensureLockedStatus($ticket, [Queue::STATUS_WAITING_FO, Queue::STATUS_CALLING_FO], 'Tiket tidak dapat ditolak.');

            $ticket->update([
                'status' => Queue::STATUS_REJECTED,
                'fo_petugas_id' => $petugas->id,
                'alasan_reject' => $alasan,
                'called_at' => null,
                'fo_finished_at' => now(),
            ]);

            return $ticket;
        }, attempts: 3);

        $this->broadcast('fo:reject', ['ticket' => $ticket->id, 'number' => $ticket->number]);

        $this->firebase->setActiveCounter('fo', null);

        $this->appendHistory($ticket, 'Tidak Hadir', 'Front Office');

        return $ticket;
    }

    public function foRecall(Queue $ticket): Queue
    {
        $ticket = DB::transaction(function () use ($ticket): Queue {
            $ticket = $this->lockedTicket($ticket);
            $this->ensureLockedStatus($ticket, [Queue::STATUS_CALLING_FO], 'Tiket tidak sedang dipanggil di Front Office.');
            $ticket->update(['called_at' => now()]);

            return $ticket;
        }, attempts: 3);

        $this->broadcastDisplayCall($ticket, 'Front Office');

        $this->publishDisplayCall($ticket, 'Front Office', 'fo');

        return $ticket;
    }

    public function foSkip(Queue $ticket): Queue
    {
        $ticket = DB::transaction(function () use ($ticket): Queue {
            $ticket = $this->lockedTicket($ticket);
            $this->ensureLockedStatus($ticket, [Queue::STATUS_CALLING_FO], 'Tiket tidak sedang dipanggil di Front Office.');

            $ticket->update([
                'status' => Queue::STATUS_WAITING_FO,
                'fo_petugas_id' => null,
                'called_at' => null,
                'fo_finished_at' => now(),
                'skipped_at' => now(),
            ]);

            return $ticket;
        }, attempts: 3);

        $this->broadcast('fo:skip', ['ticket' => $ticket->id, 'number' => $ticket->number]);

        $this->firebase->setActiveCounter('fo', null);

        return $ticket;
    }

    public function geraiCall(User $petugas): Queue
    {
        [$ticket, $counter] = DB::transaction(function () use ($petugas) {
            $counterId = $petugas->activeCounter()?->id;

            if (! $counterId) {
                throw new \RuntimeException('Petugas belum terdaftar pada gerai/loket mana pun.');
            }

            $counter = Counter::query()
                ->with('gerai')
                ->whereKey($counterId)
                ->lockForUpdate()
                ->firstOrFail();

            $activeTicket = Queue::query()
                ->callingGerai($counter->id)
                ->lockForUpdate()
                ->first();

            if ($activeTicket) {
                throw ValidationException::withMessages([
                    'queue' => 'Loket masih melayani antrian lain.',
                ]);
            }

            $ticket = Queue::query()
                ->waitingGerai($counter->gerai?->opd_id)
                ->priorityFirst()
                ->lockForUpdate()
                ->first();

            if (! $ticket) {
                throw new \RuntimeException('Tidak ada antrian.');
            }

            $ticket->update([
                'status' => Queue::STATUS_CALLING_GERAI,
                'counter_id' => $counter->id,
                'counter_name' => $counter->name,
                'gerai_petugas_id' => $petugas->id,
                'called_at' => now(),
                'gerai_called_at' => now(),
            ]);

            return [$ticket, $counter];
        }, attempts: 3);

        $this->broadcast('gerai:call', ['ticket' => $ticket->id, 'number' => $ticket->number, 'gerai' => $counter->name]);
        $this->broadcastDisplayCall($ticket, $counter->name);

        $this->publishDisplayCall($ticket, $counter->name, 'gerai-'.$counter->id);

        return $ticket;
    }

    public function geraiComplete(Queue $ticket, User $petugas, ?string $catatan = null): Queue
    {
        $ticket = DB::transaction(function () use ($ticket, $petugas, $catatan): Queue {
            $ticket = $this->lockedTicket($ticket);
            $this->ensureLockedStatus($ticket, [Queue::STATUS_CALLING_GERAI], 'Tiket tidak sedang dilayani di gerai.');

            $ticket->update([
                'status' => Queue::STATUS_DONE,
                'gerai_petugas_id' => $petugas->id,
                'notes' => $catatan ?: null,
                'done_at' => now(),
            ]);

            return $ticket;
        }, attempts: 3);

        $this->broadcast('gerai:complete', ['ticket' => $ticket->id, 'number' => $ticket->number]);

        $counterKey = $ticket->counter_id ? 'gerai-'.$ticket->counter_id : null;
        if ($counterKey) {
            $this->firebase->setActiveCounter($counterKey, null);
        }
        $this->appendHistory($ticket, 'Selesai', $ticket->counter_name);

        return $ticket;
    }

    /**
     * Panggil ulang nomor yang sedang dilayani gerai karena pemohon belum hadir.
     */
    public function geraiRecall(Queue $ticket): Queue
    {
        $ticket = DB::transaction(function () use ($ticket): Queue {
            $ticket = $this->lockedTicket($ticket);
            $this->ensureLockedStatus($ticket, [Queue::STATUS_CALLING_GERAI], 'Tiket tidak sedang dilayani di gerai.');

            $ticket->update([
                'called_at' => now(),
                'gerai_called_at' => now(),
            ]);

            return $ticket;
        }, attempts: 3);

        $this->broadcast('gerai:call', ['ticket' => $ticket->id, 'number' => $ticket->number, 'gerai' => $ticket->counter_name]);
        $this->broadcastDisplayCall($ticket, $ticket->counter_name);

        $this->publishDisplayCall($ticket, $ticket->counter_name, 'gerai-'.$ticket->counter_id);

        return $ticket;
    }

    private function lockedTicket(Queue $ticket): Queue
    {
        return Queue::query()->whereKey($ticket->id)->lockForUpdate()->firstOrFail();
    }

    /**
     * @param  array<int, string>  $statuses
     */
    private function ensureLockedStatus(Queue $ticket, array $statuses, string $message): void
    {
        if (! in_array($ticket->status, $statuses, true)) {
            throw ValidationException::withMessages(['ticket_id' => $message]);
        }
    }

    private function broadcastDisplayCall(Queue $ticket, string $tujuan): void
    {
        $this->broadcast('display:call', [
            'nomor' => $ticket->number,
            'tujuan' => $tujuan,
            'is_priority' => (bool) $ticket->is_priority,
            'priority_type' => $ticket->is_priority ? $ticket->priority_type : null,
        ]);
    }

    private function publishDisplayCall(Queue $ticket, string $geraiName, string $counterKey): void
    {
        $ticket->loadMissing('service.opd');

        $this->firebase->publishCurrentCall([
            'queue_number' => $ticket->number,
            'gerai_name' => $geraiName,
            'agency' => $ticket->service?->opd?->name,
            'service_type' => $ticket->service?->name,
            'is_priority' => (bool) $ticket->is_priority,
            'priority_type' => $ticket->is_priority ? $ticket->priority_type : null,
            'timestamp' => now()->timestamp,
        ]);

        $this->firebase->setActiveCounter($counterKey, [
            'label' => $geraiName,
            'number' => $ticket->number,
            'is_priority' => (bool) $ticket->is_priority,
            'timestamp' => now()->timestamp,
        ]);
    }

    private function appendHistory(Queue $ticket, string $status, ?string $geraiName): void
    {
        $this->firebase->appendRecentHistory([
            'queue_number' => $ticket->number,
            'gerai_name' => $geraiName,
            'status' => $status,
            'is_priority' => (bool) $ticket->is_priority,
            'timestamp' => $ticket->created_at?->timestamp,
        ]);
    }

    private function broadcast(string $event, array $data): void
    {
        if (! $this->firebase->isReady()) {
            return;
        }

        if (! $this->firebase->broadcastQueueEvent($event, $data)) {
            QueueRealtimeEvent::create([
                'event' => $event,
                'payload' => $data,
                'next_attempt_at' => now()->addMinute(),
            ]);
        }
    }
}

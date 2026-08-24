<?php

namespace App\Services;

use App\Models\Gerai;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Models\Opd;
use App\Models\Queue;
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
    public function takeTicket(int $serviceId): Queue
    {
        return DB::transaction(function () use ($serviceId) {
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

            $number = $this->generateNomorAntrian($service);

            return $this->createTicket($service, $number);
        });
    }

    /**
     * Buat tiket antrian baru berstatus waiting_fo untuk sebuah layanan.
     *
     * Dipakai oleh `POST /tickets` maupun saat pengajuan kiosk
     * (`POST /services/{service}/requests`) sehingga nomor kiosk selalu
     * muncul di antrian Front Office. Panggil dalam transaksi bersama
     * penghasil nomor agar urutan tidak bertabrakan.
     */
    public function createTicket(MppService $service, string $number): Queue
    {
        $ticket = Queue::create([
            'number' => $number,
            'service_id' => $service->id,
            'status' => Queue::STATUS_WAITING_FO,
        ]);

        $this->broadcast('ticket:new', ['ticket' => $ticket->id, 'number' => $number]);

        $this->firebase->appendRecentHistory([
            'queue_number' => $number,
            'gerai_name' => $this->geraiNameFor($service),
            'status' => 'Menunggu',
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
     */
    public function generateNomorAntrian(MppService $service): string
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

        return $prefix ? $prefix.'-'.$number : $number;
    }

    public function foCall(User $petugas): Queue
    {
        $ticket = Queue::query()->waitingFo()->oldest('created_at')->first();

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

        $this->broadcast('fo:call', ['ticket' => $ticket->id, 'number' => $ticket->number]);
        $this->broadcast('display:call', ['nomor' => $ticket->number, 'tujuan' => 'Front Office']);

        $this->publishDisplayCall($ticket, 'Front Office', 'fo');

        return $ticket;
    }

    public function foForward(Queue $ticket, User $petugas, ?string $catatan = null): Queue
    {
        $ticket->loadMissing('service.opd');

        $ticket->update([
            'status' => Queue::STATUS_WAITING_GERAI,
            'counter_id' => null,
            'counter_name' => null,
            'fo_petugas_id' => $petugas->id,
            'notes' => $catatan ?: null,
            'called_at' => null,
            'fo_finished_at' => now(),
        ]);

        $this->broadcast('fo:forward', [
            'ticket' => $ticket->id,
            'number' => $ticket->number,
            'instansi' => $ticket->service?->opd?->name,
        ]);

        return $ticket;
    }

    public function foReject(Queue $ticket, User $petugas, string $alasan): Queue
    {
        $ticket->update([
            'status' => Queue::STATUS_REJECTED,
            'fo_petugas_id' => $petugas->id,
            'alasan_reject' => $alasan,
            'called_at' => null,
            'fo_finished_at' => now(),
        ]);

        $this->broadcast('fo:reject', ['ticket' => $ticket->id, 'number' => $ticket->number]);

        $this->appendHistory($ticket, 'Tidak Hadir', 'Front Office');

        return $ticket;
    }

    public function foRecall(Queue $ticket): Queue
    {
        $ticket->update(['called_at' => now()]);

        $this->broadcast('display:call', ['nomor' => $ticket->number, 'tujuan' => 'Front Office']);

        $this->publishDisplayCall($ticket, 'Front Office', 'fo');

        return $ticket;
    }

    public function foSkip(Queue $ticket): Queue
    {
        $ticket->update([
            'status' => Queue::STATUS_WAITING_FO,
            'fo_petugas_id' => null,
            'called_at' => null,
            'fo_finished_at' => now(),
            'skipped_at' => now(),
        ]);

        $this->broadcast('fo:skip', ['ticket' => $ticket->id, 'number' => $ticket->number]);

        $this->firebase->setActiveCounter('fo', null);

        return $ticket;
    }

    public function geraiCall(User $petugas): Queue
    {
        $counter = $petugas->activeCounter();

        if (! $counter) {
            throw new \RuntimeException('Petugas belum terdaftar pada gerai/loket mana pun.');
        }

        $ticket = Queue::query()
            ->waitingGerai($counter->gerai?->opd_id)
            ->oldest('created_at')
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

        $this->broadcast('gerai:call', ['ticket' => $ticket->id, 'number' => $ticket->number, 'gerai' => $counter->name]);
        $this->broadcast('display:call', ['nomor' => $ticket->number, 'tujuan' => $counter->name]);

        $this->publishDisplayCall($ticket, $counter->name, 'gerai-'.$counter->id);

        return $ticket;
    }

    public function geraiComplete(Queue $ticket, User $petugas, ?string $catatan = null): Queue
    {
        $ticket->update([
            'status' => Queue::STATUS_DONE,
            'gerai_petugas_id' => $petugas->id,
            'notes' => $catatan ?: null,
            'done_at' => now(),
        ]);

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
        $ticket->update([
            'called_at' => now(),
            'gerai_called_at' => now(),
        ]);

        $this->broadcast('gerai:call', ['ticket' => $ticket->id, 'number' => $ticket->number, 'gerai' => $ticket->counter_name]);
        $this->broadcast('display:call', ['nomor' => $ticket->number, 'tujuan' => $ticket->counter_name]);

        $this->publishDisplayCall($ticket, $ticket->counter_name, 'gerai-'.$ticket->counter_id);

        return $ticket;
    }

    private function publishDisplayCall(Queue $ticket, string $geraiName, string $counterKey): void
    {
        $ticket->loadMissing('service.opd');

        $this->firebase->publishCurrentCall([
            'queue_number' => $ticket->number,
            'gerai_name' => $geraiName,
            'agency' => $ticket->service?->opd?->name,
            'service_type' => $ticket->service?->name,
            'timestamp' => now()->timestamp,
        ]);

        $this->firebase->setActiveCounter($counterKey, [
            'label' => $geraiName,
            'number' => $ticket->number,
            'timestamp' => now()->timestamp,
        ]);
    }

    private function appendHistory(Queue $ticket, string $status, ?string $geraiName): void
    {
        $this->firebase->appendRecentHistory([
            'queue_number' => $ticket->number,
            'gerai_name' => $geraiName,
            'status' => $status,
            'timestamp' => $ticket->created_at?->timestamp,
        ]);
    }

    private function broadcast(string $event, array $data): void
    {
        $this->firebase->broadcastQueueEvent($event, $data);
    }
}

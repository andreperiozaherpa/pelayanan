<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\JsonResponse;

class DisplayController extends Controller
{
    /**
     * Riwayat antrian hari ini untuk layar TV display.
     *
     * Berisi seluruh tiket hari ini (termasuk yang masih menunggu) dengan
     * status tampilan saat ini. Format baris mengikuti `recent_history`
     * Firebase (queue_number, gerai_name, status, timestamp) sehingga display
     * dapat memuat data awal dari sini lalu meneruskan update realtime lewat
     * Firebase.
     */
    public function history(): JsonResponse
    {
        $tickets = Queue::query()
            ->with(['service.gerai', 'service.opd.gerais'])
            ->whereDate('created_at', today())
            ->latest('created_at')
            ->limit(20)
            ->get();

        $history = $tickets
            ->map(function (Queue $ticket): array {
                return [
                    'queue_number' => $ticket->number,
                    'gerai_name' => $this->geraiName($ticket),
                    'status' => $this->statusLabel($ticket->status),
                    'timestamp' => $ticket->created_at?->timestamp,
                ];
            })
            ->filter(fn (array $entry): bool => $entry['timestamp'] !== null)
            ->sortByDesc('timestamp')
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            Queue::STATUS_DONE => 'Selesai',
            Queue::STATUS_REJECTED => 'Tidak Hadir',
            Queue::STATUS_CALLING_FO, Queue::STATUS_CALLING_GERAI => 'Dipanggil',
            default => 'Menunggu',
        };
    }

    private function geraiName(Queue $ticket): string
    {
        if ($ticket->status === Queue::STATUS_REJECTED) {
            return 'Front Office';
        }

        if ($ticket->status === Queue::STATUS_DONE || $ticket->status === Queue::STATUS_CALLING_GERAI) {
            return $ticket->counter_name
                ?: $ticket->service?->gerai?->name
                ?? $ticket->service?->opd?->gerais?->first()?->name
                ?? 'Gerai';
        }

        return $ticket->service?->gerai?->name
            ?? $ticket->service?->opd?->gerais?->first()?->name
            ?? 'Gerai';
    }
}

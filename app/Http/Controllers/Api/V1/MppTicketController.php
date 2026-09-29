<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\QueueTicketResource;
use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MppTicketController extends Controller
{
    public function __construct(private readonly QueueService $queueService) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer'],
            'priority' => ['sometimes', 'boolean'],
            'priority_type' => ['nullable', 'required_if:priority,true', 'string', 'in:'.implode(',', array_keys(Queue::PRIORITY_TYPES))],
        ]);

        $idempotencyKey = $this->idempotencyKey($request);

        [$ticket, $replayed] = $idempotencyKey
            ? $this->queueService->takeTicketIdempotently(
                (int) $validated['service_id'],
                (bool) ($validated['priority'] ?? false),
                $validated['priority_type'] ?? null,
                $idempotencyKey,
            )
            : [$this->queueService->takeTicket(
                (int) $validated['service_id'],
                (bool) ($validated['priority'] ?? false),
                $validated['priority_type'] ?? null
            ), false];

        return response()->json([
            'success' => true,
            'data' => new QueueTicketResource(
                $ticket->load(['service.opd.gerais', 'counter'])
            ),
        ])->header('Idempotency-Replayed', $replayed ? 'true' : 'false');
    }

    private function idempotencyKey(Request $request): ?string
    {
        $key = $request->header('Idempotency-Key');

        if ($key === null) {
            return null;
        }

        $key = trim($key);

        if ($key === '' || strlen($key) > 128 || ! preg_match('/^[\x21-\x7E]+$/', $key)) {
            throw ValidationException::withMessages([
                'idempotency_key' => 'Idempotency-Key harus berupa teks ASCII sepanjang 1 sampai 128 karakter.',
            ]);
        }

        return $key;
    }
}

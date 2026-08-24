<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\QueueTicketResource;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MppTicketController extends Controller
{
    public function __construct(private readonly QueueService $queueService) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer'],
        ]);

        $ticket = $this->queueService->takeTicket((int) $validated['service_id']);

        return response()->json([
            'success' => true,
            'data' => new QueueTicketResource(
                $ticket->load(['service.opd.gerais', 'counter'])
            ),
        ]);
    }
}

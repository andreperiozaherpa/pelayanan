<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\QueueTicketResource;
use App\Models\Counter;
use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MppQueueOperationController extends Controller
{
    public function __construct(private readonly QueueService $queueService) {}

    public function foWaiting(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['petugasfrontoffice', 'superadmin']);

        $tickets = Queue::query()
            ->with(['service.opd.gerais', 'counter'])
            ->waitingFo()
            ->oldest('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => QueueTicketResource::collection($tickets),
        ]);
    }

    public function foCalling(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['petugasfrontoffice', 'superadmin']);

        $ticket = Queue::query()
            ->with(['service.opd.gerais', 'counter'])
            ->callingFo()
            ->latest('called_at')
            ->first();

        return $this->ticketResponse($ticket);
    }

    public function foCall(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['petugasfrontoffice', 'superadmin']);

        $ticket = $this->queueService->foCall($request->user());

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function foForward(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['petugasfrontoffice', 'superadmin']);

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $ticket = $this->loadTicket((int) $validated['ticket_id']);

        $this->ensureFoForwardable($ticket);

        $ticket = $this->queueService->foForward(
            $ticket,
            $request->user(),
            $validated['catatan'] ?? null
        );

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function foReject(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['petugasfrontoffice', 'superadmin']);

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer'],
            'alasan' => ['required', 'string', 'max:500'],
        ]);

        $ticket = $this->loadTicket((int) $validated['ticket_id']);

        $this->ensureFoForwardable($ticket);

        $ticket = $this->queueService->foReject($ticket, $request->user(), $validated['alasan']);

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function foRecall(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['petugasfrontoffice', 'superadmin']);

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer'],
        ]);

        $ticket = $this->loadTicket((int) $validated['ticket_id']);

        $this->ensureCallingFo($ticket);

        $ticket = $this->queueService->foRecall($ticket);

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function foSkip(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['petugasfrontoffice', 'superadmin']);

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer'],
        ]);

        $ticket = $this->loadTicket((int) $validated['ticket_id']);

        $this->ensureCallingFo($ticket);

        $ticket = $this->queueService->foSkip($ticket);

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function geraiWaiting(Request $request, Counter $gerai): JsonResponse
    {
        $this->authorizeQueueRole($request, ['gerai', 'superadmin']);

        $tickets = Queue::query()
            ->with(['service.opd.gerais', 'counter'])
            ->waitingGerai($gerai->gerai?->opd_id)
            ->oldest('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => QueueTicketResource::collection($tickets),
        ]);
    }

    public function geraiCalling(Request $request, Counter $gerai): JsonResponse
    {
        $this->authorizeQueueRole($request, ['gerai', 'superadmin']);

        $ticket = Queue::query()
            ->with(['service.opd.gerais', 'counter'])
            ->callingGerai($gerai->id)
            ->latest('called_at')
            ->first();

        return $this->ticketResponse($ticket);
    }

    public function geraiCall(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['gerai', 'superadmin']);

        $ticket = $this->queueService->geraiCall($request->user());

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function geraiComplete(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['gerai', 'superadmin']);

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $ticket = $this->loadTicket((int) $validated['ticket_id']);

        if ($ticket->status !== Queue::STATUS_CALLING_GERAI) {
            return response()->json([
                'success' => false,
                'error' => 'Tiket tidak sedang dilayani di gerai.',
            ], 422);
        }

        $activeCounter = $request->user()->activeCounter();

        if ($activeCounter && $ticket->counter_id !== $activeCounter->id) {
            return response()->json([
                'success' => false,
                'error' => 'Tiket tidak dilayani pada loket Anda.',
            ], 422);
        }

        $ticket = $this->queueService->geraiComplete($ticket, $request->user(), $validated['catatan'] ?? null);

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function geraiRecall(Request $request): JsonResponse
    {
        $this->authorizeQueueRole($request, ['gerai', 'superadmin']);

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer'],
        ]);

        $ticket = $this->loadTicket((int) $validated['ticket_id']);

        if ($ticket->status !== Queue::STATUS_CALLING_GERAI) {
            return response()->json([
                'success' => false,
                'error' => 'Tiket tidak sedang dilayani di gerai.',
            ], 422);
        }

        $activeCounter = $request->user()->activeCounter();

        if ($activeCounter && $ticket->counter_id !== $activeCounter->id) {
            return response()->json([
                'success' => false,
                'error' => 'Tiket tidak dilayani pada loket Anda.',
            ], 422);
        }

        $ticket = $this->queueService->geraiRecall($ticket);

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    private function loadTicket(int $id): Queue
    {
        return Queue::query()->with(['service.opd.gerais', 'counter'])->findOrFail($id);
    }

    private function ensureCallingFo(Queue $ticket): void
    {
        if ($ticket->status !== Queue::STATUS_CALLING_FO) {
            abort(422, 'Tiket tidak sedang dipanggil di Front Office.');
        }
    }

    private function ensureFoForwardable(Queue $ticket): void
    {
        if (! in_array($ticket->status, [Queue::STATUS_CALLING_FO, Queue::STATUS_WAITING_FO], true)) {
            abort(422, 'Tiket tidak dapat dilanjutkan ke gerai.');
        }
    }

    private function ticketResponse(?Queue $ticket): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $ticket ? new QueueTicketResource($ticket) : null,
        ]);
    }

    private function authorizeQueueRole(Request $request, array $roles): void
    {
        $slug = $request->user()?->role?->slug;

        if (! $slug || ! in_array($slug, $roles, true)) {
            abort(403, 'Akses ditolak untuk role ini.');
        }
    }
}

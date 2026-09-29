<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Audit;
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
            ->priorityFirst()
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
            ->where('fo_petugas_id', $request->user()->id)
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
        $this->authorizeFoTicket($request, $ticket);

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
        $this->authorizeFoTicket($request, $ticket);

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
        $this->authorizeFoTicket($request, $ticket);

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
        $this->authorizeFoTicket($request, $ticket);

        $ticket = $this->queueService->foSkip($ticket);

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    public function geraiWaiting(Request $request, Counter $gerai): JsonResponse
    {
        $this->authorizeQueueRole($request, ['gerai', 'superadmin']);
        $this->authorizeCounter($request, $gerai);

        $tickets = Queue::query()
            ->with(['service.opd.gerais', 'counter'])
            ->waitingGerai($gerai->gerai?->opd_id)
            ->priorityFirst()
            ->get();

        return response()->json([
            'success' => true,
            'data' => QueueTicketResource::collection($tickets),
        ]);
    }

    public function geraiCalling(Request $request, Counter $gerai): JsonResponse
    {
        $this->authorizeQueueRole($request, ['gerai', 'superadmin']);
        $this->authorizeCounter($request, $gerai);

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
        $this->requireActiveCounter($request);

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

        $this->authorizeGeraiTicket($request, $ticket);

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

        $this->authorizeGeraiTicket($request, $ticket);

        $ticket = $this->queueService->geraiRecall($ticket);

        return $this->ticketResponse($ticket->load(['service.opd.gerais', 'counter']));
    }

    private function loadTicket(int $id): Queue
    {
        return Queue::query()->with(['service.opd.gerais', 'counter'])->findOrFail($id);
    }

    private function authorizeFoTicket(Request $request, Queue $ticket): void
    {
        if ($ticket->fo_petugas_id === $request->user()->id
            || ($ticket->status === Queue::STATUS_WAITING_FO && $ticket->fo_petugas_id === null)) {
            return;
        }

        $this->authorizeSupervisorOverride($request, $ticket);
    }

    private function requireActiveCounter(Request $request): Counter
    {
        $counter = $request->user()->activeCounter();

        if (! $counter) {
            abort(response()->json(['success' => false, 'error' => 'Petugas belum terdaftar pada loket aktif.'], 403));
        }

        return $counter;
    }

    private function authorizeCounter(Request $request, Counter $counter): void
    {
        if (! $counter->is_active || ! $counter->gerai?->is_active || ! $counter->gerai?->opd_id) {
            abort(response()->json(['success' => false, 'error' => 'Loket tidak aktif atau belum terhubung ke instansi.'], 403));
        }

        if ($request->user()->isSuperAdmin()) {
            Audit::log('queue_supervisor_read', $counter, ['operation' => $request->path()]);

            return;
        }

        if ($this->requireActiveCounter($request)->id !== $counter->id) {
            abort(response()->json(['success' => false, 'error' => 'Akses ditolak untuk loket ini.'], 403));
        }
    }

    private function authorizeGeraiTicket(Request $request, Queue $ticket): void
    {
        if ($request->user()->isSuperAdmin()) {
            $this->authorizeSupervisorOverride($request, $ticket);

            return;
        }

        if ($ticket->counter_id !== $this->requireActiveCounter($request)->id) {
            abort(response()->json(['success' => false, 'error' => 'Tiket tidak dilayani pada loket Anda.'], 403));
        }
    }

    private function authorizeSupervisorOverride(Request $request, Queue $ticket): void
    {
        if (! $request->user()->isSuperAdmin()) {
            abort(response()->json(['success' => false, 'error' => 'Tiket tidak ditugaskan kepada Anda.'], 403));
        }

        Audit::log('queue_supervisor_override', $ticket, [
            'operation' => $request->path(),
            'fo_petugas_id' => $ticket->fo_petugas_id,
            'counter_id' => $ticket->counter_id,
        ]);
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

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MppPublicRegistrationController extends Controller
{
    public function claim(Request $request, QueueService $queues): JsonResponse
    {
        $code = strtoupper(trim((string) $request->validate(['barcode' => ['required', 'string', 'max:64']])['barcode']));
        $ticket = $queues->claimPublicRegistration($code);
        $ticket->loadMissing('service.opd.gerais');
        $service = $ticket->service;
        return response()->json(['success' => true, 'data' => [
            'queue_id' => $ticket->id,
            'nomor_antrian' => $ticket->number,
            'service_name' => $service?->name,
            'receipt' => [
                'queue_id' => $ticket->id, 'nomor_antrian' => $ticket->number,
                'service_name' => $service?->name, 'instansi_name' => $service?->opd?->name,
                'gerai_name' => $service?->gerai?->name ?? $service?->opd?->gerais?->first()?->name,
                'issued_at' => $ticket->created_at, 'template_version_id' => $ticket->mpp_ticket_template_version_id,
                'remaining_queue' => 0,
            ],
        ]]);
    }
}

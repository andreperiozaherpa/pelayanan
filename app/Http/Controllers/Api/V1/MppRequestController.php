<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Models\MppTicketIdempotencyKey;
use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MppRequestController extends Controller
{
    public function __construct(private readonly QueueService $queueService) {}

    /**
     * Kirim permohonan untuk sebuah pelayanan MPP via API (kiosk).
     *
     * Body: form_data[<nama field>] = nilai, dan notes (opsional).
     * Field bertipe file menerima upload multipart maupun path string yang
     * sudah ada (mis. hasil dropzone). Nomor antrian dihasilkan bersama alur
     * web (mpp-requests) sehingga urutannya berbagi dan tidak bertabrakan.
     *
     * Selain menyimpan pengajuan (mpp_service_requests), endpoint ini juga
     * membuat tiket antrian (mpp_queues) berstatus waiting_fo sehingga nomor
     * kiosk langsung muncul di daftar Front Office.
     */
    public function store(Request $request, MppService $service): JsonResponse
    {
        if (! $service->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'Pelayanan tidak aktif.',
            ], 404);
        }

        $rules = [
            'notes' => 'nullable|string|max:500',
            'priority' => 'sometimes|boolean',
            'priority_type' => 'nullable|required_if:priority,true|string|in:'.implode(',', array_keys(Queue::PRIORITY_TYPES)),
        ];

        foreach ($service->fields as $field) {
            $fieldName = $field['name'];
            $fieldRules = [];

            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field['type'] === 'number') {
                $fieldRules[] = 'numeric';
            } elseif ($field['type'] === 'file') {
                if ($request->hasFile("form_data.{$fieldName}")) {
                    $fieldRules[] = 'file';
                    $fieldRules[] = 'max:5120';
                } else {
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';
                }
            } elseif ($field['type'] === 'select') {
                $fieldRules[] = 'in:'.implode(',', $field['options'] ?? []);
            } elseif ($field['type'] === 'checkbox') {
                $fieldRules[] = 'array';
                if (! empty($field['options'])) {
                    $fieldRules[] = 'in:'.implode(',', $field['options']);
                }
            } else {
                $fieldRules[] = 'string';
            }

            $rules['form_data.'.$fieldName] = $fieldRules;
        }

        $validated = $request->validate($rules);

        $isPriority = (bool) ($validated['priority'] ?? false);
        $priorityType = $isPriority ? ($validated['priority_type'] ?? null) : null;
        $idempotencyKey = $this->idempotencyKey($request);
        $payloadHash = $idempotencyKey
            ? $this->payloadHash($request, $service, $validated, $isPriority, $priorityType)
            : null;

        $submittedData = [];
        $formData = $request->input('form_data', []);

        foreach ($service->fields as $field) {
            $fieldName = $field['name'];

            if ($field['type'] === 'file') {
                if ($request->hasFile("form_data.{$fieldName}")) {
                    $file = $request->file("form_data.{$fieldName}");
                    $path = $file->store('mpp-attachments', 'public');
                    $submittedData[$fieldName] = [
                        'label' => $field['label'],
                        'type' => 'file',
                        'value' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                } elseif (is_string($request->input("form_data.{$fieldName}")) && ! empty($request->input("form_data.{$fieldName}"))) {
                    $path = $request->input("form_data.{$fieldName}");
                    $submittedData[$fieldName] = [
                        'label' => $field['label'],
                        'type' => 'file',
                        'value' => $path,
                        'original_name' => basename($path),
                    ];
                } else {
                    $submittedData[$fieldName] = [
                        'label' => $field['label'],
                        'type' => 'file',
                        'value' => null,
                    ];
                }
            } else {
                $submittedData[$fieldName] = [
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'value' => $formData[$fieldName] ?? null,
                ];
            }
        }

        [$mppServiceRequest, $ticket, $replayed] = DB::transaction(function () use ($request, $service, $submittedData, $isPriority, $priorityType, $idempotencyKey, $payloadHash) {
            $service = MppService::query()->whereKey($service->id)->lockForUpdate()->first();

            $record = null;
            if ($idempotencyKey) {
                $created = DB::table('mpp_ticket_idempotency_keys')->insertOrIgnore([
                    'operation' => 'service_request',
                    'key' => $idempotencyKey,
                    'payload_hash' => $payloadHash,
                    'service_id' => $service->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $record = MppTicketIdempotencyKey::query()
                    ->where('operation', 'service_request')
                    ->where('key', $idempotencyKey)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! hash_equals($record->payload_hash, $payloadHash)) {
                    throw ValidationException::withMessages([
                        'idempotency_key' => 'Kunci idempotensi sudah digunakan untuk permintaan yang berbeda.',
                    ]);
                }

                if (! $created) {
                    return [
                        MppServiceRequest::query()->whereKey($record->mpp_service_request_id)->lockForUpdate()->firstOrFail(),
                        Queue::query()->whereKey($record->queue_id)->lockForUpdate()->firstOrFail(),
                        true,
                    ];
                }
            }

            $nomorAntrian = $this->queueService->generateNomorAntrian($service, $isPriority);

            $mppServiceRequest = MppServiceRequest::create([
                'mpp_service_id' => $service->id,
                'nomor_antrian' => $nomorAntrian,
                'front_office_user_id' => $request->user()?->id,
                'notes' => $request->input('notes'),
                'submitted_form_data' => $submittedData,
                'status' => MppServiceRequest::STATUS_PENDING,
                'is_priority' => $isPriority,
                'priority_type' => $priorityType,
            ]);

            $ticket = $this->queueService->createTicket($service, $nomorAntrian, $isPriority, $priorityType);

            $mppServiceRequest->forceFill(['queue_id' => $ticket->id])->save();

            $record?->update([
                'queue_id' => $ticket->id,
                'mpp_service_request_id' => $mppServiceRequest->id,
            ]);

            return [$mppServiceRequest, $ticket, false];
        }, attempts: 3);

        if (! $replayed) {
            Audit::log('SUBMIT_MPP_SERVICE_REQUEST_API', $mppServiceRequest, $mppServiceRequest->toArray());
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $mppServiceRequest->id,
                'queue_id' => $ticket->id,
                'nomor_antrian' => $ticket->number,
                'status' => $ticket->status,
                'mpp_service_id' => $mppServiceRequest->mpp_service_id,
                'receipt' => $this->receiptSnapshot($ticket),
            ],
        ], $replayed ? 200 : 201)->header('Idempotency-Replayed', $replayed ? 'true' : 'false');
    }

    /**
     * @return array<string, mixed>
     */
    private function receiptSnapshot(Queue $ticket): array
    {
        $ticket->loadMissing('service.gerai', 'service.opd.gerais');
        $service = $ticket->service;
        $gerai = $service?->gerai ?? $service?->opd?->gerais?->first();
        $remainingQueue = Queue::query()
            ->where('service_id', $ticket->service_id)
            ->whereKeyNot($ticket->id)
            ->whereIn('status', [Queue::STATUS_WAITING_FO, Queue::STATUS_CALLING_FO, Queue::STATUS_WAITING_GERAI, Queue::STATUS_CALLING_GERAI])
            ->count();

        return [
            'queue_id' => $ticket->id,
            'nomor_antrian' => $ticket->number,
            'service_id' => $ticket->service_id,
            'service_name' => $service?->name,
            'instansi_name' => $service?->opd?->name,
            'gerai_name' => $gerai?->name,
            'is_priority' => (bool) $ticket->is_priority,
            'priority_type' => $ticket->is_priority ? $ticket->priority_type : null,
            'priority_label' => $ticket->priority_label,
            'issued_at' => $ticket->created_at,
            'remaining_queue' => $remainingQueue,
            'template_version_id' => $ticket->mpp_ticket_template_version_id,
        ];
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

    /**
     * @param  array<string, mixed>  $validated
     */
    private function payloadHash(Request $request, MppService $service, array $validated, bool $isPriority, ?string $priorityType): string
    {
        $formData = $request->input('form_data', []);

        foreach ($service->fields as $field) {
            if ($field['type'] !== 'file' || ! $request->hasFile("form_data.{$field['name']}")) {
                continue;
            }

            $file = $request->file("form_data.{$field['name']}");
            $formData[$field['name']] = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'sha256' => hash_file('sha256', $file->getRealPath()),
            ];
        }

        return hash('sha256', json_encode([
            'service_id' => $service->id,
            'form_data' => $this->sortPayload($formData),
            'notes' => $validated['notes'] ?? null,
            'priority' => $isPriority,
            'priority_type' => $priorityType,
        ], JSON_THROW_ON_ERROR));
    }

    /**
     * @param  array<string|int, mixed>  $payload
     * @return array<string|int, mixed>
     */
    private function sortPayload(array $payload): array
    {
        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->sortPayload($value);
            }
        }

        if (! array_is_list($payload)) {
            ksort($payload);
        }

        return $payload;
    }
}

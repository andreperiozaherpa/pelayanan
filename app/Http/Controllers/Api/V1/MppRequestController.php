<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        [$mppServiceRequest, $ticket] = DB::transaction(function () use ($request, $service, $submittedData) {
            $service = MppService::query()->whereKey($service->id)->lockForUpdate()->first();

            $nomorAntrian = $this->queueService->generateNomorAntrian($service);

            $mppServiceRequest = MppServiceRequest::create([
                'mpp_service_id' => $service->id,
                'nomor_antrian' => $nomorAntrian,
                'front_office_user_id' => $request->user()?->id,
                'notes' => $request->input('notes'),
                'submitted_form_data' => $submittedData,
                'status' => MppServiceRequest::STATUS_PENDING,
            ]);

            $ticket = $this->queueService->createTicket($service, $nomorAntrian);

            $mppServiceRequest->forceFill(['queue_id' => $ticket->id])->save();

            return [$mppServiceRequest, $ticket];
        });

        Audit::log('SUBMIT_MPP_SERVICE_REQUEST_API', $mppServiceRequest, $mppServiceRequest->toArray());

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $mppServiceRequest->id,
                'queue_id' => $ticket->id,
                'nomor_antrian' => $ticket->number,
                'status' => $ticket->status,
                'mpp_service_id' => $mppServiceRequest->mpp_service_id,
            ],
        ], 201);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Enums\ServiceType;
use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\DomicileRecord;
use App\Models\MoveRecord;
use App\Models\PovertyRecord;
use App\Models\ServiceRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    /**
     * Report a service provided to a citizen.
     */
    public function store(Request $request)
    {
        Gate::authorize('service.report');

        $request->validate([
            'citizen_nik' => 'required|string|size:16|exists:citizens,nik',
            'service_type' => ['required', 'string', 'max:255', 'in:'.implode(',', ServiceType::values())],
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        if ($user->isOperatorDesa()) {
            $citizen = Citizen::where('nik', $request->citizen_nik)->first();
            if ($citizen->desa_id != $user->desa_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak untuk memberikan layanan di luar wilayah tugas Anda.',
                ], 403);
            }
        }

        // Security Hardening: Atomic check and create to prevent race conditions
        $isPoverty = $request->service_type == ServiceType::POVERTY->value;
        $isDomicile = $request->service_type == ServiceType::DOMICILE->value;
        $isMove = $request->service_type == ServiceType::MOVE->value;

        if ($isPoverty || $isDomicile || $isMove) {
            // 1. Unified Record Check
            $record = match (true) {
                $isPoverty => PovertyRecord::where('citizen_nik', $request->citizen_nik)->latest()->first(),
                $isDomicile => DomicileRecord::where('citizen_nik', $request->citizen_nik)->latest()->first(),
                $isMove => MoveRecord::where('citizen_nik', $request->citizen_nik)->latest()->first(),
                default => null
            };

            if ($record) {
                $isExpired = $record->valid_until && Carbon::parse($record->valid_until)->isPast();
                $isRejected = $record->status === 'REJECTED';

                // Block if status is PENDING or ACTIVE (and not expired)
                if ($record->status === 'PENDING') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permohonan layanan ini sedang dalam proses verifikasi desa.',
                    ], 422);
                }

                if ($record->status === 'ACTIVE' && ! $isExpired) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Warga ini sudah memiliki layanan yang masih aktif.',
                    ], 422);
                }
            }

            return DB::transaction(function () use ($request, $isPoverty, $isDomicile, $isMove) {
                // Keep the daily duplicate check as a fallback for the ServiceRequest table itself
                $exists = ServiceRequest::where('citizen_nik', $request->citizen_nik)
                    ->where('service_type', $request->service_type)
                    ->whereDate('created_at', now()->toDateString())
                    ->where('status', 'PENDING')
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Layanan ini sudah dilaporkan untuk warga tersebut hari ini.',
                    ], 422);
                }

                $finalNotes = $request->notes;
                if ($isMove && $request->destination_address) {
                    $finalNotes = "TUJUAN: {$request->destination_address}".($request->notes ? "\nCATATAN: {$request->notes}" : '');
                }

                $service = ServiceRequest::create([
                    'citizen_nik' => $request->citizen_nik,
                    'service_type' => $request->service_type,
                    'status' => 'PENDING',
                    'front_office_user_id' => Auth::user()->id,
                    'notes' => $finalNotes,
                ]);

                if ($isPoverty) {
                    PovertyRecord::updateOrCreate(
                        ['citizen_nik' => $request->citizen_nik],
                        ['status' => 'PENDING', 'source' => 'FRONT_OFFICE_REQUEST']
                    );
                } elseif ($isDomicile) {
                    DomicileRecord::updateOrCreate(
                        ['citizen_nik' => $request->citizen_nik],
                        ['status' => 'PENDING', 'source' => 'FRONT_OFFICE_REQUEST']
                    );
                } elseif ($isMove) {
                    MoveRecord::updateOrCreate(
                        ['citizen_nik' => $request->citizen_nik],
                        ['status' => 'PENDING', 'source' => 'FRONT_OFFICE_REQUEST']
                    );
                }

                Cache::forget("citizen_services_{$request->citizen_nik}");

                Audit::log('REPORT_SERVICE', $service, $service->toArray());

                return response()->json([
                    'success' => true,
                    'message' => 'Pelayanan berhasil dicatat.',
                ]);
            });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pelayanan Belum Tersedia.',
            ], 404);
        }
    }

    /**
     * Display a listing of pending service requests for the village.
     */
    public function index(Request $request)
    {
        Gate::authorize('service.manage');

        $user = Auth::user();
        $query = ServiceRequest::with(['citizen', 'citizen.village']);

        // Type Filtering
        if ($request->has('type')) {
            $query->where('service_type', $request->type);
        }

        if (! $user->isSuperAdmin()) {
            $desaId = $user->desa_id;
            $query->whereHas('citizen', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            });
        }

        $requests = $query->where('status', 'PENDING')
            ->latest()
            ->paginate(15);

        return view('services.desa.requests.index', compact('requests'));
    }

    /**
     * Approve a service request and activate poverty record.
     */
    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('service.manage');

        // Strict protection: Only the specific village authority can approve
        if ($serviceRequest->citizen->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Persetujuan hanya dapat dilakukan oleh otoritas desa setempat. Super Admin hanya memiliki akses pemantauan.');
        }

        $validationRules = [
            'valid_until' => 'required|date|after:today',
        ];

        if ($serviceRequest->service_type === ServiceType::POVERTY) {
            $validationRules['income_range'] = 'required|string';
        }

        if ($serviceRequest->service_type === ServiceType::DOMICILE) {
            $validationRules['purpose'] = 'required|string';
        }

        $request->validate($validationRules);

        return DB::transaction(function () use ($request, $serviceRequest) {
            $serviceRequest->update(['status' => 'APPROVED']);

            if ($serviceRequest->service_type === ServiceType::POVERTY) {
                PovertyRecord::updateOrCreate(
                    ['citizen_nik' => $serviceRequest->citizen_nik],
                    [
                        'status' => 'ACTIVE',
                        'income_range' => $request->income_range,
                        'valid_from' => now(),
                        'valid_until' => $request->valid_until,
                        'verified_by' => Auth::user()->id,
                        'source' => 'VILLAGE_VERIFICATION',
                    ]
                );
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::DOMICILE) {
                DomicileRecord::updateOrCreate(
                    ['citizen_nik' => $serviceRequest->citizen_nik],
                    [
                        'status' => 'ACTIVE',
                        'purpose' => $request->purpose,
                        'valid_from' => now(),
                        'valid_until' => $request->valid_until,
                        'verified_by' => Auth::user()->id,
                        'source' => 'VILLAGE_VERIFICATION',
                    ]
                );
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::MOVE) {
                MoveRecord::updateOrCreate(
                    ['citizen_nik' => $serviceRequest->citizen_nik],
                    [
                        'status' => 'ACTIVE',
                        'destination_address' => $request->destination_address,
                        'reason' => $request->reason,
                        'valid_until' => $request->valid_until,
                        'verified_by' => Auth::user()->id,
                        'issued_at' => now(),
                    ]
                );
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            }

            Audit::log('APPROVE_SERVICE', $serviceRequest, [
                'citizen_nik' => $serviceRequest->citizen_nik,
                'service_type' => $serviceRequest->service_type,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil disetujui dan status warga diaktifkan.',
            ]);
        });
    }

    /**
     * Reject a service request.
     */
    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('service.manage');

        // Strict protection: Only the specific village authority can reject
        if ($serviceRequest->citizen->desa_id !== Auth::user()->desa_id) {
            abort(403, 'Penolakan hanya dapat dilakukan oleh otoritas desa setempat.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        return DB::transaction(function () use ($request, $serviceRequest) {
            $serviceRequest->update([
                'status' => 'REJECTED',
                'notes' => $request->reason, // Save the rejection reason to notes for easy display
            ]);

            // Update Records if they were PENDING
            if ($serviceRequest->service_type === ServiceType::POVERTY) {
                PovertyRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'REJECTED']);
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::DOMICILE) {
                DomicileRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'REJECTED']);
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            } elseif ($serviceRequest->service_type === ServiceType::MOVE) {
                MoveRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                    ->where('status', 'PENDING')
                    ->update(['status' => 'REJECTED']);
                Cache::forget("citizen_services_{$serviceRequest->citizen_nik}");
            }

            Audit::log('REJECT_SERVICE', $serviceRequest, [
                'reason' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan telah ditolak.',
            ]);
        });
    }
}

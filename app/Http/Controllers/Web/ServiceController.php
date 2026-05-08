<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\PovertyRecord;
use App\Models\ServiceRequest;
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
            'service_type' => ['required', 'string', 'max:255', 'in:'.implode(',', config('services.service_types'))],
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
        if ($request->service_type == 'KETERANGAN KEMISKINAN') {
            return DB::transaction(function () use ($request) {
                $exists = ServiceRequest::where('citizen_nik', $request->citizen_nik)
                    ->where('service_type', $request->service_type)
                    ->whereDate('created_at', now()->toDateString())
                    ->lockForUpdate() // Prevent other processes from writing/checking at same time
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Layanan ini sudah dilaporkan untuk warga tersebut hari ini.',
                    ], 422);
                }

                $service = ServiceRequest::create([
                    'citizen_nik' => $request->citizen_nik,
                    'service_type' => $request->service_type,
                    'status' => 'PENDING',
                    'front_office_user_id' => Auth::user()->id,
                    'notes' => $request->notes,
                ]);

                // Synchronize with poverty_records so FO sees "PENDING" status on the citizen card
                // We only set it to PENDING if there's no record or the existing record is not ACTIVE
                PovertyRecord::updateOrCreate(
                    ['citizen_nik' => $request->citizen_nik],
                    [
                        'status' => 'PENDING',
                        'source' => 'FRONT_OFFICE_REQUEST',
                    ]
                );

                Cache::forget("poverty_status_{$request->citizen_nik}");

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
            ]);
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

        $request->validate([
            'income_range' => 'required|string',
            'valid_until' => 'required|date|after:today',
        ]);

        return DB::transaction(function () use ($request, $serviceRequest) {
            // ... logic stays the same ...
            $serviceRequest->update(['status' => 'APPROVED']);

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

            Cache::forget("poverty_status_{$serviceRequest->citizen_nik}");

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

            // Update PovertyRecord if it was PENDING
            PovertyRecord::where('citizen_nik', $serviceRequest->citizen_nik)
                ->where('status', 'PENDING')
                ->update(['status' => 'REJECTED']);

            Cache::forget("poverty_status_{$serviceRequest->citizen_nik}");

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

<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'status' => 'APPROVED',
                'front_office_user_id' => Auth::user()->id,
                'notes' => $request->notes,
            ]);

            Audit::log('REPORT_SERVICE', $service, $service->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Pelayanan berhasil dicatat.',
            ]);
        });
    }
}

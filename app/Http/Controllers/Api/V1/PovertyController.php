<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StorePovertyRecordRequest;
use App\Http\Resources\V1\PovertyRecordResource;
use App\Models\Citizen;
use App\Models\PovertyRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PovertyController extends Controller
{
    /**
     * Check poverty status for a NIK (High Performance).
     */
    public function status(string $nik): JsonResponse
    {
        $cacheKey = "citizen_services_{$nik}";

        // Toba: Check Redis cache first
        $status = Cache::remember($cacheKey, now()->addHours(24), function () use ($nik) {
            $citizen = Citizen::where('nik', $nik)->first();

            if (! $citizen) {
                return ['status' => 'NOT_FOUND', 'message' => 'Citizen record not found.'];
            }

            $record = $citizen->povertyRecords()->latest()->first();

            if (! $record) {
                return ['status' => 'PENDING_REVIEW', 'message' => 'No poverty record found. Review required.'];
            }

            if ($record->valid_until < now()) {
                return ['status' => 'EXPIRED', 'message' => 'Poverty record has expired.', 'record' => $record];
            }

            return ['status' => 'ACTIVE', 'message' => 'Poverty record is active.', 'record' => $record];
        });

        return response()->json([
            'status' => 'success',
            'data' => $status,
        ]);
    }

    /**
     * Store a new poverty record.
     */
    public function store(StorePovertyRecordRequest $request): PovertyRecordResource
    {
        $data = $request->validated();
        $data['verified_by'] = $request->user()->id;

        $record = PovertyRecord::create($data);

        // Clear cache for this citizen
        Cache::forget("citizen_services_{$data['citizen_nik']}");

        return new PovertyRecordResource($record);
    }

    /**
     * List poverty records (scoped).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = PovertyRecord::with('citizen');

        if ($user->isOperatorDesa()) {
            $query->whereHas('citizen', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        }

        $records = $query->paginate($request->input('per_page', 15));

        return PovertyRecordResource::collection($records);
    }
}

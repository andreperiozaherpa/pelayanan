<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\GeraiResource;
use App\Http\Resources\V1\ServiceResource;
use App\Models\Counter;
use App\Models\MppService;
use Illuminate\Http\JsonResponse;

class MppServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = MppService::query()
            ->where('is_active', true)
            ->with('gerai', 'opd.gerais')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => ServiceResource::collection($services),
        ]);
    }

    public function show(MppService $service): JsonResponse
    {
        if (! $service->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'Pelayanan tidak aktif.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ServiceResource($service->load('gerai', 'opd.gerais')),
        ]);
    }

    public function geraiIndex(): JsonResponse
    {
        $gerai = Counter::query()
            ->with('gerai')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return response()->json([
            'success' => true,
            'data' => GeraiResource::collection($gerai),
        ]);
    }
}

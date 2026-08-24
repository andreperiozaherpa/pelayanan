<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\SkmResponse;
use App\Services\SkmService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MppSkmController extends Controller
{
    public function __construct(private readonly SkmService $skm) {}

    public function questions(Opd $opd): JsonResponse
    {
        $gerai = $opd->gerais()->where('is_active', true)->first();

        if (! $gerai) {
            return response()->json([
                'success' => false,
                'error' => 'Instansi tidak aktif.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'opd' => [
                    'id' => $opd->id,
                    'code' => $opd->code,
                    'nama' => $opd->name,
                ],
                'gerai' => [
                    'code' => $gerai->code,
                    'nama' => $gerai->name,
                ],
                'unsur' => $this->skm->unsur(),
            ],
        ]);
    }

    public function store(Request $request, Opd $opd): JsonResponse
    {
        $gerai = $opd->gerais()->where('is_active', true)->first();

        if (! $gerai) {
            return response()->json([
                'success' => false,
                'error' => 'Instansi tidak aktif.',
            ], 404);
        }

        $validated = $request->validate([
            'nama' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'umur' => ['nullable', 'integer', 'min:10', 'max:120'],
            'pendidikan' => ['nullable', 'string', 'max:255'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'saran' => ['nullable', 'string', 'max:2000'],
            ...collect(SkmService::unsur())->mapWithKeys(fn ($u) => [$u['key'] => ['required', 'integer', Rule::in([1, 2, 3, 4])]])->all(),
        ]);

        SkmResponse::create([
            'opd_id' => $opd->id,
            'nama' => $validated['nama'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'umur' => $validated['umur'] ?? null,
            'pendidikan' => $validated['pendidikan'] ?? null,
            'pekerjaan' => $validated['pekerjaan'] ?? null,
            ...collect(SkmService::unsur())->mapWithKeys(fn ($u) => [$u['key'] => $validated[$u['key']]])->all(),
            'saran' => $validated['saran'] ?? null,
            'source' => SkmResponse::SOURCE_API,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Survei berhasil dikirim.',
        ], 201);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\DeathRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MppCitizenController extends Controller
{
    public function show(string $nik): JsonResponse
    {
        $user = Auth::user();
        $citizen = Citizen::with('village')->where('nik', $nik)->first();

        if (! $citizen) {
            return response()->json([
                'success' => false,
                'message' => 'Warga tidak ditemukan di database.',
            ]);
        }

        if ($user->isOperatorDesa() && $citizen->desa_id != $user->desa_id) {
            return response()->json([
                'success' => false,
                'message' => 'Data warga berada di luar wilayah tugas Anda.',
            ]);
        }

        $isDeceased = DeathRecord::where('citizen_nik', $nik)->where('status', 'ACTIVE')->exists();

        return response()->json([
            'success' => true,
            'citizen' => [
                'nik' => $citizen->nik,
                'name' => $citizen->nama_lengkap,
                'village' => $citizen->village ? $citizen->village->name : '-',
                'desa_id' => $citizen->desa_id,
                'dob' => $citizen->tgl_lahir,
                'address' => $citizen->alamat_desa,
                'is_deceased' => $isDeceased,
            ],
        ]);
    }
}

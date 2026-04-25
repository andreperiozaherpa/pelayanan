<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Citizen;
use App\Models\VerificationLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class VerificationController extends Controller
{
    /**
     * Conduct a real-time verification via Web UI.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'method' => 'required|string|in:NIK,QR'
        ]);

        $nik = $request->nik;
        $cacheKey = "poverty_status_{$nik}";

        // Toba: Check Cache first (Blueprint 4.1)
        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($nik) {
            $citizen = Citizen::with(['village', 'povertyRecords' => function ($q) {
                $q->latest();
            }])->where('nik', $nik)->first();

            if (!$citizen) {
                return null;
            }

            $record = $citizen->povertyRecords->first();
            $isExpired = $record ? Carbon::parse($record->valid_until)->isPast() : false;

            return [
                'citizen' => $citizen->toArray(),
                'record' => $record ? $record->toArray() : null,
                'is_expired' => $isExpired
            ];
        });

        // 1. Check if Citizen Exists Globaly
        if (!$data) {
            return response()->json([
                'success' => false,
                'data' => [
                    'status' => 'NOT_FOUND',
                    'message' => 'Data warga tidak ditemukan di sistem.'
                ]
            ]);
        }

        // 2. Role-Based Scoping (Security Fix)
        $user = Auth::user();
        if ($user->role->slug === 'operatordesa') {
            if ($data['citizen']['desa_id'] != $user->desa_id) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'status' => 'NOT_FOUND',
                        'message' => 'Data warga tidak ditemukan atau di luar wilayah tugas Anda.'
                    ]
                ]);
            }
        }

        // 3. Construct Response Array
        $record = $data['record'];
        if ($record && isset($record['valid_until'])) {
            $record['valid_until_formatted'] = Carbon::parse($record['valid_until'])->translatedFormat('d F Y');
        }

        $status = [
            'status' => !$data['record'] ? 'PENDING_REVIEW' : ($data['is_expired'] ? 'EXPIRED' : 'ACTIVE'),
            'message' => !$data['record']
                ? 'Data kemiskinan belum ada. Perlu tinjauan desa.'
                : ($data['is_expired'] ? 'Status kemiskinan kadaluarsa.' : 'Status kemiskinan Aktif.'),
            'citizen' => $data['citizen'],
            'record' => $record
        ];

        // 4. Data Privacy Masking (Blueprint 3.3)
        if ($user->role->slug === 'petugasfrontoffice') {
            if (isset($status['citizen']['alamat_desa'])) {
                $addrParts = explode(' ', $status['citizen']['alamat_desa'], 3);
                $status['citizen']['alamat_desa'] = (count($addrParts) >= 2 ? implode(' ', array_slice($addrParts, 0, 2)) : $addrParts[0]) . ' *** (Data Disamarkan)';
            }
            if (isset($status['citizen']['kontak'])) {
                $status['citizen']['kontak'] = '*** (Data Disamarkan)';
            }
            if (isset($status['record']['income_range'])) {
                $status['record']['income_range'] = '*** (Data Disamarkan)';
            }
        }

        // Audit Log for Verification (Blueprint 6)
        VerificationLog::create([
            'user_id' => Auth::user()->id,
            'nik' => $nik,
            'method' => $request->method,
            'result' => $status['status'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        // Generic Audit Log
        AuditLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'VERIFY_CHECK',
            'target_table' => 'citizens',
            'target_id' => null, // NIK is string, target_id is bigint
            'new_value' => ['nik' => $nik, 'result' => $status['status']],
            'timestamp' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }
}

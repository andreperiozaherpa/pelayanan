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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use LSNepomuceno\LaravelA1PdfSign\Sign\ManageCert;
use LSNepomuceno\LaravelA1PdfSign\Sign\SignaturePdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;

class VerificationController extends Controller
{
    /**
     * Conduct a real-time verification via Web UI.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'method' => 'required|string|in:NIK,QR',
        ]);

        $nik = $request->nik;
        $cacheKey = "poverty_status_{$nik}";

        // Toba: Check Cache first (Blueprint 4.1)
        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($nik) {
            $citizen = Citizen::with(['village', 'povertyRecords' => function ($q) {
                $q->latest();
            }])->where('nik', $nik)->first();

            if (! $citizen) {
                return null;
            }

            $record = $citizen->povertyRecords->first();
            $isExpired = $record ? Carbon::parse($record->valid_until)->isPast() : false;

            return [
                'citizen' => $citizen->toArray(),
                'record' => $record ? $record->toArray() : null,
                'is_expired' => $isExpired,
            ];
        });

        // 1. Check if Citizen Exists Globaly
        if (! $data) {
            return response()->json([
                'success' => false,
                'data' => [
                    'status' => 'NOT_FOUND',
                    'message' => 'Data warga tidak ditemukan di sistem.',
                ],
            ]);
        }

        // 2. Role-Based Scoping (Security Fix)
        $user = Auth::user();
        if ($user->isOperatorDesa()) {
            if ($data['citizen']['desa_id'] != $user->desa_id) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'status' => 'NOT_FOUND',
                        'message' => 'Data warga tidak ditemukan atau di luar wilayah tugas Anda.',
                    ],
                ]);
            }
        }

        // 3. Construct Response Array
        $record = $data['record'];
        if ($record && isset($record['valid_until'])) {
            $record['valid_until_formatted'] = Carbon::parse($record['valid_until'])->translatedFormat('d F Y');
        }

        $status = [
            'status' => ! $data['record'] ? 'PENDING_REVIEW' : ($data['is_expired'] ? 'EXPIRED' : 'ACTIVE'),
            'message' => ! $data['record']
                ? 'Data kemiskinan belum ada. Perlu tinjauan desa.'
                : ($data['is_expired'] ? 'Status kemiskinan kadaluarsa.' : 'Status kemiskinan Aktif.'),
            'citizen' => $data['citizen'],
            'record' => $record,
        ];

        if ($user->isPetugasFrontOffice()) {
            if (isset($status['citizen']['alamat_desa'])) {
                $addrParts = explode(' ', $status['citizen']['alamat_desa'], 3);
                $status['citizen']['alamat_desa'] = (count($addrParts) >= 2 ? implode(' ', array_slice($addrParts, 0, 2)) : $addrParts[0]).' *** (Data Disamarkan)';
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

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    public function index(): View
    {
        return view('services.verification.index');
    }

    /**
     * Generate a high-fidelity PDF proof of verification.
     */
    public function proof(string $nik)
    {
        // 1. Authorization
        Gate::authorize('poverty.print_proof');

        $citizen = Citizen::with(['village', 'povertyRecords' => function ($q) {
            $q->latest();
        }])->where('nik', $nik)->firstOrFail();

        $record = $citizen->povertyRecords->first();

        // Generate a validation URL
        $validationUrl = route('verification.index', ['nik' => $nik]);

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'PRINT_PROOF',
            'target_table' => 'citizens',
            'target_id' => null,
            'new_value' => ['nik' => $nik],
            'timestamp' => now(),
        ]);

        // Generate QR code in backend
        $qrcode = QrCode::size(100)
            ->format('svg')
            ->margin(1)
            ->errorCorrection('H')
            ->generate($validationUrl);

        $html = view('documents.doc_poverty', compact('citizen', 'record', 'validationUrl', 'qrcode'))->render();

        $pdf = Browsershot::html($html)
            ->setNodeBinary(config('services.browsershot.node_binary'))
            ->setNpmBinary(config('services.browsershot.npm_binary'))
            ->setChromePath(config('services.browsershot.chrome_path'))
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->provideHtmlViaOpenPage()
            ->format('A5')
            ->margins(0, 0, 0, 0)
            ->pdf();

        // 2. Fetch Active Village Leader for Signing
        $village = $citizen->village()->with(['activeLeader.user.certificates' => function ($q) {
            $q->where('is_active', true)->latest();
        }])->first();

        $leader = $village->activeLeader ?? null;
        $signer = $leader ? $leader->user : null;
        $userCert = $signer ? $signer->certificates->first() : null;

        if ($signer && $userCert) {
            $tempPdfPath = storage_path('app/private/temp_'.uniqid().'.pdf');
            file_put_contents($tempPdfPath, $pdf);

            try {
                $cert = new ManageCert;
                $cert->fromPfx(storage_path('app/'.$userCert->certificate_path), $userCert->passphrase);

                $signerPdf = new SignaturePdf(
                    $tempPdfPath,
                    $cert,
                    SignaturePdf::MODE_RESOURCE
                );

                // Format: Nama Pejabat (NIP. 123456...)
                $signerName = $leader->name.($leader->nip ? " (NIP. {$leader->nip})" : '');

                $signerPdf->setInfo(
                    name: $signerName,
                    location: $village->name ?? 'Lampung',
                    reason: 'Penerbitan Surat Keterangan Miskin (TTE Resmi)'
                );

                $pdf = $signerPdf->signature();

                // Save signed PDF to storage for permanent record
                $storagePath = "public/poverty_proofs/{$citizen->desa_id}/{$nik}_".time().'.pdf';
                Storage::put($storagePath, $pdf);

                // Update the Poverty Record with the path
                if ($record) {
                    $record->update(['signed_pdf_path' => $storagePath]);
                }
            } catch (\Exception $e) {
                Log::error('PDF Signing failed for '.($leader->name ?? 'Unknown').': '.$e->getMessage());
                abort(500, 'Gagal membubuhkan tanda tangan elektronik: '.$e->getMessage());
            } finally {
                if (file_exists($tempPdfPath)) {
                    unlink($tempPdfPath);
                }
            }
        } else {
            abort(403, 'Gagal mencetak: Pejabat penandatangan belum memiliki sertifikat TTE yang aktif.');
        }

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="bukti-verifikasi-'.$nik.'.pdf"');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Citizen;
use App\Models\HouseholdCard;
use App\Models\ServiceRequest;
use App\Models\VerificationLog;
use App\Services\Tte\ProfessionalSignaturePdf as SignaturePdf;
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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SimpleSoftwareIO\QrCode\Generator;
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

        $input = $request->nik;

        // 1. Check if it's a Household Card (KK)
        $household = HouseholdCard::with(['village', 'citizens.povertyRecords' => function ($q) {
            $q->latest();
        }])->where('no_kk', $input)->first();

        if ($household) {
            return response()->json([
                'success' => true,
                'type' => 'HOUSEHOLD',
                'data' => [
                    'household' => $household,
                    'members' => $household->citizens->map(function ($citizen) {
                        $record = $citizen->povertyRecords->first();

                        return [
                            'nik' => $citizen->nik,
                            'nama_lengkap' => $citizen->nama_lengkap,
                            'status' => ! $record ? 'UNREGISTERED' : ($record->status ?? (Carbon::parse($record->valid_until)->isPast() ? 'EXPIRED' : 'ACTIVE')),
                            'record' => $record,
                        ];
                    }),
                ],
            ]);
        }

        // 2. Original NIK Verification Logic
        $nik = $input;
        $cacheKey = "poverty_status_{$nik}";

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
            'status' => ! $data['record']
                ? 'UNREGISTERED'
                : ($data['record']['status'] === 'PENDING'
                    ? 'PENDING'
                    : ($data['record']['status'] === 'REJECTED'
                        ? 'REJECTED'
                        : ($data['record']['status'] === 'EXPIRED' || $data['is_expired'] ? 'EXPIRED' : 'ACTIVE'))),
            'message' => ! $data['record']
                ? 'Data tidak ditemukan dalam basis data kemiskinan.'
                : ($data['record']['status'] === 'PENDING'
                    ? 'Sedang dalam proses verifikasi desa.'
                    : ($data['record']['status'] === 'REJECTED'
                        ? 'Permohonan verifikasi ditolak oleh desa.'
                        : ($data['record']['status'] === 'EXPIRED' || $data['is_expired']
                            ? 'Status kemiskinan kadaluarsa. Silakan ajukan ulang.'
                            : 'Status kemiskinan Aktif.'))),
            'citizen' => $data['citizen'],
            'record' => $record,
            'rejection_reason' => ($data['record'] && $data['record']['status'] === 'REJECTED')
                ? ServiceRequest::where('citizen_nik', $nik)->where('status', 'REJECTED')->latest()->value('notes')
                : null,
        ];

        if ($user->isPetugasFrontOffice()) {
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

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    public function index(): View
    {
        return view('services.admin.verification.index');
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
        $validationUrl = route('verification.index', ['q' => $nik]);

        // Fetch Active Village Leader and Certificate first to sync data
        $village = $citizen->village()->with(['activeLeader.user.certificates' => function ($q) {
            $q->where('is_active', true)->latest();
        }])->first();

        $leader = $village->activeLeader ?? null;
        $signer = $leader ? $leader->user : null;
        $userCert = $signer ? $signer->certificates->first() : null;

        if (! $signer || ! $userCert) {
            abort(403, 'Gagal mencetak: Pejabat penandatangan belum memiliki sertifikat TTE yang aktif.');
        }

        // Initialize Certificate to extract real metadata
        try {
            $manageCert = new ManageCert;
            $manageCert->setPreservePfx(true); // CRITICAL: Prevent vendor library from deleting our certificate!
            $manageCert->fromPfx(storage_path('app/' . $userCert->certificate_path), $userCert->passphrase);
            $certData = $manageCert->getCert()->data;

            // Get real serial number from cert
            $serialNumber = $certData['serialNumber'] ?? 'N/A';
        } catch (\Exception $e) {
            Log::error('Certificate initialization failed: ' . $e->getMessage());
            abort(500, 'Gagal memuat sertifikat TTE: ' . $e->getMessage());
        }

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'PRINT_PROOF',
            'target_table' => 'citizens',
            'target_id' => null,
            'new_value' => ['nik' => $nik],
            'timestamp' => now(),
        ]);

        // Generate QR code as PNG for visual signature compatibility
        $qrPath = storage_path('app/private/qr_' . uniqid() . '.png');
        /** @var Generator $qr */
        $qr = QrCode::format('png');
        $qr->size(300)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($validationUrl . '?sn=' . $serialNumber, $qrPath);

        $html = view('documents.doc_poverty', compact('citizen', 'record', 'validationUrl', 'serialNumber', 'leader'))->render();

        // 2. Detect coordinates of the placeholder via DOM attributes
        $renderedHtml = Browsershot::html($html)
            ->setNodeBinary(config('services.browsershot.node_binary'))
            ->setNpmBinary(config('services.browsershot.npm_binary'))
            ->setChromePath(config('services.browsershot.chrome_path'))
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->waitUntilNetworkIdle()
            ->bodyHtml();

        $coords = [];
        if (preg_match('/data-tte-page="([^"]+)"/', $renderedHtml, $matchPage)) {
            $coords['page'] = (int) $matchPage[1];
        }
        if (preg_match('/data-tte-x="([^"]+)"/', $renderedHtml, $matchX)) {
            $coords['x'] = (float) $matchX[1];
        }
        if (preg_match('/data-tte-y="([^"]+)"/', $renderedHtml, $matchY)) {
            $coords['y'] = (float) $matchY[1];
        }
        if (preg_match('/data-tte-w="([^"]+)"/', $renderedHtml, $matchW)) {
            $coords['w'] = (float) $matchW[1];
        }

        $pdf = Browsershot::html($html)
            ->setNodeBinary(config('services.browsershot.node_binary'))
            ->setNpmBinary(config('services.browsershot.npm_binary'))
            ->setChromePath(config('services.browsershot.chrome_path'))
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->provideHtmlViaOpenPage()
            ->format('A4')
            ->margins(0, 0, 0, 0)
            ->pdf();

        // 3. Digital Signing
        $tempPdfPath = storage_path('app/private/temp_' . uniqid() . '.pdf');
        file_put_contents($tempPdfPath, $pdf);

        try {
            $signerPdf = new SignaturePdf(
                $tempPdfPath,
                $manageCert,
                SignaturePdf::MODE_RESOURCE
            );

            $posX = $coords['x'] ?? 105;
            $posY = $coords['y'] ?? 22;
            $width = $coords['w'] ?? 30;
            $pageNumber = $coords['page'] ?? -1;

            // Set Visual Signature (QR Code) with dynamic page
            $signerPdf->setImage($qrPath, $posX, $posY, $width, 0, $pageNumber);

            $signerName = $leader->name . ($leader->nip ? " (NIP. {$leader->nip})" : '');

            $signerPdf->setInfo(
                name: $signerName,
                location: $village->name ?? 'Lampung',
                reason: 'Penerbitan Surat Keterangan Miskin (TTE Resmi)'
            );

            $pdf = $signerPdf->signature();

            // Save signed PDF to storage
            $storagePath = "public/poverty_proofs/{$citizen->desa_id}/{$nik}_" . time() . '.pdf';
            Storage::put($storagePath, $pdf);

            if ($record) {
                $record->update(['signed_pdf_path' => $storagePath]);
            }
        } catch (\Exception $e) {
            Log::error('PDF Signing failed: ' . $e->getMessage());
            abort(500, 'Gagal membubuhkan tanda tangan elektronik: ' . $e->getMessage());
        } finally {
            if (file_exists($tempPdfPath)) {
                unlink($tempPdfPath);
            }
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }
        }

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="bukti-verifikasi-' . $nik . '.pdf"');
    }
}

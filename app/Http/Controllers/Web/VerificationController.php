<?php

namespace App\Http\Controllers\Web;

use App\Enums\ServiceType;
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
        Gate::authorize('service.verify');

        $request->validate([
            'nik' => 'required|string|size:16',
            'method' => 'required|string|in:NIK,QR',
        ]);

        $input = $request->nik;

        // 1. Try Household Check First
        $household = HouseholdCard::with([
            'village',
            'citizens.povertyRecords' => fn ($q) => $q->latest(),
            'citizens.domicileRecords' => fn ($q) => $q->latest(),
            'citizens.moveRecords' => fn ($q) => $q->latest(),
            'citizens.arrivalRecords' => fn ($q) => $q->latest(),
        ])->where('no_kk', $input)->first();

        if ($household) {
            return $this->handleHouseholdCheck($household);
        }

        // 2. Fallback to Citizen Check
        return $this->handleCitizenCheck($input, $request);
    }

    public function index(): View
    {
        Gate::authorize('service.verify');

        return view('services.admin.verification.index');
    }

    /**
     * Generate a high-fidelity PDF proof of verification.
     */
    public function proof(string $nik, string $type)
    {
        $config = $this->getProofConfig($type);

        // 1. Authorization
        Gate::authorize($config['permission']);

        // Check if view template exists
        if (! view()->exists($config['view'])) {
            abort(404, "Template dokumen untuk tipe '{$type}' belum tersedia.");
        }

        // 2. Fetch Base Citizen Data
        $citizen = Citizen::with(['village'])->where('nik', $nik)->firstOrFail();
        $record = null;

        // Conditional Data Loading
        if ($type === 'poverty') {
            $citizen->load(['povertyRecords' => fn ($q) => $q->latest()]);
            $record = $citizen->povertyRecords->first();
        } elseif ($type === 'domicile') {
            $citizen->load(['domicileRecords' => fn ($q) => $q->latest()]);
            $record = $citizen->domicileRecords->first();
        } elseif ($type === 'move') {
            $citizen->load(['moveRecords' => fn ($q) => $q->latest()]);
            $record = $citizen->moveRecords->first();
        } elseif ($type === 'death') {
            $citizen->load(['deathRecords' => fn ($q) => $q->latest()]);
            $record = $citizen->deathRecords->first();
        } elseif ($type === 'arrival') {
            $citizen->load(['arrivalRecords' => fn ($q) => $q->latest()]);
            $record = $citizen->arrivalRecords->first();
        }

        // Generate a validation URL
        $validationUrl = route('services.verification', ['q' => $nik]);

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
            $manageCert->fromPfx(storage_path('app/'.$userCert->certificate_path), $userCert->passphrase);
            $certData = $manageCert->getCert()->data;

            // Get real serial number from cert
            $serialNumber = $certData['serialNumber'] ?? 'N/A';
        } catch (\Exception $e) {
            Log::error('Certificate initialization failed: '.$e->getMessage());
            abort(500, 'Gagal memuat sertifikat TTE: '.$e->getMessage());
        }

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'PRINT_PROOF',
            'target_table' => 'citizens',
            'target_id' => null,
            'new_value' => ['nik' => $nik, 'type' => $type],
            'timestamp' => now(),
        ]);

        // Generate QR code as PNG for visual signature compatibility
        $qrPath = storage_path('app/private/qr_'.uniqid().'.png');
        /** @var Generator $qr */
        $qr = QrCode::format('png');
        $qr->size(300)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($validationUrl.'?sn='.$serialNumber, $qrPath);

        $html = view($config['view'], compact('citizen', 'record', 'validationUrl', 'serialNumber', 'leader'))->render();

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
        $tempPdfPath = storage_path('app/private/temp_'.uniqid().'.pdf');
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

            $signerName = $leader->name.($leader->nip ? " (NIP. {$leader->nip})" : '');

            $signerPdf->setInfo(
                name: $signerName,
                location: $village->name ?? 'Lampung',
                reason: $config['reason']
            );

            $pdf = $signerPdf->signature();

            // Save signed PDF to storage
            $storagePath = "{$config['storage_dir']}/{$citizen->desa_id}/{$nik}_".time().'.pdf';
            Storage::put($storagePath, $pdf);

            if ($record) {
                $record->update(['signed_pdf_path' => $storagePath]);
            }
        } catch (\Exception $e) {
            Log::error('PDF Signing failed: '.$e->getMessage());
            abort(500, 'Gagal membubuhkan tanda tangan elektronik: '.$e->getMessage());
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
            ->header('Content-Disposition', 'inline; filename="'.$config['filename'].'-'.$nik.'.pdf"');
    }

    /**
     * Determine the status of a service based on record data.
     */
    private function getServiceStatus(array $data, string $type): string
    {
        $record = $data["{$type}_record"] ?? null;
        $isExpired = $data["is_{$type}_expired"] ?? false;

        if (! $record) {
            return 'UNREGISTERED';
        }

        return match ($record['status']) {
            'PENDING' => 'PENDING',
            'REJECTED' => 'REJECTED',
            default => ($record['status'] === 'EXPIRED' || $isExpired) ? 'EXPIRED' : 'ACTIVE',
        };
    }

    /**
     * Get the human-readable message for a specific service status.
     */
    private function getServiceStatusMessage(string $status, string $type): string
    {
        $label = match ($type) {
            'poverty' => 'kemiskinan',
            'domicile' => 'domisili',
            'move' => 'pindah',
            'death' => 'kematian',
            default => $type,
        };

        return match ($status) {
            'UNREGISTERED' => "Data tidak ditemukan dalam basis data {$label}.",
            'PENDING' => 'Sedang dalam proses verifikasi desa.',
            'REJECTED' => "Permohonan {$label} ditolak oleh desa.",
            'EXPIRED' => "Status {$label} kadaluarsa. Silakan ajukan ulang.",
            'ACTIVE' => "Status {$label} Aktif.",
            default => 'Status tidak diketahui.',
        };
    }

    /**
     * Mask sensitive data for front office staff.
     */
    private function maskSensitiveData(array $status): array
    {
        $mask = '*** (Data Disamarkan)';

        if ($address = $status['citizen']['alamat_desa'] ?? null) {
            $words = explode(' ', (string) $address, 3);
            $prefix = implode(' ', array_slice($words, 0, 2));
            $status['citizen']['alamat_desa'] = trim($prefix)." {$mask}";
        }

        foreach (['citizen.kontak', 'poverty_record.income_range', 'domicile_record.purpose'] as $key) {
            data_set($status, $key, $mask, false);
        }

        return $status;
    }

    /**
     * Get the configuration for a proof document type.
     */
    private function getProofConfig(string $type): array
    {
        return match ($type) {
            'poverty' => [
                'view' => 'documents.doc_poverty',
                'reason' => 'Penerbitan Surat Keterangan Miskin (TTE Resmi)',
                'storage_dir' => 'public/poverty_proofs',
                'filename' => 'keterangan-kemiskinan',
                'permission' => 'service.print_proof',
            ],
            'domicile' => [
                'view' => 'documents.doc_domicile',
                'reason' => 'Penerbitan Surat Keterangan Domisili (TTE Resmi)',
                'storage_dir' => 'public/domicile_proofs',
                'filename' => 'keterangan-domisili',
                'permission' => 'service.print_proof',
            ],
            'move' => [
                'view' => 'documents.doc_move',
                'reason' => 'Penerbitan Surat Pengantar Pindah (TTE Resmi)',
                'storage_dir' => 'public/move_proofs',
                'filename' => 'pengantar-pindah',
                'permission' => 'service.print_proof',
            ],
            'death' => [
                'view' => 'documents.doc_death',
                'reason' => 'Penerbitan Surat Keterangan Kematian (TTE Resmi)',
                'storage_dir' => 'public/death_proofs',
                'filename' => 'keterangan-kematian',
                'permission' => 'service.print_proof',
            ],
            'arrival' => [
                'view' => 'documents.doc_arrival',
                'reason' => 'Penerbitan Surat Pengantar Datang (TTE Resmi)',
                'storage_dir' => 'public/arrival_proofs',
                'filename' => 'pengantar-datang',
                'permission' => 'service.print_proof',
            ],
            default => abort(404, 'Tipe dokumen tidak valid.'),
        };
    }

    /**
     * Handle verification for a household (KK).
     */
    private function handleHouseholdCheck(HouseholdCard $household): JsonResponse
    {
        return response()->json([
            'success' => true,
            'type' => 'HOUSEHOLD',
            'data' => [
                'household' => $household,
                'members' => $household->citizens->map(fn ($citizen) => [
                    'nik' => $citizen->nik,
                    'nama_lengkap' => $citizen->nama_lengkap,
                    'poverty_status' => $this->getServiceStatus(['poverty_record' => $citizen->povertyRecords->first(), 'is_poverty_expired' => $citizen->povertyRecords->first()?->valid_until ? Carbon::parse($citizen->povertyRecords->first()->valid_until)->isPast() : false], 'poverty'),
                    'poverty_record' => $citizen->povertyRecords->first(),
                    'domicile_record' => $citizen->domicileRecords->first(),
                    'domicile_status' => $this->getServiceStatus(['domicile_record' => $citizen->domicileRecords->first(), 'is_domicile_expired' => $citizen->domicileRecords->first()?->valid_until ? Carbon::parse($citizen->domicileRecords->first()->valid_until)->isPast() : false], 'domicile'),
                    'move_status' => $this->getServiceStatus(['move_record' => $citizen->moveRecords->first(), 'is_move_expired' => $citizen->moveRecords->first()?->valid_until ? Carbon::parse($citizen->moveRecords->first()->valid_until)->isPast() : false], 'move'),
                    'death_status' => $this->getServiceStatus(['death_record' => $citizen->deathRecords->first(), 'is_death_expired' => false], 'death'),
                    'arrival_status' => $this->getServiceStatus(['arrival_record' => $citizen->arrivalRecords->first(), 'is_arrival_expired' => false], 'arrival'),
                    'poverty_rejection_reason' => $this->getRejectionReason($citizen->nik, ServiceType::POVERTY),
                    'domicile_rejection_reason' => $this->getRejectionReason($citizen->nik, ServiceType::DOMICILE),
                    'move_rejection_reason' => $this->getRejectionReason($citizen->nik, ServiceType::MOVE),
                    'death_rejection_reason' => $this->getRejectionReason($citizen->nik, ServiceType::DEATH),
                    'arrival_rejection_reason' => $this->getRejectionReason($citizen->nik, ServiceType::ARRIVAL),
                ]),
            ],
        ]);
    }

    /**
     * Handle verification for an individual citizen (NIK).
     */
    private function handleCitizenCheck(string $nik, Request $request): JsonResponse
    {
        $cacheKey = "citizen_services_{$nik}";

        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($nik) {
            $citizen = Citizen::with([
                'village',
                'povertyRecords' => fn ($q) => $q->latest(),
                'domicileRecords' => fn ($q) => $q->latest(),
                'moveRecords' => fn ($q) => $q->latest(),
                'deathRecords' => fn ($q) => $q->latest(),
                'arrivalRecords' => fn ($q) => $q->latest(),
            ])->where('nik', $nik)->first();

            if (! $citizen) {
                return null;
            }

            $record = $citizen->povertyRecords->first();
            $domicile = $citizen->domicileRecords->first();
            $move = $citizen->moveRecords->first();
            $death = $citizen->deathRecords->first();

            return [
                'citizen' => $citizen->toArray(),
                'poverty_record' => $record ? $record->toArray() : null,
                'domicile_record' => $domicile ? $domicile->toArray() : null,
                'move_record' => $move ? $move->toArray() : null,
                'death_record' => $death ? $death->toArray() : null,
                'arrival_record' => $citizen->arrivalRecords->first() ? $citizen->arrivalRecords->first()->toArray() : null,
                'is_poverty_expired' => $record ? Carbon::parse($record->valid_until)->isPast() : false,
                'is_domicile_expired' => $domicile ? Carbon::parse($domicile->valid_until)->isPast() : false,
                'is_move_expired' => $move && $move->valid_until ? Carbon::parse($move->valid_until)->isPast() : false,
                'is_death_expired' => false, // Death records do not expire typically
            ];
        });

        if (! $data) {
            return response()->json([
                'success' => false,
                'data' => ['status' => 'NOT_FOUND', 'message' => 'Data warga tidak ditemukan di sistem.'],
            ]);
        }

        $user = Auth::user();
        if ($user->isOperatorDesa() && $data['citizen']['desa_id'] != $user->desa_id) {
            return response()->json([
                'success' => false,
                'data' => ['status' => 'NOT_FOUND', 'message' => 'Data warga tidak ditemukan atau di luar wilayah tugas Anda.'],
            ]);
        }

        $povertyStatus = $this->getServiceStatus($data, 'poverty');
        $domicileStatus = $this->getServiceStatus($data, 'domicile');
        $moveStatus = $this->getServiceStatus($data, 'move');
        $deathStatus = $this->getServiceStatus($data, 'death');
        $arrivalStatus = $this->getServiceStatus($data, 'arrival');

        $status = [
            'poverty_status' => $povertyStatus,
            'poverty_message' => $this->getServiceStatusMessage($povertyStatus, 'poverty'),
            'citizen' => $data['citizen'] ?? null,
            'poverty_record' => $this->formatRecordDates($data['poverty_record'] ?? null),
            'domicile_record' => $this->formatRecordDates($data['domicile_record'] ?? null),
            'move_record' => $this->formatRecordDates($data['move_record'] ?? null),
            'death_record' => $this->formatRecordDates($data['death_record'] ?? null),
            'arrival_record' => $this->formatRecordDates($data['arrival_record'] ?? null),
            'domicile_status' => $domicileStatus,
            'domicile_message' => $this->getServiceStatusMessage($domicileStatus, 'domicile'),
            'move_status' => $moveStatus,
            'move_message' => $this->getServiceStatusMessage($moveStatus, 'move'),
            'death_status' => $deathStatus,
            'death_message' => $this->getServiceStatusMessage($deathStatus, 'death'),
            'arrival_status' => $arrivalStatus,
            'arrival_message' => $this->getServiceStatusMessage($arrivalStatus, 'arrival'),
            'poverty_rejection_reason' => $this->getRejectionReason($nik, ServiceType::POVERTY, $povertyStatus),
            'domicile_rejection_reason' => $this->getRejectionReason($nik, ServiceType::DOMICILE, $domicileStatus),
            'move_rejection_reason' => $this->getRejectionReason($nik, ServiceType::MOVE, $moveStatus),
            'death_rejection_reason' => $this->getRejectionReason($nik, ServiceType::DEATH, $deathStatus),
            'arrival_rejection_reason' => $this->getRejectionReason($nik, ServiceType::ARRIVAL, $arrivalStatus),
        ];

        if ($user->isPetugasFrontOffice()) {
            $status = $this->maskSensitiveData($status);
        }

        $this->logVerification($nik, $request->method, $status);

        return response()->json(['success' => true, 'data' => $status]);
    }

    /**
     * Format record dates if they exist.
     */
    private function formatRecordDates(?array $record): ?array
    {
        if ($record && isset($record['valid_until'])) {
            $record['valid_until_formatted'] = Carbon::parse($record['valid_until'])->translatedFormat('d F Y');
        }

        return $record;
    }

    /**
     * Get the rejection reason for a service if it was rejected.
     */
    private function getRejectionReason(string $nik, ServiceType $type, ?string $status = null): ?string
    {
        if ($status !== null && $status !== 'REJECTED') {
            return null;
        }

        return ServiceRequest::where('citizen_nik', $nik)
            ->where('service_type', $type->value)
            ->where('status', 'REJECTED')
            ->latest()
            ->value('notes');
    }

    /**
     * Log verification activity.
     */
    private function logVerification(string $nik, string $method, array $status): void
    {
        VerificationLog::create([
            'user_id' => Auth::id(),
            'nik' => $nik,
            'method' => $method,
            'result' => "SKTM: {$status['poverty_status']} | SKD: {$status['domicile_status']} | PINDAH: ".($status['move_status'] ?? 'N/A').' | MATI: '.($status['death_status'] ?? 'N/A'),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }
}

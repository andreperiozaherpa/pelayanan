<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicVerificationController extends Controller
{
    /**
     * Tampilkan halaman publik pengecekan keabsahan surat.
     */
    public function show(Request $request): View
    {
        $nik = $request->query('q');
        $type = $request->query('type');
        $serialNumber = $request->query('sn');

        $citizen = null;
        $record = null;
        $isValid = false;
        $serviceLabel = null;
        $statusLabel = null;
        $statusColor = null;

        if ($nik && $type) {
            $citizen = Citizen::with(['village.district'])->where('nik', $nik)->first();

            if ($citizen) {
                [$record, $isValid, $serviceLabel, $statusLabel, $statusColor] = $this->resolveRecord($citizen, $type);
            }
        }

        return view('public.verify', compact(
            'citizen',
            'record',
            'isValid',
            'type',
            'serviceLabel',
            'statusLabel',
            'statusColor',
            'serialNumber',
        ));
    }

    /**
     * Ambil record layanan dan status keabsahannya berdasarkan tipe.
     *
     * @return array{0: mixed, 1: bool, 2: string, 3: string, 4: string}
     */
    private function resolveRecord(Citizen $citizen, string $type): array
    {
        return match ($type) {
            'poverty' => $this->checkRecord(
                $citizen->povertyRecords()->latest()->first(),
                'Surat Keterangan Tidak Mampu (SKTM)',
            ),
            'domicile' => $this->checkRecord(
                $citizen->domicileRecords()->latest()->first(),
                'Surat Keterangan Domisili',
            ),
            'move' => $this->checkRecord(
                $citizen->moveRecords()->latest()->first(),
                'Surat Pengantar Pindah',
            ),
            'death' => $this->checkRecord(
                $citizen->deathRecords()->latest()->first(),
                'Surat Keterangan Kematian',
            ),
            'arrival' => $this->checkRecord(
                $citizen->arrivalRecords()->latest()->first(),
                'Surat Pengantar Datang',
            ),
            default => [null, false, 'Dokumen Tidak Dikenal', 'Tidak Valid', 'red'],
        };
    }

    /**
     * Evaluasi status record apakah aktif/expired dan kembalikan label tampilan.
     *
     * @return array{0: mixed, 1: bool, 2: string, 3: string, 4: string}
     */
    private function checkRecord(mixed $record, string $serviceLabel): array
    {
        if (! $record) {
            return [null, false, $serviceLabel, 'Dokumen Tidak Ditemukan', 'red'];
        }

        if ($record->status === 'ACTIVE') {
            $isExpired = $record->valid_until && Carbon::parse($record->valid_until)->isPast();

            if ($isExpired) {
                return [$record, false, $serviceLabel, 'Dokumen Kadaluarsa', 'orange'];
            }

            return [$record, true, $serviceLabel, 'Dokumen Sah & Aktif', 'green'];
        }

        $statusLabel = match ($record->status) {
            'PENDING' => 'Sedang Diproses',
            'REJECTED' => 'Ditolak',
            'EXPIRED' => 'Kadaluarsa',
            default => 'Tidak Valid',
        };

        return [$record, false, $serviceLabel, $statusLabel, 'red'];
    }
}

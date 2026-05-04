<?php

namespace App\Services;

use App\Http\Resources\Web\LeaderResource;
use App\Models\DistrictLeader;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\Village;
use App\Models\VillageLeader;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Generate a self-signed electronic certificate (TTE) for a given user ID.
     *
     * @throws \Exception
     */
    public function generateForUser(int $userId): array
    {
        $user = User::with(['village', 'district', 'activeVillageLeader', 'activeDistrictLeader'])->find($userId);

        if (! $user) {
            throw new \Exception("User dengan ID {$userId} tidak ditemukan.");
        }

        // Determine Official Data (Prioritize Leader Data)
        $leader = $user->activeVillageLeader ?? $user->activeDistrictLeader;
        $officialName = $leader ? $leader->name : $user->name;
        $nip = $leader ? $leader->nip : null;
        $rank = $leader ? $leader->rank : null;
        $unitName = $user->activeVillageLeader ? 'Kepala Desa' : ($user->activeDistrictLeader ? 'Camat' : ($user->role->name ?? 'Pejabat'));

        // Check for existing active certificate
        $existing = UserCertificate::where('user_id', $userId)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            $existing->update(['is_active' => false]);
        }

        $passphrase = Str::random(16);
        $filename = 'certs/cert_user_'.$userId.'_'.time().'.pfx';
        $fullPath = storage_path('app/private/'.$filename);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (! file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // OpenSSL Configuration
        $dn = [
            'countryName' => 'ID',
            'stateOrProvinceName' => 'Lampung',
            'localityName' => $user->village->district_name ?? ($user->district->name ?? 'Tulang Bawang Barat'),
            'organizationName' => 'MPP - Kabupaten Tulang Bawang Barat',
            'organizationalUnitName' => $user->village ? 'Pemerintah Desa '.$user->village->name : ($user->district ? 'Pemerintah Kecamatan '.$user->district->name : 'Pemerintah Kabupaten'),
            'commonName' => $officialName.($nip ? " (NIP. $nip)" : ''),
            'emailAddress' => $user->email ?? 'admin@desa.id',
        ];

        // Generate Private Key
        $privkey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        if (! $privkey) {
            throw new \Exception('Gagal membuat private key: '.openssl_error_string());
        }

        // Generate CSR
        $csr = openssl_csr_new($dn, $privkey, ['digest_alg' => 'sha256']);

        // Generate a unique serial number (based on timestamp)
        $serialNumber = time();

        // Generate self-signed certificate valid for 365 days
        $x509 = openssl_csr_sign($csr, null, $privkey, 365, ['digest_alg' => 'sha256'], $serialNumber);

        // Export to PKCS12
        $p12Exported = openssl_pkcs12_export_to_file($x509, $fullPath, $privkey, $passphrase);

        if (! $p12Exported) {
            throw new \Exception('Gagal mengekspor sertifikat ke PKCS12: '.openssl_error_string());
        }

        // Save to Database (Use relative path for portability)
        $certificate = UserCertificate::create([
            'user_id' => $userId,
            'certificate_path' => 'private/'.$filename,
            'passphrase' => $passphrase,
            'valid_until' => now()->addDays(365),
            'is_active' => true,
        ]);

        return [
            'success' => true,
            'message' => "Sertifikat berhasil dibuat untuk {$user->name} dan disimpan di {$fullPath}",
            'certificate' => $certificate,
        ];
    }

    /**
     * Get paginated and normalized leaders for the certificate list.
     */
    public function getPaginatedLeaders(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        // 1. Fetch Village Leaders
        $villageLeadersQuery = VillageLeader::with(['village', 'user.certificates' => function ($q) {
            $q->where('is_active', true)->latest();
        }])->where('is_active', true);

        if ($search) {
            $villageLeadersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhereHas('village', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $villageLeaders = $villageLeadersQuery->get();

        // 2. Fetch District Leaders
        $districtLeadersQuery = DistrictLeader::with(['district', 'user.certificates' => function ($q) {
            $q->where('is_active', true)->latest();
        }])->where('is_active', true);

        if ($search) {
            $districtLeadersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhereHas('district', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $districtLeaders = $districtLeadersQuery->get();

        // 3. Merge and Sort
        $allLeaders = $villageLeaders->concat($districtLeaders)->sortByDesc('created_at');

        // 4. Transform using LeaderResource and convert back to object for Blade
        $transformed = LeaderResource::collection($allLeaders)->resolve();
        $transformedCollection = collect($transformed)->map(fn ($item) => (object) $item);

        // 5. Manual Pagination
        $request = request();
        $page = $request->input('page', 1);
        $items = $transformedCollection->forPage($page, $perPage);

        return new LengthAwarePaginator(
            $items,
            $transformedCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}

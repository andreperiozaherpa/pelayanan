<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * @property string $nik
 * @property string $nama_lengkap
 * @property Carbon $tgl_lahir
 * @property string $alamat_desa
 * @property string|null $kontak
 * @property int $desa_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, PovertyRecord> $povertyRecords
 * @property-read int|null $poverty_records_count
 * @property-read Collection<int, ServiceRequest> $serviceRequests
 * @property-read int|null $service_requests_count
 * @property-read Collection<int, VerificationLog> $verificationLogs
 * @property-read int|null $verification_logs_count
 * @property-read Village $village
 *
 * @method static \Database\Factories\CitizenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereAlamatDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Citizen whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
use Illuminate\Support\Carbon;

class Citizen extends Model
{
    use Auditable, HasFactory;

    protected $primaryKey = 'nik';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'tgl_lahir',
        'alamat_desa',
        'kontak',
        'desa_id',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'alamat_desa' => 'encrypted', // Blueprint requirement: Encrypted at application level
    ];

    public function povertyRecords(): HasMany
    {
        return $this->hasMany(PovertyRecord::class, 'citizen_nik', 'nik');
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'citizen_nik', 'nik');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'desa_id', 'id');
    }

    public function verificationLogs(): HasMany
    {
        return $this->hasMany(VerificationLog::class, 'nik', 'nik');
    }
}

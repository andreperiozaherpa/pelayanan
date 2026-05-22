<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $citizen_nik
 * @property string $status
 * @property string $income_range
 * @property Carbon $valid_from
 * @property Carbon $valid_until
 * @property int|null $verified_by
 * @property string|null $source
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Citizen $citizen
 * @property-read User|null $verifiedBy
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord active()
 * @method static \Database\Factories\PovertyRecordFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereCitizenNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereIncomeRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereValidUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PovertyRecord whereVerifiedBy($value)
 *
 * @mixin \Eloquent
 */
class PovertyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_nik',
        'status',
        'signed_pdf_path',
        'letter_number',
        'income_range',
        'valid_from',
        'valid_until',
        'verified_by',
        'source',
    ];

    protected $casts = [
        'income_range' => 'encrypted', // Blueprint requirement: Encrypted at application level
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'citizen_nik', 'nik');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include active records.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE')
            ->where('valid_until', '>=', now());
    }
}

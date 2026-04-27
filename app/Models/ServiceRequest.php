<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $citizen_nik
 * @property string $service_type
 * @property string $status
 * @property int $front_office_user_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Citizen $citizen
 * @property-read User $frontOfficeUser
 * @property-read VerificationLog|null $verificationLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereCitizenNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereFrontOfficeUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_nik',
        'service_type',
        'status',
        'front_office_user_id',
        'notes',
    ];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'citizen_nik', 'nik');
    }

    public function frontOfficeUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'front_office_user_id');
    }

    public function verificationLog(): HasOne
    {
        return $this->hasOne(VerificationLog::class, 'request_id');
    }
}

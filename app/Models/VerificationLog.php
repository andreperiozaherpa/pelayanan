<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $request_id
 * @property int|null $user_id
 * @property string|null $nik
 * @property string $method
 * @property string $result
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon $timestamp
 * @property-read Citizen|null $citizen
 * @property-read ServiceRequest|null $serviceRequest
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationLog whereUserId($value)
 *
 * @mixin \Eloquent
 */
class VerificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id',
        'nik',
        'method',
        'result',
        'ip_address',
        'user_agent',
        'timestamp',
    ];

    public $timestamps = false; // Blueprint says specific timestamp column

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'nik', 'nik');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

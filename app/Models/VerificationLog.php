<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

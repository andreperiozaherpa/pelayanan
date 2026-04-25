<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

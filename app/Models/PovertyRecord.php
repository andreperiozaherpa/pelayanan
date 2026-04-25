<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PovertyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_nik',
        'status',
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

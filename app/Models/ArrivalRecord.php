<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArrivalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_nik',
        'status',
        'previous_address',
        'arrival_date',
        'recorded_by',
        'notes',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'previous_address' => 'encrypted',
    ];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'citizen_nik', 'nik');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

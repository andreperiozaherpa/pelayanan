<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeathRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_nik',
        'status',
        'date_of_death',
        'cause_of_death',
        'place_of_death',
        'signed_pdf_path',
        'verified_by',
        'issued_at',
        'source',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_death' => 'date',
            'issued_at' => 'datetime',
        ];
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'citizen_nik', 'nik');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

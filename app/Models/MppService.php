<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MppService extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'fields',
        'is_active',
        'anjungan_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(MppServiceRequest::class, 'mpp_service_id');
    }

    public function anjungan(): BelongsTo
    {
        return $this->belongsTo(Anjungan::class, 'anjungan_id');
    }
}

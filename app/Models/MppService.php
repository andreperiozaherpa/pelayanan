<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MppService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'fields',
        'is_active',
        'opd_id',
        'gerai_id',
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

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class, 'service_id');
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function gerai(): BelongsTo
    {
        return $this->belongsTo(Gerai::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MapLocation extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'region_id',
        'latitude',
        'longitude',
        'address',
        'description',
        'photo',
        'metadata',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'metadata' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MapLocationCategory::class, 'category_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(MapRegion::class, 'region_id');
    }

    public function restrictions(): HasMany
    {
        return $this->hasMany(MapLocationRestriction::class, 'location_id');
    }
}

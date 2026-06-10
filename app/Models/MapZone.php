<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapZone extends Model
{
    protected $fillable = [
        'name',
        'zone_type_id',
        'region_id',
        'geojson',
        'description',
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
            'geojson' => 'array',
            'metadata' => 'array',
        ];
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MapZoneType::class, 'zone_type_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(MapRegion::class, 'region_id');
    }
}

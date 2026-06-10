<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapLocationRestriction extends Model
{
    protected $fillable = [
        'location_id',
        'geojson',
        'restricted_activities',
        'notes',
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
            'restricted_activities' => 'array',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(MapLocation::class, 'location_id');
    }
}

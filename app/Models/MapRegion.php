<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MapRegion extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'parent_id',
        'geojson',
        'color',
        'area_km2',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MapRegion::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MapRegion::class, 'parent_id');
    }

    public function zones(): HasMany
    {
        return $this->hasMany(MapZone::class, 'region_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(MapLocation::class, 'region_id');
    }

    public function scopeKabupaten($query)
    {
        return $query->where('level', 'kabupaten');
    }

    public function scopeKecamatan($query)
    {
        return $query->where('level', 'kecamatan');
    }

    public function scopeDesa($query)
    {
        return $query->where('level', 'desa');
    }
}

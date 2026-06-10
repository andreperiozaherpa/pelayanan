<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MapZoneType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon',
        'description',
    ];

    public function zones(): HasMany
    {
        return $this->hasMany(MapZone::class, 'zone_type_id');
    }
}

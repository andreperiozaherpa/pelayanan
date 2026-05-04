<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'regency_name',
    ];

    /**
     * Get the leaders for the district.
     */
    public function leaders(): HasMany
    {
        return $this->hasMany(DistrictLeader::class);
    }

    public function activeLeader(): HasOne
    {
        return $this->hasOne(DistrictLeader::class)->where('is_active', true);
    }

    /**
     * Get the villages for the district.
     */
    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }
}

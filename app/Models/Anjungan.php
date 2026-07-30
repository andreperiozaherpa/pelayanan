<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anjungan extends Model
{
    protected $table = 'mpp_anjungans';

    protected $fillable = [
        'code',
        'name',
        'location',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function services(): HasMany
    {
        return $this->hasMany(MppService::class, 'anjungan_id');
    }
}

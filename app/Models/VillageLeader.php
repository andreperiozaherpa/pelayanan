<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageLeader extends Model
{
    protected $fillable = [
        'village_id',
        'user_id',
        'name',
        'nip',
        'rank',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HouseholdCard extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_kk';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'no_kk',
        'head_name',
        'address',
        'rt',
        'rw',
        'village_id',
    ];

    protected $casts = [
        'address' => 'encrypted',
    ];

    /**
     * Get the citizens belonging to this household.
     */
    public function citizens(): HasMany
    {
        return $this->hasMany(Citizen::class, 'household_card_id', 'no_kk');
    }

    /**
     * Get the village where this household is located.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}

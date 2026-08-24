<?php

namespace App\Models;

use Database\Factories\OpdFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opd extends Model
{
    /** @use HasFactory<OpdFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get all users that belong to this OPD.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all MPP gerai that belong to this instansi.
     */
    public function gerais(): HasMany
    {
        return $this->hasMany(Gerai::class);
    }

    /**
     * Get all services owned by this instansi.
     */
    public function services(): HasMany
    {
        return $this->hasMany(MppService::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'district_name',
    ];

    public function citizens(): HasMany
    {
        return $this->hasMany(Citizen::class, 'desa_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'desa_id', 'id');
    }
}

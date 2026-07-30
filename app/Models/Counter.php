<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model
{
    protected $table = 'mpp_counters';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mpp_counter_user')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function activeUserAssignments(): HasMany
    {
        return $this->hasMany(CounterUser::class)->where('is_active', true);
    }
}

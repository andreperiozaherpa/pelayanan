<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model
{
    use HasFactory;

    protected $table = 'mpp_counters';

    protected $fillable = [
        'gerai_id',
        'code',
        'name',
        'location',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function gerai(): BelongsTo
    {
        return $this->belongsTo(Gerai::class);
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

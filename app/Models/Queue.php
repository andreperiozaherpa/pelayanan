<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    protected $table = 'mpp_queues';

    protected $fillable = [
        'number',
        'counter_id',
        'counter_name',
        'status',
        'called_at',
        'done_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'called_at' => 'datetime',
            'done_at' => 'datetime',
        ];
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }
}

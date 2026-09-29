<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueRealtimeEvent extends Model
{
    protected $fillable = ['event', 'payload', 'attempts', 'next_attempt_at', 'dispatched_at'];

    protected function casts(): array
    {
        return ['payload' => 'array', 'next_attempt_at' => 'datetime', 'dispatched_at' => 'datetime'];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MppTicketIdempotencyKey extends Model
{
    protected $fillable = [
        'operation',
        'key',
        'payload_hash',
        'service_id',
        'queue_id',
        'mpp_service_request_id',
    ];
}

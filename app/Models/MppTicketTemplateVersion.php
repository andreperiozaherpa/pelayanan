<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MppTicketTemplateVersion extends Model
{
    protected $fillable = ['mpp_ticket_template_id', 'version', 'layout', 'checksum', 'published_by', 'published_at'];

    protected function casts(): array
    {
        return ['layout' => 'array', 'published_at' => 'datetime'];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MppTicketTemplate::class, 'mpp_ticket_template_id');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}

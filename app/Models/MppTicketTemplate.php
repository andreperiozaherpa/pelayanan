<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MppTicketTemplate extends Model
{
    protected $fillable = ['name', 'draft_layout', 'active_version_id'];

    protected function casts(): array
    {
        return ['draft_layout' => 'array'];
    }

    public function activeVersion(): BelongsTo
    {
        return $this->belongsTo(MppTicketTemplateVersion::class, 'active_version_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(MppTicketTemplateVersion::class);
    }
}

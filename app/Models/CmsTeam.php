<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsTeam extends Model
{
    protected $table = 'cms_teams';

    protected $fillable = [
        'name',
        'position',
        'image',
        'social_links',
        'order',
        'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];
}

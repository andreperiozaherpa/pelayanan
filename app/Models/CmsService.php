<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsService extends Model
{
    use HasFactory;

    protected $table = 'cms_services';

    protected $fillable = [
        'icon',
        'name',
        'summary',
        'description',
        'link_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];
}

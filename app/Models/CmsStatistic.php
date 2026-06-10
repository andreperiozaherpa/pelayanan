<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsStatistic extends Model
{
    use HasFactory;

    protected $table = 'cms_statistics';

    protected $fillable = [
        'icon',
        'label',
        'value',
        'order',
        'is_active',
    ];

    protected $casts = [
        'value' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];
}

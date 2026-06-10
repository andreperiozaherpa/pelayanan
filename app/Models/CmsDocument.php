<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsDocument extends Model
{
    use HasFactory;

    protected $table = 'cms_documents';

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'type',
        'downloads_count',
        'is_active',
    ];

    protected $casts = [
        'downloads_count' => 'integer',
        'is_active' => 'boolean',
    ];
}

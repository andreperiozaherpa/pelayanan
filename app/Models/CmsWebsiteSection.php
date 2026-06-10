<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsWebsiteSection extends Model
{
    use HasFactory;

    protected $table = 'cms_website_sections';

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'content',
        'image',
        'button_text',
        'button_url',
    ];
}

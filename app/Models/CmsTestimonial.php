<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsTestimonial extends Model
{
    protected $table = 'cms_testimonials';

    protected $fillable = [
        'name',
        'position',
        'company',
        'content',
        'rating',
        'avatar',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
    ];
}

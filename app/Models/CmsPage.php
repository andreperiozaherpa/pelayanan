<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPage extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'cms_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'template',
        'status',
    ];

    public function seo(): MorphOne
    {
        return $this->morphOne(CmsSeo::class, 'seoable');
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'published';
    }
}

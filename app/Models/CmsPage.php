<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    protected static function booted(): void
    {
        static::saved(function (CmsPage $page) {
            if ($page->isDirty('slug')) {
                $menu = CmsMenu::where('cms_page_id', $page->id)->first();
                if ($menu) {
                    $menu->url = CmsMenu::generateUrlForPage($menu->parent_id, $page->slug);
                    $menu->save();
                }
            }
        });
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(CmsSeo::class, 'seoable');
    }

    public function menu(): HasOne
    {
        return $this->hasOne(CmsMenu::class, 'cms_page_id');
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'published';
    }
}

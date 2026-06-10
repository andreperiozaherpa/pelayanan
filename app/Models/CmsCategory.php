<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsCategory extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'cms_categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
    ];

    protected function getSlugSourceColumn(): string
    {
        return 'name';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CmsCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CmsCategory::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(CmsArticle::class, 'category_id');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(CmsPortfolio::class, 'category_id');
    }
}

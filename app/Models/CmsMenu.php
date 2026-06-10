<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsMenu extends Model
{
    use HasFactory;

    protected $table = 'cms_menus';

    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'icon',
        'description',
        'target',
        'order',
        'is_active',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CmsMenu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CmsMenu::class, 'parent_id')->orderBy('order');
    }
}

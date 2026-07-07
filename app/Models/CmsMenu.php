<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CmsMenu extends Model
{
    use HasFactory;

    protected $table = 'cms_menus';

    protected $fillable = [
        'parent_id',
        'cms_page_id',
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
        'cms_page_id' => 'integer',
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

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    /**
     * Generate dynamic routing URL based on parent.
     */
    public static function generateUrlForPage(?int $parentId, string $pageSlug): string
    {
        $pageSlug = ltrim($pageSlug, '/');

        if ($parentId) {
            $parent = self::find($parentId);
            if ($parent) {
                // Jika parent memiliki URL yang valid (bukan # atau empty dan diawali /)
                if (! empty($parent->url) && $parent->url !== '#' && str_starts_with($parent->url, '/')) {
                    return rtrim($parent->url, '/').'/'.$pageSlug;
                }

                // Jika parent memiliki URL '#' atau empty, tentukan berdasarkan title parent
                $title = strtolower($parent->title);
                $keywords = ['profil', 'pelayanan', 'informasi', 'investasi', 'ppid', 'kontak'];
                foreach ($keywords as $keyword) {
                    if (str_contains($title, $keyword)) {
                        return "/{$keyword}/{$pageSlug}";
                    }
                }

                // Fallback menggunakan slug dari title parent
                $parentSlug = Str::slug($parent->title);

                return "/{$parentSlug}/{$pageSlug}";
            }
        }

        // Jika tidak ada parent
        return "/profil/{$pageSlug}";
    }
}

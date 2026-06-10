<?php

namespace App\Services;

use App\Models\CmsSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsSeoService
{
    /**
     * Get SEO metadata for a model or generic page.
     */
    public function getMetadata(?Model $model = null, array $overrides = []): array
    {
        $siteName = $this->getSetting('site_name', 'SIBERUGO');
        $siteDescription = $this->getSetting('site_description', 'Sistem Informasi Tata Ruang dan Pelayanan Publik');
        $siteKeywords = $this->getSetting('site_keywords', 'siberugo, tata ruang, pelayanan');
        $siteLogo = $this->getSetting('site_logo', null);

        // Standard Fallbacks
        $title = $overrides['title'] ?? null;
        $description = $overrides['description'] ?? null;
        $keywords = $overrides['keywords'] ?? null;
        $ogImage = $overrides['og_image'] ?? null;
        $canonical = $overrides['canonical_url'] ?? request()->url();

        if ($model) {
            // Check polymorphic relationship
            $seo = method_exists($model, 'seo') ? $model->seo : null;

            if ($seo) {
                $title = $seo->meta_title ?: $title;
                $description = $seo->meta_description ?: $description;
                $keywords = $seo->meta_keywords ?: $keywords;
                $ogImage = $seo->og_image ?: $ogImage;
                $canonical = $seo->canonical_url ?: $canonical;
            }

            // Secondary Model Fallbacks
            if (! $title) {
                $title = $model->title ?? $model->name ?? null;
            }

            if (! $description) {
                if (! empty($model->excerpt)) {
                    $description = $model->excerpt;
                } elseif (! empty($model->content)) {
                    $description = Str::limit(strip_tags($model->content), 160);
                } elseif (! empty($model->description)) {
                    $description = Str::limit(strip_tags($model->description), 160);
                }
            }

            if (! $ogImage && ! empty($model->featured_image)) {
                $ogImage = $model->featured_image;
            }
        }

        // Final settings fallback
        $title = $title ? $title.' | '.$siteName : $siteName;
        $description = $description ?: $siteDescription;
        $keywords = $keywords ?: $siteKeywords;
        $ogImage = $ogImage ? asset('storage/'.$ogImage) : ($siteLogo ? asset('storage/'.$siteLogo) : null);

        return [
            'meta_title' => $title,
            'meta_description' => $description,
            'meta_keywords' => $keywords,
            'og_image' => $ogImage,
            'canonical_url' => $canonical,
            'site_name' => $siteName,
        ];
    }

    /**
     * Retrieve setting helper.
     */
    protected function getSetting(string $key, ?string $default = null): ?string
    {
        return CmsSetting::where('key', $key)->value('value') ?? $default;
    }
}

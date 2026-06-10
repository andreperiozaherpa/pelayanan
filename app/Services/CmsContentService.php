<?php

namespace App\Services;

use App\Models\CmsArticle;
use App\Models\CmsBanner;
use App\Models\CmsFaq;
use App\Models\CmsPage;
use App\Models\CmsSetting;
use App\Models\CmsTeam;
use App\Models\CmsTestimonial;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CmsContentService
{
    /**
     * Cache TTL in seconds (1 hour).
     */
    protected int $cacheTtl = 3600;

    /**
     * Get a page by slug with SEO metadata cached.
     */
    public function getPage(string $slug): ?CmsPage
    {
        $cacheKey = 'cms_page_'.$slug;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($slug) {
            return CmsPage::with('seo')
                ->where('slug', $slug)
                ->where('status', 'published')
                ->first();
        });
    }

    /**
     * Get paginated articles with category and SEO cached.
     */
    public function getArticles(int $perPage = 10, ?int $categoryId = null, ?string $search = null): LengthAwarePaginator
    {
        // Don't cache complex query/search combinations directly to avoid cache bloat,
        // but eager load relationships to avoid N+1 queries.
        $query = CmsArticle::with(['category', 'seo', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get article by slug cached.
     */
    public function getArticle(string $slug): ?CmsArticle
    {
        $cacheKey = 'cms_article_'.$slug;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($slug) {
            return CmsArticle::with(['category', 'seo', 'author'])
                ->where('slug', $slug)
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->first();
        });
    }

    /**
     * Get active banners cached.
     */
    public function getBanners(): Collection
    {
        return Cache::remember('cms_banners', $this->cacheTtl, function () {
            return CmsBanner::where('is_active', true)
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get active FAQs cached.
     */
    public function getFaqs(): Collection
    {
        return Cache::remember('cms_faqs', $this->cacheTtl, function () {
            return CmsFaq::where('is_active', true)
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get active testimonials cached.
     */
    public function getTestimonials(): Collection
    {
        return Cache::remember('cms_testimonials', $this->cacheTtl, function () {
            return CmsTestimonial::where('is_active', true)
                ->latest()
                ->get();
        });
    }

    /**
     * Get active teams cached.
     */
    public function getTeams(): Collection
    {
        return Cache::remember('cms_teams', $this->cacheTtl, function () {
            return CmsTeam::where('is_active', true)
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get website settings grouped by group name cached.
     */
    public function getSettingsGroup(string $group): Collection
    {
        $cacheKey = 'cms_settings_group_'.$group;

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($group) {
            return CmsSetting::where('group', $group)->pluck('value', 'key');
        });
    }

    /**
     * Invalidate specific caches (called during CRUD updates).
     */
    public function clearCacheFor(string $type, ?string $identifier = null): void
    {
        switch ($type) {
            case 'page':
                if ($identifier) {
                    Cache::forget('cms_page_'.$identifier);
                }
                break;

            case 'article':
                if ($identifier) {
                    Cache::forget('cms_article_'.$identifier);
                }
                break;

            case 'banners':
                Cache::forget('cms_banners');
                break;

            case 'faqs':
                Cache::forget('cms_faqs');
                break;

            case 'testimonials':
                Cache::forget('cms_testimonials');
                break;

            case 'teams':
                Cache::forget('cms_teams');
                break;

            case 'settings':
                if ($identifier) {
                    Cache::forget('cms_settings_group_'.$identifier);
                }
                break;
        }
    }
}

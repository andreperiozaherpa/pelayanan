<?php

namespace App\Repositories\Eloquent;

use App\Models\CmsArticle;
use App\Models\CmsFaq;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsPortfolio;
use App\Models\CmsService;
use App\Models\CmsSetting;
use App\Models\CmsStatistic;
use App\Models\CmsTeam;
use App\Models\CmsTestimonial;
use App\Models\CmsWebsiteSection;
use App\Models\CmsWhyChooseUs;
use App\Repositories\Contracts\LandingPageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    public function getSectionByKey(string $key): ?CmsWebsiteSection
    {
        return CmsWebsiteSection::where('key', $key)->first();
    }

    public function getActiveStatistics(): Collection
    {
        return CmsStatistic::where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'icon', 'label', 'value']);
    }

    public function getAboutPage(): ?CmsPage
    {
        return CmsPage::where('slug', 'about-us')
            ->where('status', 'published')
            ->select('id', 'title', 'content')
            ->first();
    }

    public function getActiveServices(int $limit = 6): Collection
    {
        return CmsService::where('is_active', true)
            ->orderBy('order')
            ->take($limit)
            ->get(['id', 'icon', 'name', 'summary', 'link_url']);
    }

    public function getActiveWhyChooseUs(): Collection
    {
        return CmsWhyChooseUs::where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'icon', 'title', 'description']);
    }

    public function getLatestPortfolios(int $limit = 6): Collection
    {
        return CmsPortfolio::where('is_active', true)
            ->with('category:id,name')
            ->latest()
            ->take($limit)
            ->get(['id', 'thumbnail', 'name', 'client', 'category_id']);
    }

    public function getActiveTeamMembers(): Collection
    {
        return CmsTeam::where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'position', 'image', 'social_links']);
    }

    public function getActiveTestimonials(): Collection
    {
        return CmsTestimonial::where('is_active', true)
            ->get(['id', 'name', 'position', 'company', 'avatar', 'rating', 'content']);
    }

    public function getLatestArticles(int $limit = 6): Collection
    {
        return CmsArticle::where('status', 'published')
            ->with('category:id,name,slug')
            ->latest('published_at')
            ->take($limit)
            ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'category_id', 'published_at']);
    }

    public function getActiveFaqs(): Collection
    {
        return CmsFaq::where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'question', 'answer']);
    }

    /**
     * @return array<string, string|null>
     */
    public function getWebsiteSettings(): array
    {
        $settings = CmsSetting::all(['key', 'value'])->pluck('value', 'key');

        return $settings->toArray();
    }

    public function getActiveMenus(): Collection
    {
        return CmsMenu::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
    }
}

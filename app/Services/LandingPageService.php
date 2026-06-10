<?php

namespace App\Services;

use App\Repositories\Contracts\LandingPageRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LandingPageService
{
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private readonly LandingPageRepositoryInterface $repository
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function getPageData(): array
    {
        return [
            'hero' => $this->getHero(),
            'statistics' => $this->getStatistics(),
            'about' => $this->getAbout(),
            'vision' => $this->getSection('vision'),
            'mission' => $this->getSection('mission'),
            'services' => $this->getServices(),
            'whyChooseUs' => $this->getWhyChooseUs(),
            'portfolios' => $this->getPortfolios(),
            'team' => $this->getTeam(),
            'testimonials' => $this->getTestimonials(),
            'articles' => $this->getLatestArticles(),
            'faqs' => $this->getFaqs(),
            'cta' => $this->getSection('cta'),
            'settings' => $this->getSettings(),
            'menus' => $this->getMenus(),
        ];
    }

    private function getHero(): mixed
    {
        return Cache::remember(
            'landing.section.hero',
            self::CACHE_TTL,
            fn () => $this->repository->getSectionByKey('hero')
        );
    }

    private function getSection(string $key): mixed
    {
        return Cache::remember(
            "landing.section.{$key}",
            self::CACHE_TTL,
            fn () => $this->repository->getSectionByKey($key)
        );
    }

    private function getStatistics(): Collection
    {
        return Cache::remember(
            'landing.statistics',
            self::CACHE_TTL,
            fn () => $this->repository->getActiveStatistics()
        );
    }

    private function getAbout(): mixed
    {
        return Cache::remember(
            'landing.about',
            self::CACHE_TTL,
            fn () => $this->repository->getAboutPage()
        );
    }

    private function getServices(): Collection
    {
        return Cache::remember(
            'landing.services',
            self::CACHE_TTL,
            fn () => $this->repository->getActiveServices(6)
        );
    }

    private function getWhyChooseUs(): Collection
    {
        return Cache::remember(
            'landing.why_choose_us',
            self::CACHE_TTL,
            fn () => $this->repository->getActiveWhyChooseUs()
        );
    }

    private function getPortfolios(): Collection
    {
        return Cache::remember(
            'landing.portfolios',
            self::CACHE_TTL,
            fn () => $this->repository->getLatestPortfolios(6)
        );
    }

    private function getTeam(): Collection
    {
        return Cache::remember(
            'landing.team',
            self::CACHE_TTL,
            fn () => $this->repository->getActiveTeamMembers()
        );
    }

    private function getTestimonials(): Collection
    {
        return Cache::remember(
            'landing.testimonials',
            self::CACHE_TTL,
            fn () => $this->repository->getActiveTestimonials()
        );
    }

    private function getLatestArticles(): Collection
    {
        return Cache::remember(
            'landing.articles',
            self::CACHE_TTL,
            fn () => $this->repository->getLatestArticles(6)
        );
    }

    private function getFaqs(): Collection
    {
        return Cache::remember(
            'landing.faqs',
            self::CACHE_TTL,
            fn () => $this->repository->getActiveFaqs()
        );
    }

    /**
     * @return array<string, string|null>
     */
    private function getSettings(): array
    {
        return Cache::remember(
            'landing.settings',
            self::CACHE_TTL,
            fn () => $this->repository->getWebsiteSettings()
        );
    }

    private function getMenus(): Collection
    {
        return Cache::remember(
            'landing.menus',
            self::CACHE_TTL,
            fn () => $this->repository->getActiveMenus()
        );
    }

    /**
     * Flush all landing page cache keys. Called from CMS model observers.
     */
    public static function flushCache(): void
    {
        $keys = [
            'landing.section.hero',
            'landing.section.vision',
            'landing.section.mission',
            'landing.section.cta',
            'landing.statistics',
            'landing.about',
            'landing.services',
            'landing.why_choose_us',
            'landing.portfolios',
            'landing.team',
            'landing.testimonials',
            'landing.articles',
            'landing.faqs',
            'landing.settings',
            'landing.menus',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}

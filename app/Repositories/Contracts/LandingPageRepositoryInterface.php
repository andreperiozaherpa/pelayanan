<?php

namespace App\Repositories\Contracts;

use App\Models\CmsArticle;
use App\Models\CmsFaq;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsPortfolio;
use App\Models\CmsService;
use App\Models\CmsStatistic;
use App\Models\CmsTeam;
use App\Models\CmsTestimonial;
use App\Models\CmsWebsiteSection;
use App\Models\CmsWhyChooseUs;
use Illuminate\Database\Eloquent\Collection;

interface LandingPageRepositoryInterface
{
    public function getSectionByKey(string $key): ?CmsWebsiteSection;

    /** @return Collection<int, CmsStatistic> */
    public function getActiveStatistics(): Collection;

    public function getAboutPage(): ?CmsPage;

    /** @return Collection<int, CmsService> */
    public function getActiveServices(int $limit = 6): Collection;

    /** @return Collection<int, CmsWhyChooseUs> */
    public function getActiveWhyChooseUs(): Collection;

    /** @return Collection<int, CmsPortfolio> */
    public function getLatestPortfolios(int $limit = 6): Collection;

    /** @return Collection<int, CmsTeam> */
    public function getActiveTeamMembers(): Collection;

    /** @return Collection<int, CmsTestimonial> */
    public function getActiveTestimonials(): Collection;

    /** @return Collection<int, CmsArticle> */
    public function getLatestArticles(int $limit = 6): Collection;

    /** @return Collection<int, CmsFaq> */
    public function getActiveFaqs(): Collection;

    /**
     * @return array<string, string|null>
     */
    public function getWebsiteSettings(): array;

    /** @return Collection<int, CmsMenu> */
    public function getActiveMenus(): Collection;
}

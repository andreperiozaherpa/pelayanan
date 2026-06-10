<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsSetting;
use App\Services\LandingPageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function __construct(
        private readonly LandingPageService $landingPageService
    ) {}

    public function index(): View
    {
        $data = $this->landingPageService->getPageData();

        return view('pages.landing', $data);
    }

    public function showPage(string $section, string $slug): View
    {
        $page = CmsPage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $menus = Cache::remember(
            'landing.menus',
            3600,
            fn () => CmsMenu::with('children')
                ->where('parent_id', null)
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
        );

        $settings = Cache::remember(
            'landing.settings',
            3600,
            fn () => CmsSetting::pluck('value', 'key')->toArray()
        );

        return view('pages.show', compact('page', 'menus', 'settings'));
    }
}

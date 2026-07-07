<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsMenuRequest;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Services\CmsContentService;
use Illuminate\Support\Facades\Gate;

class CmsMenuController extends Controller
{
    public function __construct(
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.menus.view');

        // Fetch parent menus with their children and page relations
        $menus = CmsMenu::with(['children', 'page', 'parent'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->paginate(15);

        return view('cms.menus.index', compact('menus'));
    }

    public function create()
    {
        Gate::authorize('cms.menus.create');

        $parentMenus = CmsMenu::whereNull('parent_id')->orderBy('order')->get();
        $pages = CmsPage::orderBy('title')->get();

        return view('cms.menus.create', compact('parentMenus', 'pages'));
    }

    public function store(CmsMenuRequest $request)
    {
        Gate::authorize('cms.menus.create');

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if (! empty($validated['cms_page_id'])) {
            $page = CmsPage::findOrFail($validated['cms_page_id']);
            $validated['url'] = $this->generateMenuUrl($validated['parent_id'] ?? null, $page);
        }

        $menu = CmsMenu::create($validated);

        Audit::log('CMS_CREATE_MENU', $menu, $menu->toArray());

        // Also clear caching since we observe saved events

        return redirect()->route('cms-menus.index')
            ->with('success', 'Menu navigasi berhasil ditambahkan.');
    }

    public function edit(CmsMenu $cmsMenu)
    {
        Gate::authorize('cms.menus.edit');

        $parentMenus = CmsMenu::whereNull('parent_id')
            ->where('id', '!=', $cmsMenu->id)
            ->orderBy('order')
            ->get();
        $pages = CmsPage::orderBy('title')->get();

        return view('cms.menus.edit', compact('cmsMenu', 'parentMenus', 'pages'));
    }

    public function update(CmsMenuRequest $request, CmsMenu $cmsMenu)
    {
        Gate::authorize('cms.menus.edit');

        $oldValue = $cmsMenu->toArray();
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        // Reset parent_id to null if empty
        if (! isset($validated['parent_id']) || $validated['parent_id'] === '') {
            $validated['parent_id'] = null;
        }

        if (! empty($validated['cms_page_id'])) {
            $page = CmsPage::findOrFail($validated['cms_page_id']);
            $validated['url'] = $this->generateMenuUrl($validated['parent_id'], $page);
        } else {
            $validated['cms_page_id'] = null;
        }

        $cmsMenu->update($validated);

        Audit::log('CMS_UPDATE_MENU', $cmsMenu, $cmsMenu->toArray(), $oldValue);

        return redirect()->route('cms-menus.index')
            ->with('success', 'Menu navigasi berhasil diperbarui.');
    }

    public function destroy(CmsMenu $cmsMenu)
    {
        Gate::authorize('cms.menus.delete');

        $oldValue = $cmsMenu->toArray();
        $cmsMenu->delete();

        Audit::log('CMS_DELETE_MENU', $cmsMenu, null, $oldValue);

        return redirect()->route('cms-menus.index')
            ->with('success', 'Menu navigasi berhasil dihapus.');
    }

    protected function generateMenuUrl(?int $parentId, CmsPage $page): string
    {
        return CmsMenu::generateUrlForPage($parentId, $page->slug);
    }
}

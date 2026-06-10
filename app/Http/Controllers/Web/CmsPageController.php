<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsPageRequest;
use App\Models\CmsPage;
use App\Services\CmsContentService;
use Illuminate\Support\Facades\Gate;

class CmsPageController extends Controller
{
    public function __construct(
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.pages.view');

        $pages = CmsPage::latest()->paginate(10);

        return view('cms.pages.index', compact('pages'));
    }

    public function create()
    {
        Gate::authorize('cms.pages.create');

        return view('cms.pages.create');
    }

    public function store(CmsPageRequest $request)
    {
        Gate::authorize('cms.pages.create');

        $validated = $request->validated();
        $page = CmsPage::create($validated);

        // Save SEO
        $page->seo()->create([
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'canonical_url' => $request->canonical_url,
        ]);

        Audit::log('CMS_CREATE_PAGE', $page, $page->load('seo')->toArray());
        $this->contentService->clearCacheFor('page', $page->slug);

        return redirect()->route('cms-pages.index')
            ->with('success', "Halaman {$page->title} berhasil disimpan.");
    }

    public function edit(CmsPage $cmsPage)
    {
        Gate::authorize('cms.pages.edit');

        $cmsPage->load('seo');

        return view('cms.pages.edit', compact('cmsPage'));
    }

    public function update(CmsPageRequest $request, CmsPage $cmsPage)
    {
        Gate::authorize('cms.pages.edit');

        $oldValue = $cmsPage->load('seo')->toArray();
        $validated = $request->validated();

        $cmsPage->update($validated);

        // Update/create SEO
        $cmsPage->seo()->updateOrCreate([], [
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'canonical_url' => $request->canonical_url,
        ]);

        Audit::log('CMS_UPDATE_PAGE', $cmsPage, $cmsPage->fresh()->load('seo')->toArray(), $oldValue);

        $this->contentService->clearCacheFor('page', $oldValue['slug']);
        $this->contentService->clearCacheFor('page', $cmsPage->slug);

        return redirect()->route('cms-pages.index')
            ->with('success', "Halaman {$cmsPage->title} berhasil diperbarui.");
    }

    public function destroy(CmsPage $cmsPage)
    {
        Gate::authorize('cms.pages.delete');

        $oldValue = $cmsPage->load('seo')->toArray();
        $cmsPage->delete();

        Audit::log('CMS_DELETE_PAGE', $cmsPage, null, $oldValue);
        $this->contentService->clearCacheFor('page', $cmsPage->slug);

        return redirect()->route('cms-pages.index')
            ->with('success', "Halaman {$cmsPage->title} berhasil dihapus.");
    }
}

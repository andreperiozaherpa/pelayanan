<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsBannerRequest;
use App\Models\CmsBanner;
use App\Services\CmsContentService;
use App\Services\CmsMediaService;
use Illuminate\Support\Facades\Gate;

class CmsBannerController extends Controller
{
    public function __construct(
        protected CmsMediaService $mediaService,
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.banners.view');

        $banners = CmsBanner::orderBy('order')->paginate(10);

        return view('cms.banners.index', compact('banners'));
    }

    public function create()
    {
        Gate::authorize('cms.banners.create');

        return view('cms.banners.create');
    }

    public function store(CmsBannerRequest $request)
    {
        Gate::authorize('cms.banners.create');

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('image')) {
            $validated['image_path'] = $request->input('image');
        } elseif ($request->hasFile('image_file')) {
            $validated['image_path'] = $this->mediaService->upload(
                $request->file('image_file'),
                'banners',
                1920 // max banner width
            );
        }

        $banner = CmsBanner::create($validated);

        Audit::log('CMS_CREATE_BANNER', $banner, $banner->toArray());
        $this->contentService->clearCacheFor('banners');

        return redirect()->route('cms-banners.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(CmsBanner $cmsBanner)
    {
        Gate::authorize('cms.banners.edit');

        return view('cms.banners.edit', compact('cmsBanner'));
    }

    public function update(CmsBannerRequest $request, CmsBanner $cmsBanner)
    {
        Gate::authorize('cms.banners.edit');

        $oldValue = $cmsBanner->toArray();
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('image') && $request->input('image') !== $cmsBanner->image_path) {
            $this->mediaService->delete($cmsBanner->image_path);
            $validated['image_path'] = $request->input('image');
        } elseif ($request->hasFile('image_file')) {
            $this->mediaService->delete($cmsBanner->image_path);
            $validated['image_path'] = $this->mediaService->upload(
                $request->file('image_file'),
                'banners',
                1920
            );
        }

        $cmsBanner->update($validated);

        Audit::log('CMS_UPDATE_BANNER', $cmsBanner, $cmsBanner->toArray(), $oldValue);
        $this->contentService->clearCacheFor('banners');

        return redirect()->route('cms-banners.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(CmsBanner $cmsBanner)
    {
        Gate::authorize('cms.banners.delete');

        $oldValue = $cmsBanner->toArray();
        $this->mediaService->delete($cmsBanner->image_path);
        $cmsBanner->delete();

        Audit::log('CMS_DELETE_BANNER', $cmsBanner, null, $oldValue);
        $this->contentService->clearCacheFor('banners');

        return redirect()->route('cms-banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }
}

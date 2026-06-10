<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsTestimonialRequest;
use App\Models\CmsTestimonial;
use App\Services\CmsContentService;
use App\Services\CmsMediaService;
use Illuminate\Support\Facades\Gate;

class CmsTestimonialController extends Controller
{
    public function __construct(
        protected CmsMediaService $mediaService,
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.testimonials.view');

        $testimonials = CmsTestimonial::latest()->paginate(10);

        return view('cms.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        Gate::authorize('cms.testimonials.create');

        return view('cms.testimonials.create');
    }

    public function store(CmsTestimonialRequest $request)
    {
        Gate::authorize('cms.testimonials.create');

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('avatar')) {
            $validated['avatar'] = $request->input('avatar');
        } elseif ($request->hasFile('avatar_file')) {
            $validated['avatar'] = $this->mediaService->upload(
                $request->file('avatar_file'),
                'testimonials',
                200 // small avatar size
            );
        }

        $testimonial = CmsTestimonial::create($validated);

        Audit::log('CMS_CREATE_TESTIMONIAL', $testimonial, $testimonial->toArray());
        $this->contentService->clearCacheFor('testimonials');

        return redirect()->route('cms-testimonials.index')
            ->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(CmsTestimonial $cmsTestimonial)
    {
        Gate::authorize('cms.testimonials.edit');

        return view('cms.testimonials.edit', compact('cmsTestimonial'));
    }

    public function update(CmsTestimonialRequest $request, CmsTestimonial $cmsTestimonial)
    {
        Gate::authorize('cms.testimonials.edit');

        $oldValue = $cmsTestimonial->toArray();
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('avatar') && $request->input('avatar') !== $cmsTestimonial->avatar) {
            $this->mediaService->delete($cmsTestimonial->avatar);
            $validated['avatar'] = $request->input('avatar');
        } elseif ($request->hasFile('avatar_file')) {
            $this->mediaService->delete($cmsTestimonial->avatar);
            $validated['avatar'] = $this->mediaService->upload(
                $request->file('avatar_file'),
                'testimonials',
                200
            );
        }

        $cmsTestimonial->update($validated);

        Audit::log('CMS_UPDATE_TESTIMONIAL', $cmsTestimonial, $cmsTestimonial->toArray(), $oldValue);
        $this->contentService->clearCacheFor('testimonials');

        return redirect()->route('cms-testimonials.index')
            ->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(CmsTestimonial $cmsTestimonial)
    {
        Gate::authorize('cms.testimonials.delete');

        $oldValue = $cmsTestimonial->toArray();
        $this->mediaService->delete($cmsTestimonial->avatar);
        $cmsTestimonial->delete();

        Audit::log('CMS_DELETE_TESTIMONIAL', $cmsTestimonial, null, $oldValue);
        $this->contentService->clearCacheFor('testimonials');

        return redirect()->route('cms-testimonials.index')
            ->with('success', 'Testimoni berhasil dihapus.');
    }
}

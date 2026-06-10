<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsFaqRequest;
use App\Models\CmsFaq;
use App\Services\CmsContentService;
use Illuminate\Support\Facades\Gate;

class CmsFaqController extends Controller
{
    public function __construct(
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.faqs.view');

        $faqs = CmsFaq::orderBy('order')->paginate(10);

        return view('cms.faqs.index', compact('faqs'));
    }

    public function create()
    {
        Gate::authorize('cms.faqs.create');

        return view('cms.faqs.create');
    }

    public function store(CmsFaqRequest $request)
    {
        Gate::authorize('cms.faqs.create');

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $faq = CmsFaq::create($validated);

        Audit::log('CMS_CREATE_FAQ', $faq, $faq->toArray());
        $this->contentService->clearCacheFor('faqs');

        return redirect()->route('cms-faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(CmsFaq $cmsFaq)
    {
        Gate::authorize('cms.faqs.edit');

        return view('cms.faqs.edit', compact('cmsFaq'));
    }

    public function update(CmsFaqRequest $request, CmsFaq $cmsFaq)
    {
        Gate::authorize('cms.faqs.edit');

        $oldValue = $cmsFaq->toArray();
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $cmsFaq->update($validated);

        Audit::log('CMS_UPDATE_FAQ', $cmsFaq, $cmsFaq->toArray(), $oldValue);
        $this->contentService->clearCacheFor('faqs');

        return redirect()->route('cms-faqs.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(CmsFaq $cmsFaq)
    {
        Gate::authorize('cms.faqs.delete');

        $oldValue = $cmsFaq->toArray();
        $cmsFaq->delete();

        Audit::log('CMS_DELETE_FAQ', $cmsFaq, null, $oldValue);
        $this->contentService->clearCacheFor('faqs');

        return redirect()->route('cms-faqs.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }
}

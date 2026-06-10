<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsArticleRequest;
use App\Models\CmsArticle;
use App\Models\CmsCategory;
use App\Services\CmsContentService;
use App\Services\CmsMediaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CmsArticleController extends Controller
{
    public function __construct(
        protected CmsMediaService $mediaService,
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.articles.view');

        $articles = CmsArticle::with(['category', 'author'])
            ->latest()
            ->paginate(10);

        return view('cms.articles.index', compact('articles'));
    }

    public function create()
    {
        Gate::authorize('cms.articles.create');

        $categories = CmsCategory::orderBy('name')->get();

        return view('cms.articles.create', compact('categories'));
    }

    public function store(CmsArticleRequest $request)
    {
        Gate::authorize('cms.articles.create');

        $validated = $request->validated();
        $validated['author_id'] = Auth::id();

        // Check publishing permission
        if (in_array($validated['status'], ['published', 'scheduled']) && ! Auth::user()->hasPermission('cms.articles.publish')) {
            $validated['status'] = 'draft';
        }

        // Set published_at
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        // Handle image upload
        if ($request->filled('featured_image')) {
            $validated['featured_image'] = $request->input('featured_image');
        } elseif ($request->hasFile('featured_image_file')) {
            $validated['featured_image'] = $this->mediaService->upload(
                $request->file('featured_image_file'),
                'articles',
                800 // max width for article image
            );
        }

        $article = CmsArticle::create($validated);

        // Save SEO
        $article->seo()->create([
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'canonical_url' => $request->canonical_url,
        ]);

        Audit::log('CMS_CREATE_ARTICLE', $article, $article->load('seo')->toArray());
        $this->contentService->clearCacheFor('article', $article->slug);

        return redirect()->route('cms-articles.index')
            ->with('success', "Artikel {$article->title} berhasil disimpan.");
    }

    public function edit(CmsArticle $cmsArticle)
    {
        Gate::authorize('cms.articles.edit');

        $categories = CmsCategory::orderBy('name')->get();
        $cmsArticle->load('seo');

        return view('cms.articles.edit', compact('cmsArticle', 'categories'));
    }

    public function update(CmsArticleRequest $request, CmsArticle $cmsArticle)
    {
        Gate::authorize('cms.articles.edit');

        $oldValue = $cmsArticle->load('seo')->toArray();
        $validated = $request->validated();

        // Check publishing permission
        if (in_array($validated['status'], ['published', 'scheduled']) && ! Auth::user()->hasPermission('cms.articles.publish')) {
            // If it was already published, they can keep it published if they have edit permission,
            // otherwise keep it as draft/revert to draft
            if ($cmsArticle->status !== 'published') {
                $validated['status'] = 'draft';
            }
        }

        // Set published_at
        if ($validated['status'] === 'published') {
            $validated['published_at'] = $cmsArticle->published_at ?: now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        // Handle image upload
        if ($request->filled('featured_image') && $request->input('featured_image') !== $cmsArticle->featured_image) {
            $this->mediaService->delete($cmsArticle->featured_image);
            $validated['featured_image'] = $request->input('featured_image');
        } elseif ($request->hasFile('featured_image_file')) {
            $this->mediaService->delete($cmsArticle->featured_image);
            $validated['featured_image'] = $this->mediaService->upload(
                $request->file('featured_image_file'),
                'articles',
                800
            );
        }

        $cmsArticle->update($validated);

        // Update/create SEO
        $cmsArticle->seo()->updateOrCreate([], [
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'canonical_url' => $request->canonical_url,
        ]);

        Audit::log('CMS_UPDATE_ARTICLE', $cmsArticle, $cmsArticle->fresh()->load('seo')->toArray(), $oldValue);

        // Invalidate old slug cache and new slug cache
        $this->contentService->clearCacheFor('article', $oldValue['slug']);
        $this->contentService->clearCacheFor('article', $cmsArticle->slug);

        return redirect()->route('cms-articles.index')
            ->with('success', "Artikel {$cmsArticle->title} berhasil diperbarui.");
    }

    public function destroy(CmsArticle $cmsArticle)
    {
        Gate::authorize('cms.articles.delete');

        $oldValue = $cmsArticle->load('seo')->toArray();

        // Delete image file
        $this->mediaService->delete($cmsArticle->featured_image);

        $cmsArticle->delete();

        Audit::log('CMS_DELETE_ARTICLE', $cmsArticle, null, $oldValue);
        $this->contentService->clearCacheFor('article', $cmsArticle->slug);

        return redirect()->route('cms-articles.index')
            ->with('success', "Artikel {$cmsArticle->title} berhasil dihapus.");
    }
}

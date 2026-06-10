<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsCategoryRequest;
use App\Models\CmsCategory;
use Illuminate\Support\Facades\Gate;

class CmsCategoryController extends Controller
{
    public function index()
    {
        Gate::authorize('cms.articles.view');

        $categories = CmsCategory::with('parent')
            ->withCount('articles')
            ->latest()
            ->paginate(10);

        return view('cms.categories.index', compact('categories'));
    }

    public function create()
    {
        Gate::authorize('cms.articles.create');

        $parentCategories = CmsCategory::whereNull('parent_id')->orderBy('name')->get();

        return view('cms.categories.create', compact('parentCategories'));
    }

    public function store(CmsCategoryRequest $request)
    {
        Gate::authorize('cms.articles.create');

        $validated = $request->validated();
        $category = CmsCategory::create($validated);

        Audit::log('CMS_CREATE_CATEGORY', $category, $category->toArray());

        return redirect()->route('cms-categories.index')
            ->with('success', "Kategori {$category->name} berhasil dibuat.");
    }

    public function edit(CmsCategory $cmsCategory)
    {
        Gate::authorize('cms.articles.edit');

        $parentCategories = CmsCategory::whereNull('parent_id')
            ->where('id', '!=', $cmsCategory->id)
            ->orderBy('name')
            ->get();

        return view('cms.categories.edit', compact('cmsCategory', 'parentCategories'));
    }

    public function update(CmsCategoryRequest $request, CmsCategory $cmsCategory)
    {
        Gate::authorize('cms.articles.edit');

        $oldValue = $cmsCategory->toArray();
        $validated = $request->validated();

        $cmsCategory->update($validated);

        Audit::log('CMS_UPDATE_CATEGORY', $cmsCategory, $cmsCategory->toArray(), $oldValue);

        return redirect()->route('cms-categories.index')
            ->with('success', "Kategori {$cmsCategory->name} berhasil diperbarui.");
    }

    public function destroy(CmsCategory $cmsCategory)
    {
        Gate::authorize('cms.articles.delete');

        if ($cmsCategory->articles()->exists()) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh artikel.');
        }

        $oldValue = $cmsCategory->toArray();
        $cmsCategory->delete();

        Audit::log('CMS_DELETE_CATEGORY', $cmsCategory, null, $oldValue);

        return redirect()->route('cms-categories.index')
            ->with('success', "Kategori {$cmsCategory->name} berhasil dihapus.");
    }
}

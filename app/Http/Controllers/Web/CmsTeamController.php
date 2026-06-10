<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsTeamRequest;
use App\Models\CmsTeam;
use App\Services\CmsContentService;
use App\Services\CmsMediaService;
use Illuminate\Support\Facades\Gate;

class CmsTeamController extends Controller
{
    public function __construct(
        protected CmsMediaService $mediaService,
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.teams.view');

        $teams = CmsTeam::orderBy('order')->paginate(10);

        return view('cms.teams.index', compact('teams'));
    }

    public function create()
    {
        Gate::authorize('cms.teams.create');

        return view('cms.teams.create');
    }

    public function store(CmsTeamRequest $request)
    {
        Gate::authorize('cms.teams.create');

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('image')) {
            $validated['image'] = $request->input('image');
        } elseif ($request->hasFile('image_file')) {
            $validated['image'] = $this->mediaService->upload(
                $request->file('image_file'),
                'teams',
                400 // photo profile size
            );
        }

        $team = CmsTeam::create($validated);

        Audit::log('CMS_CREATE_TEAM', $team, $team->toArray());
        $this->contentService->clearCacheFor('teams');

        return redirect()->route('cms-teams.index')
            ->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(CmsTeam $cmsTeam)
    {
        Gate::authorize('cms.teams.edit');

        return view('cms.teams.edit', compact('cmsTeam'));
    }

    public function update(CmsTeamRequest $request, CmsTeam $cmsTeam)
    {
        Gate::authorize('cms.teams.edit');

        $oldValue = $cmsTeam->toArray();
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('image') && $request->input('image') !== $cmsTeam->image) {
            $this->mediaService->delete($cmsTeam->image);
            $validated['image'] = $request->input('image');
        } elseif ($request->hasFile('image_file')) {
            $this->mediaService->delete($cmsTeam->image);
            $validated['image'] = $this->mediaService->upload(
                $request->file('image_file'),
                'teams',
                400
            );
        }

        $cmsTeam->update($validated);

        Audit::log('CMS_UPDATE_TEAM', $cmsTeam, $cmsTeam->toArray(), $oldValue);
        $this->contentService->clearCacheFor('teams');

        return redirect()->route('cms-teams.index')
            ->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    public function destroy(CmsTeam $cmsTeam)
    {
        Gate::authorize('cms.teams.delete');

        $oldValue = $cmsTeam->toArray();
        $this->mediaService->delete($cmsTeam->image);
        $cmsTeam->delete();

        Audit::log('CMS_DELETE_TEAM', $cmsTeam, null, $oldValue);
        $this->contentService->clearCacheFor('teams');

        return redirect()->route('cms-teams.index')
            ->with('success', 'Anggota tim berhasil dihapus.');
    }
}

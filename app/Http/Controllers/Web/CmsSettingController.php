<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CmsSettingRequest;
use App\Models\CmsSetting;
use App\Services\CmsContentService;
use Illuminate\Support\Facades\Gate;

class CmsSettingController extends Controller
{
    public function __construct(
        protected CmsContentService $contentService
    ) {}

    public function index()
    {
        Gate::authorize('cms.settings.view');

        $settings = CmsSetting::all()->groupBy('group');

        return view('cms.settings.index', compact('settings'));
    }

    public function update(CmsSettingRequest $request)
    {
        Gate::authorize('cms.settings.edit');

        $validated = $request->validated();
        $updatedSettings = [];
        $groupsToClear = [];

        foreach ($validated['settings'] as $key => $value) {
            $setting = CmsSetting::where('key', $key)->first();
            if ($setting) {
                $oldValue = $setting->value;
                if ($oldValue !== $value) {
                    $setting->update(['value' => $value]);
                    $updatedSettings[$key] = [
                        'old' => $oldValue,
                        'new' => $value,
                    ];
                    $groupsToClear[$setting->group] = true;
                }
            } else {
                // If setting doesn't exist, create it with group 'general' as default fallback
                $newSetting = CmsSetting::create([
                    'group' => 'general',
                    'key' => $key,
                    'value' => $value,
                    'type' => 'string',
                ]);
                $updatedSettings[$key] = [
                    'old' => null,
                    'new' => $value,
                ];
                $groupsToClear['general'] = true;
            }
        }

        if (! empty($updatedSettings)) {
            Audit::log('CMS_UPDATE_SETTINGS', 'cms_settings', $updatedSettings);

            foreach (array_keys($groupsToClear) as $group) {
                $this->contentService->clearCacheFor('settings', $group);
            }
        }

        return redirect()->route('cms-settings.index')
            ->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}

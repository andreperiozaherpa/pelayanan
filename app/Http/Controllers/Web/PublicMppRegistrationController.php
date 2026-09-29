<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CmsMenu;
use App\Models\CmsSetting;
use App\Models\MppService;
use App\Models\MppServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicMppRegistrationController extends Controller
{
    public function index(): View
    {
        return view('public.mpp-registration.index', array_merge($this->layoutData(), ['services' => MppService::query()->where('is_active', true)->orderBy('name')->get()]));
    }

    public function create(MppService $service): View
    {
        abort_unless($service->is_active, 404);
        return view('public.mpp-registration.create', array_merge($this->layoutData(), compact('service')));
    }

    public function store(Request $request, MppService $service): RedirectResponse
    {
        abort_unless($service->is_active, 404);
        $rules = ['notes' => ['nullable', 'string', 'max:500']];
        foreach ($service->fields ?? [] as $field) {
            $rules['form_data.'.$field['name']] = [($field['required'] ?? false) ? 'required' : 'nullable', 'string', 'max:1000'];
        }
        $validated = $request->validate($rules);
        $submitted = [];
        foreach ($service->fields ?? [] as $field) {
            $name = $field['name'];
            $submitted[$name] = ['label' => $field['label'], 'type' => $field['type'], 'value' => data_get($validated, 'form_data.'.$name)];
        }
        $registration = DB::transaction(function () use ($service, $submitted, $validated): MppServiceRequest {
            return MppServiceRequest::create([
                'mpp_service_id' => $service->id,
                'public_registration_code' => 'MPP-'.Str::upper(Str::random(16)),
                'registered_at' => now(),
                'submitted_form_data' => $submitted,
                'notes' => $validated['notes'] ?? null,
                'status' => MppServiceRequest::STATUS_PENDING,
            ]);
        });
        return redirect()->route('public.mpp-registration.complete', $registration->public_registration_code);
    }

    public function complete(string $code): View
    {
        $registration = MppServiceRequest::query()->with('mppService')->where('public_registration_code', $code)->firstOrFail();
        return view('public.mpp-registration.complete', array_merge($this->layoutData(), compact('registration')));
    }

    private function layoutData(): array
    {
        return [
            'menus' => Cache::remember('landing.menus', 3600, fn () => CmsMenu::with('children')->whereNull('parent_id')->where('is_active', true)->orderBy('order')->get()),
            'settings' => Cache::remember('landing.settings', 3600, fn () => CmsSetting::pluck('value', 'key')->toArray()),
        ];
    }
}

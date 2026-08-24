<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\MppService;
use App\Models\Opd;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MppPelayananController extends Controller
{
    public function index(Request $request): View
    {
        $query = MppService::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%');
        }

        $services = $query->with('opd')->latest()->paginate(10);

        return view('services.admin.mpp.daftar-pelayanan.index', compact('services'));
    }

    public function create(): View
    {
        $opds = Opd::with('gerais')->orderBy('name')->get();

        return view('services.admin.mpp.daftar-pelayanan.create', compact('opds'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required|string|max:255|unique:mpp_services,name',
            'description' => 'nullable|string',
            'opd_id' => 'required|exists:opds,id',
            'gerai_id' => ['required', Rule::exists('mpp_gerais', 'id')->where('opd_id', $request->input('opd_id'))],
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|string|in:text,number,select,textarea,file,checkbox',
            'fields.*.required' => 'nullable',
            'fields.*.options' => 'nullable|array',
        ];

        if ($request->hasFile('logo')) {
            $rules['logo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            $rules['logo'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mpp-logos', 'public');
        } elseif (is_string($request->input('logo')) && ! empty($request->input('logo'))) {
            $logoPath = $request->input('logo');
        }

        $fields = [];
        foreach ($request->input('fields', []) as $field) {
            $fieldName = Str::slug($field['label'], '_');

            $originalName = $fieldName;
            $counter = 1;
            while (collect($fields)->contains('name', $fieldName)) {
                $fieldName = $originalName.'_'.$counter;
                $counter++;
            }

            $fields[] = [
                'id' => uniqid('field_'),
                'name' => $fieldName,
                'label' => $field['label'],
                'type' => $field['type'],
                'required' => isset($field['required']) && ($field['required'] === 'true' || $field['required'] === true || $field['required'] === '1'),
                'options' => in_array($field['type'], ['select', 'checkbox']) ? array_filter($field['options'] ?? []) : [],
            ];
        }

        $mppService = MppService::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'opd_id' => $validated['opd_id'],
            'gerai_id' => $validated['gerai_id'],
            'logo' => $logoPath,
            'fields' => $fields,
            'is_active' => $request->has('is_active'),
        ]);

        Audit::log('CREATE_MPP_SERVICE', $mppService, $mppService->toArray());

        return redirect()->route('mpp-services.index')
            ->with('success', "Pelayanan {$mppService->name} berhasil dibuat.");
    }

    public function edit(MppService $mppService): View
    {
        $opds = Opd::with('gerais')->orderBy('name')->get();

        return view('services.admin.mpp.daftar-pelayanan.edit', compact('mppService', 'opds'));
    }

    public function update(Request $request, MppService $mppService): RedirectResponse
    {
        $rules = [
            'name' => 'required|string|max:255|unique:mpp_services,name,'.$mppService->id,
            'description' => 'nullable|string',
            'opd_id' => 'required|exists:opds,id',
            'gerai_id' => ['required', Rule::exists('mpp_gerais', 'id')->where('opd_id', $request->input('opd_id'))],
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|string|in:text,number,select,textarea,file,checkbox',
            'fields.*.required' => 'nullable',
            'fields.*.options' => 'nullable|array',
        ];

        if ($request->hasFile('logo')) {
            $rules['logo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            $rules['logo'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $oldValue = $mppService->toArray();

        $logoPath = $mppService->logo;
        if ($request->hasFile('logo')) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('mpp-logos', 'public');
        } elseif ($request->has('logo')) {
            $newLogoPath = $request->input('logo');
            if ($newLogoPath !== $logoPath) {
                if ($logoPath && empty($newLogoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }
                $logoPath = $newLogoPath ?: null;
            }
        }

        $fields = [];
        foreach ($request->input('fields', []) as $field) {
            $fieldName = Str::slug($field['label'], '_');

            $originalName = $fieldName;
            $counter = 1;
            while (collect($fields)->contains('name', $fieldName)) {
                $fieldName = $originalName.'_'.$counter;
                $counter++;
            }

            $fields[] = [
                'id' => $field['id'] ?? uniqid('field_'),
                'name' => $fieldName,
                'label' => $field['label'],
                'type' => $field['type'],
                'required' => isset($field['required']) && ($field['required'] === 'true' || $field['required'] === true || $field['required'] === '1'),
                'options' => in_array($field['type'], ['select', 'checkbox']) ? array_filter($field['options'] ?? []) : [],
            ];
        }

        $mppService->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'opd_id' => $validated['opd_id'],
            'gerai_id' => $validated['gerai_id'],
            'logo' => $logoPath,
            'fields' => $fields,
            'is_active' => $request->has('is_active'),
        ]);

        Audit::log('UPDATE_MPP_SERVICE', $mppService, $mppService->fresh()->toArray(), $oldValue);

        return redirect()->route('mpp-services.index')
            ->with('success', "Pelayanan {$mppService->name} berhasil diperbarui.");
    }

    public function destroy(MppService $mppService): RedirectResponse
    {
        $oldValue = $mppService->toArray();

        if ($mppService->logo) {
            Storage::disk('public')->delete($mppService->logo);
        }

        $mppService->delete();

        Audit::log('DELETE_MPP_SERVICE', $mppService, null, $oldValue);

        return redirect()->route('mpp-services.index')
            ->with('success', "Pelayanan {$mppService->name} berhasil dihapus.");
    }

    public function show(MppService $mppService): View
    {
        return view('services.admin.mpp.daftar-pelayanan.show', compact('mppService'));
    }

    public function uploadLogo(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (! $user || ! $user->hasPermission('system.manage')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk mengunggah logo.',
            ], 403);
        }

        $request->validate([
            'file' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('mpp-logos', 'public');

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset('storage/'.$path),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah logo: '.$e->getMessage(),
            ], 500);
        }
    }
}

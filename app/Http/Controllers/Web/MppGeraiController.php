<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\Gerai;
use App\Models\Opd;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MppGeraiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Gerai::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%');
        }

        $gerais = $query->with('opd')->withCount('counters')->orderBy('code')->paginate(10);

        return view('services.admin.mpp.daftar-gerai.index', compact('gerais'));
    }

    public function create(): View
    {
        $opds = Opd::orderBy('name')->get();

        return view('services.admin.mpp.daftar-gerai.create', compact('opds'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'code' => 'required|string|max:10|unique:mpp_gerais,code',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('mpp-logos', 'public');
        } elseif (is_string($request->input('logo')) && ! empty($request->input('logo'))) {
            $validated['logo'] = $request->input('logo');
        }

        $gerai = Gerai::create($validated);

        Audit::log('CREATE_GERAI', $gerai, $gerai->toArray());

        return redirect()->route('gerais.index')
            ->with('success', "Gerai {$gerai->name} berhasil ditambahkan.");
    }

    public function edit(Gerai $gerai): View
    {
        $opds = Opd::orderBy('name')->get();

        return view('services.admin.mpp.daftar-gerai.edit', compact('gerai', 'opds'));
    }

    public function update(Request $request, Gerai $gerai): RedirectResponse
    {
        $validated = $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'code' => 'required|string|max:10|unique:mpp_gerais,code,'.$gerai->id,
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            if ($gerai->logo) {
                Storage::disk('public')->delete($gerai->logo);
            }
            $validated['logo'] = $request->file('logo')->store('mpp-logos', 'public');
        } elseif (is_string($request->input('logo')) && ! empty($request->input('logo'))) {
            $validated['logo'] = $request->input('logo');
        }

        $gerai->update($validated);

        Audit::log('UPDATE_GERAI', $gerai, $gerai->fresh()->toArray());

        return redirect()->route('gerais.index')
            ->with('success', "Gerai {$gerai->name} berhasil diperbarui.");
    }

    public function destroy(Gerai $gerai): RedirectResponse
    {
        $name = $gerai->name;

        if ($gerai->logo) {
            Storage::disk('public')->delete($gerai->logo);
        }

        $gerai->delete();

        Audit::log('DELETE_GERAI', null, ['name' => $name]);

        return redirect()->route('gerais.index')
            ->with('success', "Gerai {$name} berhasil dihapus.");
    }

    public function uploadLogo(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (! $user || ! $user->hasPermission('mpp.gerai.manage')) {
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

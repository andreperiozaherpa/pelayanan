<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\Anjungan;
use App\Models\MppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MppAnjunganController extends Controller
{
    public function index(Request $request): View
    {
        $query = Anjungan::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%')
                ->orWhere('location', 'like', '%'.$request->search.'%');
        }

        $anjungans = $query->withCount('services')->latest()->paginate(10);

        return view('services.admin.mpp.daftar-anjungan.index', compact('anjungans'));
    }

    public function create(): View
    {
        return view('services.admin.mpp.daftar-anjungan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:mpp_anjungans,code',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $anjungan = Anjungan::create($validated);

        Audit::log('CREATE_ANJUNGAN', $anjungan, $anjungan->toArray());

        return redirect()->route('anjungans.index')
            ->with('success', "Anjungan {$anjungan->name} berhasil ditambahkan.");
    }

    public function show(Anjungan $anjungan): View
    {
        $services = MppService::where('anjungan_id', $anjungan->id)->latest()->paginate(10);

        return view('services.admin.mpp.daftar-anjungan.show', compact('anjungan', 'services'));
    }

    public function edit(Anjungan $anjungan): View
    {
        return view('services.admin.mpp.daftar-anjungan.edit', compact('anjungan'));
    }

    public function update(Request $request, Anjungan $anjungan): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:mpp_anjungans,code,'.$anjungan->id,
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $anjungan->update($validated);

        Audit::log('UPDATE_ANJUNGAN', $anjungan, $anjungan->toArray());

        return redirect()->route('anjungans.index')
            ->with('success', "Anjungan {$anjungan->name} berhasil diperbarui.");
    }

    public function destroy(Anjungan $anjungan): RedirectResponse
    {
        $name = $anjungan->name;
        $anjungan->delete();

        Audit::log('DELETE_ANJUNGAN', null, ['name' => $name]);

        return redirect()->route('anjungans.index')
            ->with('success', "Anjungan {$name} berhasil dihapus.");
    }
}

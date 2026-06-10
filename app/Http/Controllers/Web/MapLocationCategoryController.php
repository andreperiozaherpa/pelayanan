<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MapLocationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MapLocationCategoryController extends Controller
{
    public function index()
    {
        $categories = MapLocationCategory::latest()->paginate(15);

        return view('master-maps.map-location-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('master-maps.map-location-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:100',
            'color' => 'nullable|string|size:7',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        MapLocationCategory::create($validated);

        return redirect()->route('map-location-categories.index')
            ->with('success', 'Kategori lokasi berhasil ditambahkan.');
    }

    public function edit(MapLocationCategory $mapLocationCategory)
    {
        return view('master-maps.map-location-categories.edit', compact('mapLocationCategory'));
    }

    public function update(Request $request, MapLocationCategory $mapLocationCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:100',
            'color' => 'nullable|string|size:7',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $mapLocationCategory->update($validated);

        return redirect()->route('map-location-categories.index')
            ->with('success', 'Kategori lokasi berhasil diperbarui.');
    }

    public function destroy(MapLocationCategory $mapLocationCategory)
    {
        $mapLocationCategory->delete();

        return redirect()->route('map-location-categories.index')
            ->with('success', 'Kategori lokasi berhasil dihapus.');
    }
}

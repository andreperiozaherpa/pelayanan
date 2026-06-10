<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MapRegion;
use Illuminate\Http\Request;

class MapRegionController extends Controller
{
    public function index()
    {
        $regions = MapRegion::with('parent')->latest()->paginate(15);

        return view('master-maps.map-regions.index', compact('regions'));
    }

    public function create()
    {
        $parents = MapRegion::whereIn('level', ['kabupaten', 'kecamatan'])->get();

        return view('master-maps.map-regions.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'level' => 'required|in:kabupaten,kecamatan,desa',
            'parent_id' => 'nullable|exists:map_regions,id',
            'color' => 'nullable|string|size:7',
            'geojson' => 'nullable|string',
            'area_km2' => 'nullable|numeric|min:0',
        ]);

        if (! empty($validated['geojson'])) {
            $validated['geojson'] = json_decode($validated['geojson'], true);
        } else {
            $validated['geojson'] = null;
        }

        MapRegion::create($validated);

        return redirect()->route('map-regions.index')
            ->with('success', 'Batas wilayah berhasil ditambahkan.');
    }

    public function edit(MapRegion $mapRegion)
    {
        $parents = MapRegion::whereIn('level', ['kabupaten', 'kecamatan'])
            ->where('id', '!=', $mapRegion->id)
            ->get();

        return view('master-maps.map-regions.edit', compact('mapRegion', 'parents'));
    }

    public function update(Request $request, MapRegion $mapRegion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'level' => 'required|in:kabupaten,kecamatan,desa',
            'parent_id' => 'nullable|exists:map_regions,id',
            'color' => 'nullable|string|size:7',
            'geojson' => 'nullable|string',
            'area_km2' => 'nullable|numeric|min:0',
        ]);

        if (! empty($validated['geojson'])) {
            $validated['geojson'] = json_decode($validated['geojson'], true);
        } else {
            $validated['geojson'] = null;
        }

        $mapRegion->update($validated);

        return redirect()->route('map-regions.index')
            ->with('success', 'Batas wilayah berhasil diperbarui.');
    }

    public function destroy(MapRegion $mapRegion)
    {
        $mapRegion->delete();

        return redirect()->route('map-regions.index')
            ->with('success', 'Batas wilayah berhasil dihapus.');
    }
}

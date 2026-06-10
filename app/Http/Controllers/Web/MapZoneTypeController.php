<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MapZoneType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MapZoneTypeController extends Controller
{
    public function index()
    {
        $types = MapZoneType::latest()->paginate(15);

        return view('master-maps.map-zone-types.index', compact('types'));
    }

    public function create()
    {
        return view('master-maps.map-zone-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|size:7',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        MapZoneType::create($validated);

        return redirect()->route('map-zone-types.index')
            ->with('success', 'Kategori zonasi berhasil ditambahkan.');
    }

    public function edit(MapZoneType $mapZoneType)
    {
        return view('master-maps.map-zone-types.edit', compact('mapZoneType'));
    }

    public function update(Request $request, MapZoneType $mapZoneType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|size:7',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $mapZoneType->update($validated);

        return redirect()->route('map-zone-types.index')
            ->with('success', 'Kategori zonasi berhasil diperbarui.');
    }

    public function destroy(MapZoneType $mapZoneType)
    {
        $mapZoneType->delete();

        return redirect()->route('map-zone-types.index')
            ->with('success', 'Kategori zonasi berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MapRegion;
use App\Models\MapZone;
use App\Models\MapZoneType;
use Illuminate\Http\Request;

class MapZoneController extends Controller
{
    public function index()
    {
        $zones = MapZone::with(['type', 'region'])->latest()->paginate(15);

        return view('master-maps.map-zones.index', compact('zones'));
    }

    public function create()
    {
        $types = MapZoneType::all();
        $regions = MapRegion::whereIn('level', ['kecamatan', 'desa'])->get();

        return view('master-maps.map-zones.create', compact('types', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'zone_type_id' => 'required|exists:map_zone_types,id',
            'region_id' => 'nullable|exists:map_regions,id',
            'geojson' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $validated['geojson'] = json_decode($validated['geojson'], true);

        MapZone::create($validated);

        return redirect()->route('map-zones.index')
            ->with('success', 'Zona tata ruang berhasil ditambahkan.');
    }

    public function edit(MapZone $mapZone)
    {
        $types = MapZoneType::all();
        $regions = MapRegion::whereIn('level', ['kecamatan', 'desa'])->get();

        return view('master-maps.map-zones.edit', compact('mapZone', 'types', 'regions'));
    }

    public function update(Request $request, MapZone $mapZone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'zone_type_id' => 'required|exists:map_zone_types,id',
            'region_id' => 'nullable|exists:map_regions,id',
            'geojson' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $validated['geojson'] = json_decode($validated['geojson'], true);

        $mapZone->update($validated);

        return redirect()->route('map-zones.index')
            ->with('success', 'Zona tata ruang berhasil diperbarui.');
    }

    public function destroy(MapZone $mapZone)
    {
        $mapZone->delete();

        return redirect()->route('map-zones.index')
            ->with('success', 'Zona tata ruang berhasil dihapus.');
    }
}

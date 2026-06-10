<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MapLocation;
use App\Models\MapLocationCategory;
use App\Models\MapLocationRestriction;
use App\Models\MapRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MapLocationController extends Controller
{
    public function index()
    {
        $locations = MapLocation::with(['category', 'region'])->latest()->paginate(15);

        return view('master-maps.map-locations.index', compact('locations'));
    }

    public function create()
    {
        $categories = MapLocationCategory::all();
        $regions = MapRegion::whereIn('level', ['kecamatan', 'desa'])->get();

        return view('master-maps.map-locations.create', compact('categories', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:map_location_categories,id',
            'region_id' => 'nullable|exists:map_regions,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            // Restriction validations
            'has_restriction' => 'nullable|boolean',
            'restriction_geojson' => 'required_if:has_restriction,1|nullable|string',
            'restricted_activities' => 'required_if:has_restriction,1|nullable|array',
            'restriction_notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('map-locations', 'public');
        }

        $location = MapLocation::create($validated);

        if ($request->boolean('has_restriction') && ! empty($request->input('restriction_geojson'))) {
            MapLocationRestriction::create([
                'location_id' => $location->id,
                'geojson' => json_decode($request->input('restriction_geojson'), true),
                'restricted_activities' => $request->input('restricted_activities', []),
                'notes' => $request->input('restriction_notes'),
            ]);
        }

        return redirect()->route('map-locations.index')
            ->with('success', 'Titik lokasi berhasil ditambahkan.');
    }

    public function edit(MapLocation $mapLocation)
    {
        $categories = MapLocationCategory::all();
        $regions = MapRegion::whereIn('level', ['kecamatan', 'desa'])->get();
        $restriction = $mapLocation->restrictions()->first();

        return view('master-maps.map-locations.edit', compact('mapLocation', 'categories', 'regions', 'restriction'));
    }

    public function update(Request $request, MapLocation $mapLocation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:map_location_categories,id',
            'region_id' => 'nullable|exists:map_regions,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            // Restriction validations
            'has_restriction' => 'nullable|boolean',
            'restriction_geojson' => 'required_if:has_restriction,1|nullable|string',
            'restricted_activities' => 'required_if:has_restriction,1|nullable|array',
            'restriction_notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($mapLocation->photo) {
                Storage::disk('public')->delete($mapLocation->photo);
            }
            $validated['photo'] = $request->file('photo')->store('map-locations', 'public');
        }

        $mapLocation->update($validated);

        if ($request->boolean('has_restriction') && ! empty($request->input('restriction_geojson'))) {
            MapLocationRestriction::updateOrCreate(
                ['location_id' => $mapLocation->id],
                [
                    'geojson' => json_decode($request->input('restriction_geojson'), true),
                    'restricted_activities' => $request->input('restricted_activities', []),
                    'notes' => $request->input('restriction_notes'),
                ]
            );
        } else {
            // Delete restriction if it was removed
            $mapLocation->restrictions()->delete();
        }

        return redirect()->route('map-locations.index')
            ->with('success', 'Titik lokasi berhasil diperbarui.');
    }

    public function destroy(MapLocation $mapLocation)
    {
        if ($mapLocation->photo) {
            Storage::disk('public')->delete($mapLocation->photo);
        }
        $mapLocation->delete();

        return redirect()->route('map-locations.index')
            ->with('success', 'Titik lokasi berhasil dihapus.');
    }
}

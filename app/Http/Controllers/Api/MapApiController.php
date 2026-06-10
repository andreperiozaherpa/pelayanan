<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MapLocation;
use App\Models\MapRegion;
use App\Models\MapZone;
use Illuminate\Http\JsonResponse;

class MapApiController extends Controller
{
    /**
     * Get all regions with GeoJSON data.
     */
    public function regions(): JsonResponse
    {
        $regions = MapRegion::whereNotNull('geojson')->get();

        $features = $regions->map(function ($region) {
            return [
                'type' => 'Feature',
                'id' => $region->id,
                'geometry' => $region->geojson,
                'properties' => [
                    'name' => $region->name,
                    'code' => $region->code,
                    'level' => $region->level,
                    'color' => $region->color,
                    'parent_id' => $region->parent_id,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * Get all zones with GeoJSON data.
     */
    public function zones(): JsonResponse
    {
        $zones = MapZone::with('type')->get();

        $features = $zones->map(function ($zone) {
            return [
                'type' => 'Feature',
                'id' => $zone->id,
                'geometry' => $zone->geojson,
                'properties' => [
                    'name' => $zone->name,
                    'type_name' => $zone->type->name,
                    'type_slug' => $zone->type->slug,
                    'color' => $zone->type->color ?? '#94a3b8',
                    'description' => $zone->description,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    /**
     * Get all point locations (POIs).
     */
    public function locations(): JsonResponse
    {
        $locations = MapLocation::with(['category', 'restrictions'])->get();

        $formatted = $locations->map(function ($loc) {
            return [
                'id' => $loc->id,
                'name' => $loc->name,
                'latitude' => (float) $loc->latitude,
                'longitude' => (float) $loc->longitude,
                'address' => $loc->address,
                'description' => $loc->description,
                'photo_url' => $loc->photo ? asset('storage/'.$loc->photo) : null,
                'category' => [
                    'name' => $loc->category->name,
                    'slug' => $loc->category->slug,
                    'icon' => $loc->category->icon,
                    'color' => $loc->category->color,
                ],
                'restrictions' => $loc->restrictions->map(function ($rest) {
                    return [
                        'id' => $rest->id,
                        'geojson' => $rest->geojson,
                        'restricted_activities' => $rest->restricted_activities,
                        'notes' => $rest->notes,
                    ];
                }),
            ];
        });

        return response()->json($formatted);
    }
}

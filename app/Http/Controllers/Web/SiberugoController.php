<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MapLocation;
use App\Models\MapLocationCategory;
use App\Models\MapRegion;
use App\Models\MapZone;

class SiberugoController extends Controller
{
    /**
     * Display the SIBERUGO landing page.
     */
    public function index()
    {
        $stats = [
            'kecamatan' => MapRegion::kecamatan()->count(),
            'desa' => MapRegion::desa()->count(),
            'zona' => MapZone::count(),
            'poi' => MapLocation::count(),
        ];

        return view('public.siberugo.index', compact('stats'));
    }

    /**
     * Display the full interactive map.
     */
    public function map()
    {
        $categories = MapLocationCategory::all();
        $regions = MapRegion::kecamatan()->with('children')->get();

        return view('public.siberugo.map', compact('categories', 'regions'));
    }
}

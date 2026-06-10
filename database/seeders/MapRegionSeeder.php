<?php

namespace Database\Seeders;

use App\Models\MapRegion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MapRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Load GeoJSON
        $path = storage_path('app/public/maps/mapsKabupaten.json');
        if (! File::exists($path)) {
            $path = public_path('maps/mapsKabupaten.json'); // fallback
        }

        $tubabaFeature = null;
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            foreach ($data['features'] as $feature) {
                if (($feature['properties']['WADMKK'] ?? '') === 'Tulang Bawang Barat') {
                    $tubabaFeature = $feature;
                    break;
                }
            }
        }

        // 2. Seed Kabupaten
        $kabupaten = MapRegion::updateOrCreate(
            ['code' => '18.12', 'level' => 'kabupaten'],
            [
                'name' => 'Kabupaten Tulang Bawang Barat',
                'color' => '#1E293B', // Slate dark for kabupaten border
                // We don't have the full kabupaten polygon in this file (it is village level),
                // but we can store the geometry of the village or null as the kabupaten's fallback geometry
                'geojson' => $tubabaFeature ? $tubabaFeature['geometry'] : null,
            ]
        );

        // 3. Seed 9 Kecamatan in Tubaba with unique colors
        $kecamatans = [
            ['code' => '18.12.01', 'name' => 'Tulang Bawang Tengah', 'color' => '#3B82F6'], // Blue
            ['code' => '18.12.02', 'name' => 'Tumijajar', 'color' => '#10B981'],          // Green
            ['code' => '18.12.03', 'name' => 'Tulang Bawang Udik', 'color' => '#EF4444'],     // Red
            ['code' => '18.12.04', 'name' => 'Gunung Terang', 'color' => '#F59E0B'],         // Amber
            ['code' => '18.12.05', 'name' => 'Gunung Agung', 'color' => '#8B5CF6'],          // Purple
            ['code' => '18.12.06', 'name' => 'Way Kenanga', 'color' => '#EC4899'],           // Pink
            ['code' => '18.12.07', 'name' => 'Lambu Kibang', 'color' => '#06B6D4'],          // Cyan
            ['code' => '18.12.08', 'name' => 'Pagar Dewa', 'color' => '#14B8A6'],           // Teal
            ['code' => '18.12.09', 'name' => 'Batu Putih', 'color' => '#6366F1'],            // Indigo
        ];

        $kecamatanModels = [];
        foreach ($kecamatans as $kec) {
            $kecamatanModels[$kec['code']] = MapRegion::updateOrCreate(
                ['code' => $kec['code'], 'level' => 'kecamatan'],
                [
                    'name' => $kec['name'],
                    'parent_id' => $kabupaten->id,
                    'color' => $kec['color'],
                    'geojson' => null, // drawn/edited manually
                ]
            );
        }

        // 4. Seed sample village "Indraloka I" under Way Kenanga (code 18.12.06) using geometry from JSON
        if ($tubabaFeature) {
            $wayKenanga = $kecamatanModels['18.12.06'];
            MapRegion::updateOrCreate(
                ['code' => '18.12.06.2005', 'level' => 'desa'],
                [
                    'name' => 'Indraloka I',
                    'parent_id' => $wayKenanga->id,
                    'color' => '#F472B6', // Tint of Way Kenanga pink
                    'geojson' => $tubabaFeature['geometry'],
                    'metadata' => [
                        'luas_ha' => $tubabaFeature['properties']['LUAS'] ?? null,
                        'sumber' => $tubabaFeature['properties']['METADATA'] ?? null,
                    ],
                ]
            );
        }

        // Seed some placeholder villages for other kecamatans to show in UI
        $sampleVillages = [
            // Tulang Bawang Tengah (18.12.01)
            ['code' => '18.12.01.2001', 'name' => 'Panaragan Jaya', 'parent_code' => '18.12.01', 'color' => '#60A5FA'],
            ['code' => '18.12.01.2002', 'name' => 'Mulya Kencana', 'parent_code' => '18.12.01', 'color' => '#93C5FD'],
            // Tumijajar (18.12.02)
            ['code' => '18.12.02.2001', 'name' => 'Dayamurni', 'parent_code' => '18.12.02', 'color' => '#34D399'],
            ['code' => '18.12.02.2002', 'name' => 'Margo Mulyo', 'parent_code' => '18.12.02', 'color' => '#6EE7B7'],
        ];

        foreach ($sampleVillages as $v) {
            $parent = $kecamatanModels[$v['parent_code']] ?? null;
            if ($parent) {
                MapRegion::updateOrCreate(
                    ['code' => $v['code'], 'level' => 'desa'],
                    [
                        'name' => $v['name'],
                        'parent_id' => $parent->id,
                        'color' => $v['color'],
                        'geojson' => null,
                    ]
                );
            }
        }
    }
}

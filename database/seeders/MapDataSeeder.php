<?php

namespace Database\Seeders;

use App\Models\MapLocation;
use App\Models\MapLocationCategory;
use App\Models\MapLocationRestriction;
use App\Models\MapRegion;
use App\Models\MapZone;
use App\Models\MapZoneType;
use Illuminate\Database\Seeder;

class MapDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get references to existing MapRegions
        $tbt = MapRegion::where('code', '18.12.01')->first(); // Tulang Bawang Tengah
        $tumijajar = MapRegion::where('code', '18.12.02')->first(); // Tumijajar

        // Fallbacks if not found
        $tbtId = $tbt ? $tbt->id : null;
        $tumijajarId = $tumijajar ? $tumijajar->id : null;

        // 2. Get references to MapZoneTypes
        $industriType = MapZoneType::where('slug', 'industri')->first();
        $pariwisataType = MapZoneType::where('slug', 'pariwisata')->first();
        $permukimanType = MapZoneType::where('slug', 'permukiman')->first();
        $konservasiType = MapZoneType::where('slug', 'konservasi')->first();

        // 3. Seed MapZones (Polygons)
        if ($industriType) {
            MapZone::updateOrCreate(
                ['name' => 'Kawasan Peruntukan Industri (KPI) Penaragan'],
                [
                    'zone_type_id' => $industriType->id,
                    'region_id' => $tbtId,
                    'geojson' => [
                        'type' => 'Polygon',
                        'coordinates' => [
                            [
                                [105.020, -4.420],
                                [105.040, -4.420],
                                [105.040, -4.430],
                                [105.020, -4.430],
                                [105.020, -4.420],
                            ],
                        ],
                    ],
                    'description' => 'Zona pengembangan industri manufaktur dan pergudangan non-polusi di Tulang Bawang Tengah.',
                ]
            );
        }

        if ($pariwisataType) {
            MapZone::updateOrCreate(
                ['name' => 'Kawasan Strategis Wisata Budaya Dayamurni'],
                [
                    'zone_type_id' => $pariwisataType->id,
                    'region_id' => $tumijajarId,
                    'geojson' => [
                        'type' => 'Polygon',
                        'coordinates' => [
                            [
                                [105.000, -4.460],
                                [105.020, -4.460],
                                [105.020, -4.480],
                                [105.000, -4.480],
                                [105.000, -4.460],
                            ],
                        ],
                    ],
                    'description' => 'Zona pengembangan pariwisata, rekreasi, ekonomi kreatif, dan pelestarian budaya lokal.',
                ]
            );
        }

        if ($permukimanType) {
            MapZone::updateOrCreate(
                ['name' => 'Kawasan Permukiman Perkotaan Pulung Kencana'],
                [
                    'zone_type_id' => $permukimanType->id,
                    'region_id' => $tbtId,
                    'geojson' => [
                        'type' => 'Polygon',
                        'coordinates' => [
                            [
                                [105.035, -4.450],
                                [105.055, -4.450],
                                [105.055, -4.470],
                                [105.035, -4.470],
                                [105.035, -4.450],
                            ],
                        ],
                    ],
                    'description' => 'Zona pemukiman penduduk kepadatan sedang dengan sarana prasarana lingkungan terpadu.',
                ]
            );
        }

        // 4. Get references to MapLocationCategories
        $ibadahCat = MapLocationCategory::where('slug', 'ibadah')->first();
        $wisataCat = MapLocationCategory::where('slug', 'wisata')->first();
        $kesehatanCat = MapLocationCategory::where('slug', 'kesehatan')->first();
        $pendidikanCat = MapLocationCategory::where('slug', 'pendidikan')->first();
        $pemerintahanCat = MapLocationCategory::where('slug', 'pemerintahan')->first();

        // 5. Seed MapLocations (POIs) & Restrictions
        if ($ibadahCat) {
            $islamicCenter = MapLocation::updateOrCreate(
                ['name' => 'Masjid Agung Baitus Shobur (Islamic Center Tubaba)'],
                [
                    'category_id' => $ibadahCat->id,
                    'region_id' => $tbtId,
                    'latitude' => -4.442965,
                    'longitude' => 105.049386,
                    'address' => 'Kel. Panaragan Jaya, Kec. Tulang Bawang Tengah, Kab. Tulang Bawang Barat, Lampung 34612',
                    'description' => 'Masjid ikonik tanpa kubah yang dirancang oleh arsitek Andra Matin, menjadi ikon spiritual dan pariwisata religi Kabupaten Tulang Bawang Barat.',
                ]
            );

            // Seed restriction zone around Islamic Center (Radius 500m area)
            MapLocationRestriction::updateOrCreate(
                ['location_id' => $islamicCenter->id],
                [
                    'geojson' => [
                        'type' => 'Polygon',
                        'coordinates' => [
                            [
                                [105.045, -4.440],
                                [105.054, -4.440],
                                [105.054, -4.446],
                                [105.045, -4.446],
                                [105.045, -4.440],
                            ],
                        ],
                    ],
                    'restricted_activities' => [
                        'Pembangunan Pabrik / Industri Berat',
                        'Pembangunan Tempat Hiburan Malam / Diskotek',
                        'Aktivitas Pergudangan Bising & Emisi Tinggi',
                        'Penebangan Pohon / Kerusakan Area Hijau Terbuka',
                    ],
                    'notes' => 'Batas zona perlindungan radius 500m dari Islamic Center Tubaba. Wajib menjaga estetika arsitektur dan kenyamanan ibadah.',
                ]
            );
        }

        if ($wisataCat) {
            MapLocation::updateOrCreate(
                ['name' => 'Tugu Rato Nago Besanding'],
                [
                    'category_id' => $wisataCat->id,
                    'region_id' => $tbtId,
                    'latitude' => -4.435738,
                    'longitude' => 105.048731,
                    'address' => 'Simpang Tiga Kagungan Ratu, Kec. Tulang Bawang Tengah, Kab. Tulang Bawang Barat',
                    'description' => 'Monumen megah berupa patung kereta kencana naga yang ditarik oleh empat ekor naga besar, melambangkan kepemimpinan adat dan kearifan lokal Tubaba.',
                ]
            );

            MapLocation::updateOrCreate(
                ['name' => 'Patung Megalith Relief Tubaba'],
                [
                    'category_id' => $wisataCat->id,
                    'region_id' => $tbtId,
                    'latitude' => -4.438510,
                    'longitude' => 105.041280,
                    'address' => 'Panaragan, Kec. Tulang Bawang Tengah, Kab. Tulang Bawang Barat',
                    'description' => 'Ukiran patung batu megalitik berwajah manusia berukuran raksasa di perbukitan Tubaba, salah satu ikon destinasi wisata budaya.',
                ]
            );
        }

        if ($kesehatanCat) {
            MapLocation::updateOrCreate(
                ['name' => 'Puskesmas Rawat Inap Panaragan Jaya'],
                [
                    'category_id' => $kesehatanCat->id,
                    'region_id' => $tbtId,
                    'latitude' => -4.449622,
                    'longitude' => 105.043516,
                    'address' => 'Jl. Diponegoro No. 12, Panaragan Jaya, Kec. Tulang Bawang Tengah',
                    'description' => 'Puskesmas dengan fasilitas rawat inap utama yang melayani masyarakat Kecamatan Tulang Bawang Tengah dan sekitarnya.',
                ]
            );
        }

        if ($pendidikanCat) {
            MapLocation::updateOrCreate(
                ['name' => 'SMAN 1 Tumijajar'],
                [
                    'category_id' => $pendidikanCat->id,
                    'region_id' => $tumijajarId,
                    'latitude' => -4.471900,
                    'longitude' => 105.012500,
                    'address' => 'Jl. Pahlawan No. 45, Dayamurni, Kec. Tumijajar, Kab. Tulang Bawang Barat',
                    'description' => 'Sekolah menengah atas negeri unggulan di Kecamatan Tumijajar dengan program akreditasi A.',
                ]
            );
        }

        if ($pemerintahanCat) {
            MapLocation::updateOrCreate(
                ['name' => 'Kantor Bupati Tulang Bawang Barat'],
                [
                    'category_id' => $pemerintahanCat->id,
                    'region_id' => $tbtId,
                    'latitude' => -4.441850,
                    'longitude' => 105.047120,
                    'address' => 'Kompleks Perkantoran Pemkab Tubaba, Kel. Panaragan Jaya, Kec. Tulang Bawang Tengah',
                    'description' => 'Pusat administrasi pemerintahan Kabupaten Tulang Bawang Barat, bersebelahan dengan kompleks Islamic Center.',
                ]
            );
        }
    }
}

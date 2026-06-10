<?php

use App\Models\MapLocationCategory;
use App\Models\MapZoneType;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->superAdmin = User::factory()->create([
        'role_id' => Role::where('slug', 'superadmin')->first()->id,
    ]);

    $this->operatorDesa = User::factory()->create([
        'role_id' => Role::where('slug', 'operatordesa')->first()->id,
    ]);

    // Create seed items
    $this->category = MapLocationCategory::create([
        'name' => 'Masjid',
        'slug' => 'masjid',
        'icon' => 'lucide:map-pin',
        'color' => '#10b981',
    ]);

    $this->zoneType = MapZoneType::create([
        'name' => 'Industri',
        'slug' => 'industri',
        'color' => '#ef4444',
    ]);
});

test('super admin can access maps management views', function () {
    $this->actingAs($this->superAdmin)->get(route('map-regions.index'))->assertStatus(200);
    $this->actingAs($this->superAdmin)->get(route('map-zones.index'))->assertStatus(200);
    $this->actingAs($this->superAdmin)->get(route('map-locations.index'))->assertStatus(200);
});

test('operator desa cannot access maps management views', function () {
    $this->actingAs($this->operatorDesa)->get(route('map-regions.index'))->assertStatus(403);
    $this->actingAs($this->operatorDesa)->get(route('map-zones.index'))->assertStatus(403);
    $this->actingAs($this->operatorDesa)->get(route('map-locations.index'))->assertStatus(403);
});

test('super admin can create a map region', function () {
    $regionData = [
        'name' => 'Kecamatan Tumijajar',
        'code' => '18.12.01',
        'level' => 'kecamatan',
        'geojson' => json_encode([
            'type' => 'Polygon',
            'coordinates' => [
                [[105.01, -4.41], [105.05, -4.41], [105.05, -4.45], [105.01, -4.45], [105.01, -4.41]],
            ],
        ]),
        'color' => '#3b82f6',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('map-regions.store'), $regionData);
    $response->assertRedirect(route('map-regions.index'));
    $this->assertDatabaseHas('map_regions', ['code' => '18.12.01', 'name' => 'Kecamatan Tumijajar']);
});

test('super admin can create a map zone', function () {
    $zoneData = [
        'name' => 'Kawasan Industri Tulang Bawang Tengah',
        'zone_type_id' => $this->zoneType->id,
        'geojson' => json_encode([
            'type' => 'Polygon',
            'coordinates' => [
                [[105.02, -4.42], [105.04, -4.42], [105.04, -4.44], [105.02, -4.44], [105.02, -4.42]],
            ],
        ]),
        'description' => 'Zona Industri Utama',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('map-zones.store'), $zoneData);
    $response->assertRedirect(route('map-zones.index'));
    $this->assertDatabaseHas('map_zones', ['name' => 'Kawasan Industri Tulang Bawang Tengah']);
});

test('super admin can create a map location with radius restriction', function () {
    $locationData = [
        'name' => 'Masjid Raya Tubaba',
        'category_id' => $this->category->id,
        'latitude' => -4.4435,
        'longitude' => 105.0456,
        'address' => 'Panaragan Jaya',
        'description' => 'Masjid utama',
        'has_restriction' => '1',
        'restricted_activities' => ['Aktivitas Industri', 'Pembangunan Tempat Hiburan'],
        'restriction_notes' => 'Tidak boleh ada industri dalam radius terdekat',
        'restriction_geojson' => json_encode([
            'type' => 'Polygon',
            'coordinates' => [
                [[105.044, -4.442], [105.047, -4.442], [105.047, -4.445], [105.044, -4.445], [105.044, -4.442]],
            ],
        ]),
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('map-locations.store'), $locationData);
    $response->assertRedirect(route('map-locations.index'));

    $this->assertDatabaseHas('map_locations', ['name' => 'Masjid Raya Tubaba']);
    $this->assertDatabaseHas('map_location_restrictions', [
        'notes' => 'Tidak boleh ada industri dalam radius terdekat',
    ]);
});

test('super admin can create map zone type and location category', function () {
    $catData = [
        'name' => 'Pariwisata',
        'icon' => 'lucide:camera',
        'color' => '#eab308',
    ];
    $response1 = $this->actingAs($this->superAdmin)->post(route('map-location-categories.store'), $catData);
    $response1->assertRedirect(route('map-location-categories.index'));
    $this->assertDatabaseHas('map_location_categories', ['name' => 'Pariwisata', 'slug' => 'pariwisata']);

    $typeData = [
        'name' => 'Perumahan',
        'color' => '#22c55e',
        'description' => 'Zona Pemukiman',
    ];
    $response2 = $this->actingAs($this->superAdmin)->post(route('map-zone-types.store'), $typeData);
    $response2->assertRedirect(route('map-zone-types.index'));
    $this->assertDatabaseHas('map_zone_types', ['name' => 'Perumahan', 'slug' => 'perumahan']);
});

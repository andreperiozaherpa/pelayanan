<?php

namespace Database\Seeders;

use App\Models\MapZoneType;
use Illuminate\Database\Seeder;

class MapZoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Kawasan Industri',
                'slug' => 'industri',
                'color' => '#F97316',
                'description' => 'Wilayah yang diperuntukkan bagi kegiatan industri, manufaktur, dan pergudangan.',
            ],
            [
                'name' => 'Kawasan Pariwisata',
                'slug' => 'pariwisata',
                'color' => '#06B6D4',
                'description' => 'Wilayah destinasi wisata, cagar budaya, rekreasi, dan jasa perhotelan/kuliner.',
            ],
            [
                'name' => 'Kawasan Permukiman',
                'slug' => 'permukiman',
                'color' => '#84CC16',
                'description' => 'Wilayah hunian penduduk perkotaan dan perdesaan beserta fasilitas penunjangnya.',
            ],
            [
                'name' => 'Kawasan Pertanian',
                'slug' => 'pertanian',
                'color' => '#22C55E',
                'description' => 'Lahan pertanian produktif, hortikultura, perkebunan, dan peternakan.',
            ],
            [
                'name' => 'Kawasan Perkantoran & Pemerintahan',
                'slug' => 'perkantoran',
                'color' => '#64748B',
                'description' => 'Pusat layanan pemerintahan daerah, perkantoran swasta, dan instansi publik.',
            ],
            [
                'name' => 'Kawasan Perdagangan & Jasa',
                'slug' => 'perdagangan',
                'color' => '#A855F7',
                'description' => 'Pusat perbelanjaan, pasar tradisional, ruko, dan jasa komersial lainnya.',
            ],
            [
                'name' => 'Kawasan Lindung & Konservasi',
                'slug' => 'konservasi',
                'color' => '#14B8A6',
                'description' => 'Hutan lindung, sempadan sungai, resapan air, dan zona pelestarian alam.',
            ],
        ];

        foreach ($types as $type) {
            MapZoneType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\MapLocationCategory;
use Illuminate\Database\Seeder;

class MapLocationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Masjid & Rumah Ibadah',
                'slug' => 'ibadah',
                'icon' => 'lucide:home', // fallback to lucide icons
                'color' => '#10B981',
            ],
            [
                'name' => 'Sekolah & Pendidikan',
                'slug' => 'pendidikan',
                'icon' => 'lucide:graduation-cap',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Kantor Pemerintahan',
                'slug' => 'pemerintahan',
                'icon' => 'lucide:landmark',
                'color' => '#64748B',
            ],
            [
                'name' => 'Fasilitas Kesehatan',
                'slug' => 'kesehatan',
                'icon' => 'lucide:activity',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Pasar & Pusat Belanja',
                'slug' => 'perbelanjaan',
                'icon' => 'lucide:shopping-bag',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Stasiun Pengisian Bahan Bakar (SPBU)',
                'slug' => 'spbu',
                'icon' => 'lucide:fuel',
                'color' => '#D97706',
            ],
            [
                'name' => 'Bank & ATM',
                'slug' => 'keuangan',
                'icon' => 'lucide:wallet',
                'color' => '#0284C7',
            ],
            [
                'name' => 'Destinasi Wisata',
                'slug' => 'wisata',
                'icon' => 'lucide:compass',
                'color' => '#EC4899',
            ],
        ];

        foreach ($categories as $cat) {
            MapLocationCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}

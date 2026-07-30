<?php

namespace Database\Seeders;

use App\Models\Counter;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    public function run(): void
    {
        $counters = [
            ['code' => 'A', 'name' => 'Loket 1 - Pendaftaran', 'description' => 'Loket pendaftaran awal'],
            ['code' => 'B', 'name' => 'Loket 2 - Pengaduan', 'description' => 'Loket pengaduan masyarakat'],
            ['code' => 'C', 'name' => 'Loket 3 - Informasi', 'description' => 'Loket informasi pelayanan'],
            ['code' => 'D', 'name' => 'Loket 4 - Pengesahan', 'description' => 'Loket pengesahan dokumen'],
            ['code' => 'E', 'name' => 'Loket 5 - Penyerahan', 'description' => 'Loket penyerahan dokumen'],
        ];

        foreach ($counters as $counter) {
            Counter::create($counter);
        }
    }
}

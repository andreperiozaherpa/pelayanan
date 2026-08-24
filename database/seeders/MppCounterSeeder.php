<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Gerai;
use Illuminate\Database\Seeder;

class MppCounterSeeder extends Seeder
{
    public function run(): void
    {
        // Satu loket per gerai aktif (kode loket angka urut tanpa nol depan, mis. 1, 2, 3)
        $gerais = Gerai::where('is_active', true)->orderBy('code')->get();

        foreach ($gerais as $index => $gerai) {
            $code = (string) ($index + 1);

            Counter::updateOrCreate(['code' => $code], [
                'gerai_id' => $gerai->id,
                'name' => 'Loket '.$code.' - '.$gerai->name,
                'location' => 'Lantai 1 Zona '.$code,
                'description' => 'Loket pelayanan '.$gerai->name,
                'is_active' => true,
            ]);
        }
    }
}

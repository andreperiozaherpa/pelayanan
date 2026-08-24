<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Perbarui kode gerai dari prefiks lama "ANJ-" (Anjungan) menjadi "GR-"
     * agar konsisten dengan penamaan Gerai yang sudah diganti.
     */
    public function up(): void
    {
        $gerais = DB::table('mpp_gerais')->where('code', 'LIKE', 'ANJ-%')->get();

        foreach ($gerais as $gerai) {
            DB::table('mpp_gerais')
                ->where('id', $gerai->id)
                ->update(['code' => preg_replace('/^ANJ-/', 'GR-', $gerai->code)]);
        }
    }

    public function down(): void
    {
        $gerais = DB::table('mpp_gerais')->where('code', 'LIKE', 'GR-%')->get();

        foreach ($gerais as $gerai) {
            DB::table('mpp_gerais')
                ->where('id', $gerai->id)
                ->update(['code' => preg_replace('/^GR-/', 'ANJ-', $gerai->code)]);
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kaitkan setiap layanan ke gerai pelayanannya.
     *
     * Setiap layanan wajib punya instansi (opd_id) dan gerai (gerai_id).
     * Kode gerai menentukan huruf awal nomor antrian (mis. gerai "A" → A-001,
     * gerai "AB" → AB-001). Nomor antrian dihitung per gerai sehingga unik
     * untuk semua layanan pada gerai yang sama.
     */
    public function up(): void
    {
        Schema::table('mpp_services', function (Blueprint $table) {
            $table->foreignId('gerai_id')->nullable()->after('opd_id')->constrained('mpp_gerais')->nullOnDelete();
        });

        // Backfill: layanan yang belum punya gerai dipasangkan ke gerai pertama
        // instansinya (satu instansi = satu gerai pada skema lama).
        $services = DB::table('mpp_services')
            ->leftJoin('mpp_gerais', 'mpp_gerais.opd_id', '=', 'mpp_services.opd_id')
            ->select('mpp_services.id', 'mpp_gerais.id as gerai_id')
            ->get();

        $byService = [];
        foreach ($services as $row) {
            if ($row->gerai_id === null) {
                continue;
            }
            $byService[$row->id] ??= $row->gerai_id;
        }

        foreach ($byService as $serviceId => $geraiId) {
            DB::table('mpp_services')->where('id', $serviceId)->update(['gerai_id' => $geraiId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_services', function (Blueprint $table) {
            $table->dropForeign(['gerai_id']);
            $table->dropColumn('gerai_id');
        });
    }
};

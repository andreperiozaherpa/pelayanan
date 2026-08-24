<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kode antrian kini hanya dimiliki oleh gerai (mpp_gerais), bukan
     * pelayanan. Kolom code pada mpp_services tidak lagi diperlukan.
     */
    public function up(): void
    {
        Schema::table('mpp_services', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }

    public function down(): void
    {
        Schema::table('mpp_services', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->unique()->after('name');
        });
    }
};

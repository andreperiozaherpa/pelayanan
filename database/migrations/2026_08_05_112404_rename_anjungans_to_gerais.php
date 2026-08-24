<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mpp_services', function (Blueprint $table) {
            $table->dropForeign(['anjungan_id']);
            $table->renameColumn('anjungan_id', 'gerai_id');
        });

        Schema::rename('mpp_anjungans', 'mpp_gerais');

        Schema::table('mpp_services', function (Blueprint $table) {
            $table->foreign('gerai_id')->references('id')->on('mpp_gerais')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_services', function (Blueprint $table) {
            $table->dropForeign(['gerai_id']);
            $table->renameColumn('gerai_id', 'anjungan_id');
        });

        Schema::rename('mpp_gerais', 'mpp_anjungans');

        Schema::table('mpp_services', function (Blueprint $table) {
            $table->foreign('anjungan_id')->references('id')->on('mpp_anjungans')->nullOnDelete();
        });
    }
};

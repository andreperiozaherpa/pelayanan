<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Konsolidasi relasi instansi (OPD):
     * - mpp_gerais: tambah opd_id (gerai milik instansi)
     * - mpp_services: gerai_id -> opd_id (layanan terhubung ke instansi)
     * - mpp_counters: tambah opd_id (loket milik instansi)
     * - skm_responses: gerai_id -> opd_id (SKM mengarah ke instansi)
     */
    public function up(): void
    {
        Schema::table('mpp_gerais', function (Blueprint $table) {
            $table->foreignId('opd_id')->nullable()->after('id')->constrained('opds')->nullOnDelete();
        });

        Schema::table('mpp_services', function (Blueprint $table) {
            $table->dropForeign(['gerai_id']);
            $table->renameColumn('gerai_id', 'opd_id');
            $table->foreign('opd_id')->references('id')->on('opds')->nullOnDelete();
        });

        Schema::table('mpp_counters', function (Blueprint $table) {
            $table->foreignId('opd_id')->nullable()->after('id')->constrained('opds')->nullOnDelete();
        });

        Schema::table('skm_responses', function (Blueprint $table) {
            $table->dropForeign(['gerai_id']);
            $table->dropIndex(['gerai_id', 'created_at']);
            $table->renameColumn('gerai_id', 'opd_id');
            $table->foreign('opd_id')->references('id')->on('opds')->cascadeOnDelete();

            $table->index(['opd_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skm_responses', function (Blueprint $table) {
            $table->dropIndex(['opd_id', 'created_at']);
            $table->dropForeign(['opd_id']);
            $table->renameColumn('opd_id', 'gerai_id');
            $table->foreign('gerai_id')->references('id')->on('mpp_gerais')->cascadeOnDelete();

            $table->index(['gerai_id', 'created_at']);
        });
        Schema::table('mpp_counters', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->dropColumn('opd_id');
        });

        Schema::table('mpp_services', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->renameColumn('opd_id', 'gerai_id');
            $table->foreign('gerai_id')->references('id')->on('mpp_gerais')->nullOnDelete();
        });

        Schema::table('mpp_gerais', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->dropColumn('opd_id');
        });
    }
};

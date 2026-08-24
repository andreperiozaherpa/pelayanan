<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor loket agar terhubung ke gerai (bukan langsung ke instansi):
     * - mpp_counters.opd_id -> gerai_id (loket milik gerai)
     *
     * Relasi final:
     * - opds (instansi) 1:N  mpp_gerais (gerai milik instansi)
     * - mpp_gerais 1:N        mpp_counters (loket milik gerai)
     * - mpp_services tetap terhubung langsung ke opds (instansi)
     * - skm_responses tetap terhubung langsung ke opds (instansi)
     */
    public function up(): void
    {
        Schema::table('mpp_gerais', function (Blueprint $table) {
            $table->index('opd_id');
        });

        Schema::table('mpp_counters', function (Blueprint $table) {
            $table->foreignId('gerai_id')->nullable()->after('id')->constrained('mpp_gerais')->nullOnDelete();
        });

        // Backfill: loket lama mengikuti gerai milik instansi yang sama.
        DB::table('mpp_counters')
            ->join('mpp_gerais', 'mpp_gerais.opd_id', '=', 'mpp_counters.opd_id')
            ->update(['mpp_counters.gerai_id' => DB::raw('mpp_gerais.id')]);

        Schema::table('mpp_counters', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->dropColumn('opd_id');
        });
    }

    public function down(): void
    {
        Schema::table('mpp_counters', function (Blueprint $table) {
            $table->foreignId('opd_id')->nullable()->after('gerai_id')->constrained('opds')->nullOnDelete();
        });

        DB::table('mpp_counters')
            ->join('mpp_gerais', 'mpp_gerais.id', '=', 'mpp_counters.gerai_id')
            ->update(['mpp_counters.opd_id' => DB::raw('mpp_gerais.opd_id')]);

        Schema::table('mpp_counters', function (Blueprint $table) {
            $table->dropForeign(['gerai_id']);
            $table->dropColumn('gerai_id');
        });

        Schema::table('mpp_gerais', function (Blueprint $table) {
            $table->dropIndex(['opd_id']);
        });
    }
};

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
            $table->foreignId('anjungan_id')->nullable()->constrained('mpp_anjungans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mpp_services', function (Blueprint $table) {
            $table->dropForeign(['anjungan_id']);
            $table->dropColumn('anjungan_id');
        });
    }
};

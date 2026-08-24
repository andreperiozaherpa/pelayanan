<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nomor antrian kini berformat {kode gerai}-{nomor} (mis. GR-001-001),
     * lebih panjang dari format lama {kode layanan}-{nomor} (mis. AB-001).
     * Kolom diperlebar agar tidak terpotong untuk kode gerai yang panjang.
     */
    public function up(): void
    {
        Schema::table('mpp_queues', function (Blueprint $table) {
            $table->string('number', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_queues', function (Blueprint $table) {
            $table->string('number', 10)->change();
        });
    }
};

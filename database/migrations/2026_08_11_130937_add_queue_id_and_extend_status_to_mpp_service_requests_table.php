<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mpp_service_requests', function (Blueprint $table) {
            $table->foreignId('queue_id')->nullable()->after('nomor_antrian')->constrained('mpp_queues')->nullOnDelete();
        });

        // Backfill: link request lama ke tiket yang cocok berdasarkan (mpp_service_id, nomor_antrian).
        DB::statement('
            UPDATE mpp_service_requests r
            LEFT JOIN mpp_queues q
                ON q.service_id = r.mpp_service_id
                AND q.number = r.nomor_antrian
            SET r.queue_id = q.id
            WHERE r.queue_id IS NULL
        ');

        Schema::table('mpp_service_requests', function (Blueprint $table) {
            $table->enum('status', ['PENDING', 'PROCESSED', 'COMPLETED', 'REJECTED'])->default('PENDING')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_service_requests', function (Blueprint $table) {
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING')->change();
        });

        Schema::table('mpp_service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('queue_id');
        });
    }
};

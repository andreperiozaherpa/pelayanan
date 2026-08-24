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
        Schema::table('mpp_queues', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('id')->constrained('mpp_services')->cascadeOnDelete();
            $table->foreignId('fo_petugas_id')->nullable()->after('service_id')->constrained('users')->nullOnDelete();
            $table->foreignId('gerai_petugas_id')->nullable()->after('fo_petugas_id')->constrained('users')->nullOnDelete();
            $table->text('alasan_reject')->nullable()->after('notes');
            $table->timestamp('skipped_at')->nullable()->after('alasan_reject');

            $table->index(['service_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_queues', function (Blueprint $table) {
            $table->dropIndex(['service_id', 'created_at']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['fo_petugas_id']);
            $table->dropForeign(['gerai_petugas_id']);
            $table->dropColumn(['service_id', 'fo_petugas_id', 'gerai_petugas_id', 'alasan_reject', 'skipped_at']);
        });
    }
};

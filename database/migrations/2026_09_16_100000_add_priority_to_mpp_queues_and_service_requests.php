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
            $table->boolean('is_priority')->default(false)->after('alasan_reject');
            $table->string('priority_type')->nullable()->after('is_priority');

            $table->index(['is_priority', 'status', 'created_at']);
        });

        Schema::table('mpp_service_requests', function (Blueprint $table) {
            $table->boolean('is_priority')->default(false)->after('status');
            $table->string('priority_type')->nullable()->after('is_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_queues', function (Blueprint $table) {
            $table->dropIndex(['is_priority', 'status', 'created_at']);
            $table->dropColumn(['is_priority', 'priority_type']);
        });

        Schema::table('mpp_service_requests', function (Blueprint $table) {
            $table->dropColumn(['is_priority', 'priority_type']);
        });
    }
};

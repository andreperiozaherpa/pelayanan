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
            $table->foreignId('mpp_ticket_template_version_id')
                ->nullable()
                ->after('service_id')
                ->constrained('mpp_ticket_template_versions')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_queues', function (Blueprint $table) {
            $table->dropForeign(['mpp_ticket_template_version_id']);
            $table->dropColumn('mpp_ticket_template_version_id');
        });
    }
};

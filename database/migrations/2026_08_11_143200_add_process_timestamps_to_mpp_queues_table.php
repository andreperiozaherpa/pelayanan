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
            $table->timestamp('fo_called_at')->nullable()->after('called_at');
            $table->timestamp('fo_finished_at')->nullable()->after('fo_called_at');
            $table->timestamp('gerai_called_at')->nullable()->after('fo_finished_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpp_queues', function (Blueprint $table) {
            $table->dropColumn(['fo_called_at', 'fo_finished_at', 'gerai_called_at']);
        });
    }
};

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
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('service_type')->nullable()->change();
            $table->string('citizen_nik', 16)->nullable()->change();
            $table->foreignId('mpp_service_id')->nullable()->constrained('mpp_services')->onDelete('set null');
            $table->json('submitted_form_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('service_type')->nullable(false)->change();
            $table->string('citizen_nik', 16)->nullable(false)->change();
            $table->dropConstrainedForeignId('mpp_service_id');
            $table->dropColumn('submitted_form_data');
        });
    }
};

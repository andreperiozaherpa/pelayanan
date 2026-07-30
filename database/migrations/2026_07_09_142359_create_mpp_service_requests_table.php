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
        // Create dedicated MPP service requests table (if not already created)
        if (! Schema::hasTable('mpp_service_requests')) {
            Schema::create('mpp_service_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mpp_service_id')->constrained('mpp_services')->onDelete('cascade');
                $table->foreignId('front_office_user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->json('submitted_form_data')->nullable();
                $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Remove MPP-specific columns from service_requests (they now have their own table)
        if (Schema::hasColumn('service_requests', 'mpp_service_id')) {
            Schema::table('service_requests', function (Blueprint $table) {
                $table->dropConstrainedForeignId('mpp_service_id');
            });
        }

        if (Schema::hasColumn('service_requests', 'submitted_form_data')) {
            Schema::table('service_requests', function (Blueprint $table) {
                $table->dropColumn('submitted_form_data');
            });
        }

        // Restore service_type and citizen_nik to NOT NULL
        // Only if they are currently nullable (check actual data first)
        DB::table('service_requests')->whereNull('citizen_nik')->delete();
        DB::table('service_requests')->whereNull('service_type')->delete();

        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('service_type')->nullable(false)->change();
            $table->string('citizen_nik', 16)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('service_type')->nullable()->change();
            $table->string('citizen_nik', 16)->nullable()->change();
            $table->foreignId('mpp_service_id')->nullable()->constrained('mpp_services')->onDelete('set null');
            $table->json('submitted_form_data')->nullable();
        });

        Schema::dropIfExists('mpp_service_requests');
    }
};

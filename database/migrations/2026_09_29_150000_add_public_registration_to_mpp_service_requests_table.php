<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mpp_service_requests', function (Blueprint $table): void {
            $table->string('public_registration_code', 64)->nullable()->unique()->after('queue_id');
            $table->timestamp('registered_at')->nullable()->after('public_registration_code');
            $table->timestamp('claimed_at')->nullable()->after('registered_at');
        });
    }

    public function down(): void
    {
        Schema::table('mpp_service_requests', function (Blueprint $table): void {
            $table->dropUnique(['public_registration_code']);
            $table->dropColumn(['public_registration_code', 'registered_at', 'claimed_at']);
        });
    }
};

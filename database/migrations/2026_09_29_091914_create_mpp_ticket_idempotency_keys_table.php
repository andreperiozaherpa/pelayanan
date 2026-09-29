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
        Schema::create('mpp_ticket_idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->string('operation', 32);
            $table->string('key', 128);
            $table->string('payload_hash', 64);
            $table->foreignId('service_id')->constrained('mpp_services')->cascadeOnDelete();
            $table->foreignId('queue_id')->nullable()->constrained('mpp_queues')->nullOnDelete();
            $table->foreignId('mpp_service_request_id')->nullable()->constrained('mpp_service_requests')->nullOnDelete();
            $table->unique(['operation', 'key']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpp_ticket_idempotency_keys');
    }
};

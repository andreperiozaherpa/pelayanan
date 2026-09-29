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
        Schema::create('mpp_ticket_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Template Tiket MPP');
            $table->json('draft_layout');
            $table->foreignId('active_version_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpp_ticket_templates');
    }
};

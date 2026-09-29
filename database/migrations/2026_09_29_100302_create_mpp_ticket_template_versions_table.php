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
        if (Schema::hasTable('mpp_ticket_template_versions')) {
            Schema::table('mpp_ticket_template_versions', function (Blueprint $table) {
                $table->unique(['mpp_ticket_template_id', 'version'], 'mpp_ticket_template_version_unique');
            });

            return;
        }

        Schema::create('mpp_ticket_template_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mpp_ticket_template_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->json('layout');
            $table->string('checksum', 64);
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at');
            $table->timestamps();
            $table->unique(['mpp_ticket_template_id', 'version'], 'mpp_ticket_template_version_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpp_ticket_template_versions');
    }
};

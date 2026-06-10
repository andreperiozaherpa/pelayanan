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
        Schema::create('map_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->index();
            $table->enum('level', ['kabupaten', 'kecamatan', 'desa']);
            $table->foreignId('parent_id')->nullable()->constrained('map_regions')->nullOnDelete();
            $table->json('geojson')->nullable();
            $table->string('color', 7)->nullable();
            $table->decimal('area_km2', 12, 4)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_regions');
    }
};

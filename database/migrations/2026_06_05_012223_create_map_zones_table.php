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
        Schema::create('map_zone_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 7);
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('map_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('zone_type_id')->constrained('map_zone_types')->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('map_regions')->nullOnDelete();
            $table->json('geojson');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_zones');
        Schema::dropIfExists('map_zone_types');
    }
};

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
        Schema::create('map_location_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon');
            $table->string('color', 7)->nullable();
            $table->timestamps();
        });

        Schema::create('map_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('map_location_categories')->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('map_regions')->nullOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('map_location_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('map_locations')->cascadeOnDelete();
            $table->json('geojson');
            $table->json('restricted_activities');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_location_restrictions');
        Schema::dropIfExists('map_locations');
        Schema::dropIfExists('map_location_categories');
    }
};

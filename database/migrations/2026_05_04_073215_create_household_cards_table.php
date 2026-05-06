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
        Schema::create('household_cards', function (Blueprint $col) {
            $col->string('no_kk', 16)->primary();
            $col->string('head_name');
            $col->text('address'); // Will be encrypted at app level
            $col->string('rt', 3)->nullable();
            $col->string('rw', 3)->nullable();
            $col->foreignId('village_id')->constrained('villages')->onDelete('cascade');
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_cards');
    }
};

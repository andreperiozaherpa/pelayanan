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
        Schema::create('skm_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gerai_id')->constrained('mpp_gerais')->cascadeOnDelete();
            $table->string('nama')->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->unsignedTinyInteger('umur')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->unsignedTinyInteger('u1');
            $table->unsignedTinyInteger('u2');
            $table->unsignedTinyInteger('u3');
            $table->unsignedTinyInteger('u4');
            $table->unsignedTinyInteger('u5');
            $table->unsignedTinyInteger('u6');
            $table->unsignedTinyInteger('u7');
            $table->unsignedTinyInteger('u8');
            $table->unsignedTinyInteger('u9');
            $table->text('saran')->nullable();
            $table->string('source')->default('web');
            $table->timestamps();

            $table->index(['gerai_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skm_responses');
    }
};

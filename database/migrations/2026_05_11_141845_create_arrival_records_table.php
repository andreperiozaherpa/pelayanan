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
        Schema::create('arrival_records', function (Blueprint $table) {
            $table->id();
            $table->string('citizen_nik', 16);
            $table->enum('status', ['ACTIVE', 'PENDING', 'REJECTED'])->default('ACTIVE');
            $table->text('previous_address'); // Encrypted at model level
            $table->date('arrival_date');
            $table->foreignId('recorded_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('citizen_nik')->references('nik')->on('citizens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrival_records');
    }
};

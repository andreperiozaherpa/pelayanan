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
        Schema::create('death_records', function (Blueprint $table) {
            $table->id();
            $table->string('citizen_nik', 16)->unique();
            $table->string('status')->default('PENDING'); // PENDING, ACTIVE, REJECTED, EXPIRED
            $table->date('date_of_death')->nullable();
            $table->string('cause_of_death')->nullable();
            $table->string('place_of_death')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->string('source')->default('FRONT_OFFICE_REQUEST');
            $table->timestamps();

            $table->foreign('citizen_nik')->references('nik')->on('citizens')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('death_records');
    }
};

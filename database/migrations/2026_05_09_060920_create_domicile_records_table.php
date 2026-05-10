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
        Schema::create('domicile_records', function (Blueprint $table) {
            $table->id();
            $table->string('citizen_nik', 16)->index();
            $table->enum('status', ['ACTIVE', 'EXPIRED', 'PENDING', 'REJECTED'])->default('PENDING');
            $table->string('signed_pdf_path')->nullable();
            $table->text('purpose')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->string('source')->nullable();
            $table->timestamps();

            $table->foreign('citizen_nik')->references('nik')->on('citizens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicile_records');
    }
};

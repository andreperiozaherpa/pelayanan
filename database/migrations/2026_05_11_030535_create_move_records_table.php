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
        Schema::create('move_records', function (Blueprint $table) {
            $table->id();
            $table->string('citizen_nik', 16)->index();
            $table->enum('status', ['ACTIVE', 'EXPIRED', 'PENDING', 'REJECTED'])->default('PENDING');
            $table->text('destination_address')->nullable();
            $table->string('reason')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->date('valid_until')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->foreign('citizen_nik')->references('nik')->on('citizens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('move_records');
    }
};

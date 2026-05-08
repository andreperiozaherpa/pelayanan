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
        Schema::table('poverty_records', function (Blueprint $table) {
            $table->text('income_range')->nullable()->change();
            $table->date('valid_from')->nullable()->change();
            $table->date('valid_until')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poverty_records', function (Blueprint $table) {
            $table->text('income_range')->nullable(false)->change();
            $table->date('valid_from')->nullable(false)->change();
            $table->date('valid_until')->nullable(false)->change();
        });
    }
};

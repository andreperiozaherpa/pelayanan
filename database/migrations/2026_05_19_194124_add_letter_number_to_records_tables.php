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
            $table->string('letter_number')->nullable()->after('status');
        });

        Schema::table('domicile_records', function (Blueprint $table) {
            $table->string('letter_number')->nullable()->after('status');
        });

        Schema::table('death_records', function (Blueprint $table) {
            $table->string('letter_number')->nullable()->after('status');
        });

        Schema::table('move_records', function (Blueprint $table) {
            $table->string('letter_number')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poverty_records', function (Blueprint $table) {
            $table->dropColumn('letter_number');
        });

        Schema::table('domicile_records', function (Blueprint $table) {
            $table->dropColumn('letter_number');
        });

        Schema::table('death_records', function (Blueprint $table) {
            $table->dropColumn('letter_number');
        });

        Schema::table('move_records', function (Blueprint $table) {
            $table->dropColumn('letter_number');
        });
    }
};

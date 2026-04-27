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
        Schema::table('villages', function (Blueprint $table) {
            $table->foreignId('district_id')->after('id')->nullable()->constrained('districts')->onDelete('restrict');
            $table->dropColumn('district_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->string('district_name')->after('code')->nullable();
            $table->dropForeign(['district_id']);
            $table->dropColumn('district_id');
        });
    }
};

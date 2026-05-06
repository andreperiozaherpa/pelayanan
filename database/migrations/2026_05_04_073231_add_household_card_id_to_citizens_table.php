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
        Schema::table('citizens', function (Blueprint $table) {
            $table->string('household_card_id', 16)->nullable()->after('nik');
            $table->foreign('household_card_id')
                ->references('no_kk')
                ->on('household_cards')
                ->onDelete('set null');

            $table->index('household_card_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizens', function (Blueprint $table) {
            $table->dropForeign(['household_card_id']);
            $table->dropColumn('household_card_id');
        });
    }
};

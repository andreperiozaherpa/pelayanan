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
        Schema::table('cms_menus', function (Blueprint $table) {
            $table->foreignId('cms_page_id')->nullable()->after('parent_id')->constrained('cms_pages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_menus', function (Blueprint $table) {
            $table->dropForeign(['cms_page_id']);
            $table->dropColumn('cms_page_id');
        });
    }
};

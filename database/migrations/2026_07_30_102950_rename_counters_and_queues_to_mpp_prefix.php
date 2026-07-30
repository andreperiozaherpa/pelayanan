<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('counters', 'mpp_counters');
        Schema::rename('queues', 'mpp_queues');
    }

    public function down(): void
    {
        Schema::rename('mpp_counters', 'counters');
        Schema::rename('mpp_queues', 'queues');
    }
};

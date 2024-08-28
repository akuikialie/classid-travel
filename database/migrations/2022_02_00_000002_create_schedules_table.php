<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->date('departure_date');
            $table->boolean('is_active')->default(true);
            tableTimestamps($table);
            tableSoftDeletes($table);

            $table->unique(['tenant_id', 'departure_date'], 'schedules_main_unique');
            $table->index(['tenant_id', 'departure_date', 'is_active'], 'schedules_main_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

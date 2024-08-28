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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->string('name', 50);
            $table->string('type', 15);
            $table->boolean('is_active')->default(true);
            tableTimestamps($table);
            tableSoftDeletes($table);

            $table->unique(['tenant_id', 'name'], 'facilities_main_unique');
            $table->index(['tenant_id', 'type', 'name', 'is_active'], 'facilities_main_index');
        });

        Schema::create('model_has_facility', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            $table->foreignId('plan_facility_id')->nullable()
                ->constrained('facilities')
                ->onDelete('restrict');
            $table->morphs('model');
            tableTimestamps($table);

            $table->unique(['plan_facility_id', 'model_type', 'model_id'], 'model_has_facility_main_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_facility');
        Schema::dropIfExists('facilities');
    }
};

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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->string('name', 50);
            $table->unsignedInteger('roaming_in_destination')->nullable()->comment('waktu jelajah di tempat tujuan');
            $table->boolean('is_active')->default(true);
            tableTimestamps($table);
            tableSoftDeletes($table);

            $table->unique(['tenant_id', 'name'], 'destinations_main_unique');
            $table->index(['tenant_id', 'is_active', 'name'], 'destinations_main_index');
        });

        Schema::create('model_has_destination', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            $table->foreignId('destination_id')->nullable()
                ->constrained('destinations')
                ->onDelete('restrict');
            $table->morphs('model');
            tableTimestamps($table);

            $table->unique(['destination_id', 'model_type', 'model_id'], 'model_has_destination_main_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_destination');
        Schema::dropIfExists('destinations');
    }
};

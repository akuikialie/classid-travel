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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->morphs('model');
            $table->string('name', 50)->comment('keterangan kegiatan untuk hari');
            $table->integer('day');
            tableTimestamps($table);
            tableSoftDeletes($table);

            $table->unique(['tenant_id', 'name'], 'itineraries_main_unique');
            $table->index(['tenant_id', 'day'], 'itineraries_main_index');
            $table->index(['name'], 'itineraries_content_index');
        });

        Schema::create('itinerary_activities', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->string('activity', '100')->comment('Nama aktifitas');
            $table->text('detail')->nullable();
            tableTimestamps($table);
            tableSoftDeletes($table);

            $table->index(['tenant_id', 'activity'], 'itinerary_activities_main_index');
        });

        Schema::create('model_has_itinerary_activity', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->foreignId('itinerary_activity_id')->nullable()
                ->constrained('itinerary_activities')
                ->onDelete('restrict');
            $table->morphs('model');
            $table->time('time')->nullable();
            tableTimestamps($table);

            $table->index(['tenant_id', 'itinerary_activity_id', 'time'], 'model_has_itinerary_activity_main_index');
            $table->unique(['itinerary_activity_id', 'model_type', 'model_id'], 'model_has_itinerary_activity_main_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_itinerary_activity');
        Schema::dropIfExists('itinerary_activities');
        Schema::dropIfExists('itineraries');
    }
};

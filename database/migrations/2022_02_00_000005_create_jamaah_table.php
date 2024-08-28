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
        Schema::create('jamaah', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            tableUserId($table);
            $table->foreignId('departure_city_id')->nullable()
                ->comment('tempat keberangkatan link ke city_id')
                ->constrained('users')
                ->onDelete('restrict');
            $table->foreignId('schedule_id')->nullable()
                ->comment('link ke schedules_table, untuk memilih waktu keberangkatan')
                ->constrained('users')
                ->onDelete('restrict');
            tableTimestamps($table);
            tableSoftDeletes($table);
        });

        Schema::create('jamaah_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            tableTenantId($table);
            $table->foreignId('jamaah_id')->nullable()
                ->constrained('jamaah')
                ->onDelete('restrict');
            $table->string('departure_status')->default(\App\Enums\DepartureStatus::BELUM_BERANGKAT->keyValue())->comment('Status keberangkatan jamaah, DepartureStatus::class');
            $table->text('detail')->nullable()->comment('detail status keberangkatan jamaah');
            tableTimestamps($table, precision: 6);
            tableSoftDeletes($table, precision: 6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaah_histories');
        Schema::dropIfExists('jamaah');
    }
};

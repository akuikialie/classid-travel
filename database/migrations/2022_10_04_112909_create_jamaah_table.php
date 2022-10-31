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
    public function up()
    {
        Schema::create('jamaah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->comment('reference to tenant_table');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('departure_city_id')->nullable()->comment('tempat keberangkatan link ke city_id');
            $table->unsignedBigInteger('schedule_id')->nullable()->comment('link ke schedules_table, untuk memilih waktu keberangkatan');
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);

            /* foreign key */
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::dropIfExists('jamaah');
    }
};

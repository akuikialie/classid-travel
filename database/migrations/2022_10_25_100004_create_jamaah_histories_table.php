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
        Schema::create('jamaah_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jamaah_id')->comment('reference to jamaah_table');
            $table->string('departure_status')->default(\App\Enums\DepartureStatus::BELUM_BERANGKAT->keyValue())->comment('Status keberangkatan jamaah, DepartureStatus::class');
            $table->text('detail')->nullable()->comment('detail status keberangkatan jamaah');
            $table->timestamps();

            /*  foreign key */
            $table->foreign('jamaah_id')->on('jamaah')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jamaah_histories');
    }
};

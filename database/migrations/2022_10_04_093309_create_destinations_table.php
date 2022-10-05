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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('destination_name', 50);
            $table->unsignedInteger('roaming_in_destination')->comment('waktu jelajah di tempat tujuan');
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('model_has_destination', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 50);
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('destination_id')->comment('reference to destinations_table');

            $table->timestamps();

            /* foreign keys */
            $table->foreign('destination_id')->on('destinations')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_destination');
        Schema::dropIfExists('destinations');
    }
};

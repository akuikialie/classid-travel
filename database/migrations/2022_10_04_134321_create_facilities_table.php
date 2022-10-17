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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('type', 15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('model_has_facility', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 50);
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('plan_facility_id')->comment('reference to facilities_table');

            $table->timestamps();

            /* foreign keys */
            $table->foreign('plan_facility_id')->on('facilities')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_facility');
        Schema::dropIfExists('facilities');
    }
};

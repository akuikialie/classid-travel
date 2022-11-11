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
        Schema::create('itinerary_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('activity', '100')->comment('Nama aktifitas');
            $table->text('detail')->nullable();
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);
        });

        Schema::create('model_has_itinerary_activity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('itinerary_activity_id');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type', 50);
            $table->time('time', precision: 6)->nullable();

            $table->timestamp('created_at')->useCurrent();

            /* foreign keys */
            $table->foreign('itinerary_activity_id')->on('itinerary_activities')
                ->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_itinerary_activity');
        Schema::dropIfExists('itinerary_activities');
    }
};

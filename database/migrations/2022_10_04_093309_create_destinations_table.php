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
            $table->unsignedBigInteger('tenant_id')->comment('reference to tenant_table');
            $table->string('name', 50);
            $table->unsignedInteger('roaming_in_destination')->nullable()->comment('waktu jelajah di tempat tujuan');
            $table->boolean('is_active')->default(true);
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);

        });

        Schema::create('model_has_destination', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id')->comment('reference to destinations_table');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type', 50);

            $table->timestamps(precision: 6);

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

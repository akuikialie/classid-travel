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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('app_domain');
            $table->string('BCN')->unique()->comment('Bank Code Number');
            $table->string('locale')->default('id_ID');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->timestamps(6);
            $table->softDeletes(precision: 6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
};

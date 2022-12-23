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
        Schema::table('plan_packages', function (Blueprint $table){
            $table->unique(['name', 'tenant_id']);
        });
        Schema::table('destinations', function (Blueprint $table){
            $table->unique(['name', 'tenant_id']);
        });
        Schema::table('facilities', function (Blueprint $table){
            $table->unique(['name', 'tenant_id']);
        });
        Schema::table('schedules', function (Blueprint $table){
            $table->unique(['departure_date', 'tenant_id']);
        });
        Schema::table('itineraries', function (Blueprint $table) {
            $table->unique(['name', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

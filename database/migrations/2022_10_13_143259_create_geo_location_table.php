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
        $this->geoProvince();
        $this->geoCity();
        $this->geoDistrict();
        $this->geoSubDistrict();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo_subdistricts');
        Schema::dropIfExists('geo_districts');
        Schema::dropIfExists('geo_cities');
        Schema::dropIfExists('geo_provinces');
    }

    public function geoProvince(): void
    {
        Schema::create('geo_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('admin_code');
            $table->unsignedBigInteger('wilayah_code');
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);
        });
    }

    public function geoCity(): void
    {
        Schema::create('geo_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->string('name');
            $table->string('postal_code', 5)->nullable();
            $table->string('latitude', 40)->nullable();
            $table->string('longitude', 40)->nullable();
            $table->string('admin_code')->nullable();
            $table->unsignedBigInteger('wilayah_code')->nullable();
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);

            /* foreign key */
            $table->foreign('province_id')->on('geo_provinces')->references('id')->onDelete('cascade');
        });

    }

    public function geoDistrict(): void
    {
        Schema::create('geo_districts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('name');
            $table->string('admin_code');
            $table->unsignedBigInteger('wilayah_code');
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);

             /* foreign key */
             $table->foreign('city_id')->on('geo_cities')->references('id')->onDelete('cascade');
        });
    }

    public function geoSubDistrict(): void
    {
        Schema::create('geo_subdistricts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('district_id');
            $table->string('name');
            $table->string('admin_code');
            $table->unsignedBigInteger('wilayah_code');
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);

             /* foreign key */
             $table->foreign('district_id')->on('geo_districts')->references('id')->onDelete('cascade');
        });
    }
};

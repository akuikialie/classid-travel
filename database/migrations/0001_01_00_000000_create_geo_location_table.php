<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // reset all data first
        $tables = ['geo_sub_districts', 'geo_districts', 'geo_cities', 'geo_provinces', 'geo_countries', 'geo_temporary'];
        foreach ($tables as $table) {
            try {
                DB::unprepared("truncate table only {$table} restart identity cascade;");
            } catch (Exception $e) {
                if (app()->hasDebugModeEnabled()) {
                    app('log')->error($e->getMessage());
                }
            }
        }
        DB::commit();

        $this->geoCountry();
        $this->geoProvince();
        $this->geoCity();
        $this->geoDistrict();
        $this->geoSubDistrict();
        $this->geoTemporary();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_subdistricts');
        Schema::dropIfExists('geo_districts');
        Schema::dropIfExists('geo_cities');
        Schema::dropIfExists('geo_provinces');
    }

    public function geoCountry(): void
    {
        Schema::create('geo_countries', function (Blueprint $table) {
            $xTable = new \App\Core\Database\Eloquent\XBlueprint($table);

            $table->id();
            $xTable->hashId();
            $table->string('iso2', 10)->nullable()->unique();
            $table->string('iso3', 20)->nullable()->unique();
            $table->string('name');
            $xTable->locale(true);
            $this->location($table);
            $xTable->timestamps(constrained: false);
            $xTable->softDeletes(constrained: false);

            $table->index(['iso2', 'iso3', 'name'], 'geo_countries_main_index');
            $table->index(['longitude', 'latitude', 'altitude'], 'geo_countries_location_index');
        });
    }

    public function geoProvince(): void
    {
        Schema::create('geo_provinces', function (Blueprint $table) {
            $xTable = new \App\Core\Database\Eloquent\XBlueprint($table);

            $table->id();
            $xTable->hashId();
            $xTable->geo('country');
            $table->string('code', 50)->nullable();
            $table->string('local_code', 50)->nullable();
            $table->string('name');
            $table->string('postal_code')->nullable();
            $xTable->locale(true);
            $this->location($table);
            $xTable->timestamps(constrained: false);
            $xTable->softDeletes(constrained: false);

            $table->index(['country_id'], 'geo_provinces_parent_index');
            $table->index(['code', 'local_code', 'name', 'postal_code'], 'geo_provinces_main_index');
            $table->index(['longitude', 'latitude', 'altitude'], 'geo_provinces_location_index');
        });
    }

    public function geoCity(): void
    {
        Schema::create('geo_cities', function (Blueprint $table) {
            $xTable = new \App\Core\Database\Eloquent\XBlueprint($table);

            $table->id();
            $xTable->hashId();
            $xTable->geo('country', 'province');
            $table->string('code', 50)->nullable();
            $table->string('local_code', 50)->nullable();
            $table->string('name');
            $table->string('postal_code')->nullable();
            $this->location($table);
            $xTable->timestamps(constrained: false);
            $xTable->softDeletes(constrained: false);

            $table->index(['country_id', 'province_id'], 'geo_cities_parent_index');
            $table->index(['code', 'local_code', 'name', 'postal_code'], 'geo_cities_main_index');
            $table->index(['longitude', 'latitude', 'altitude'], 'geo_cities_location_index');
        });

    }

    public function geoDistrict(): void
    {
        Schema::create('geo_districts', function (Blueprint $table) {
            $xTable = new \App\Core\Database\Eloquent\XBlueprint($table);

            $table->id();
            $xTable->hashId();
            $xTable->geo('country', 'province', 'city');
            $table->string('code', 50)->nullable();
            $table->string('local_code', 50)->nullable();
            $table->string('name');
            $table->string('postal_code')->nullable();
            $this->location($table);
            $xTable->timestamps(constrained: false);
            $xTable->softDeletes(constrained: false);

            $table->index(['country_id', 'province_id', 'city_id'], 'geo_districts_parent_index');
            $table->index(['code', 'local_code', 'name', 'postal_code'], 'geo_districts_main_index');
            $table->index(['longitude', 'latitude', 'altitude'], 'geo_districts_location_index');
        });
    }

    public function geoSubDistrict(): void
    {
        Schema::create('geo_sub_districts', function (Blueprint $table) {
            $xTable = new \App\Core\Database\Eloquent\XBlueprint($table);

            $table->id();
            $xTable->hashId();
            $xTable->geo('country', 'province', 'city', 'district');
            $table->string('code', 50)->nullable();
            $table->string('local_code', 50)->nullable();
            $table->string('name');
            $table->string('postal_code')->nullable();
            $this->location($table);
            $xTable->timestamps(constrained: false);
            $xTable->softDeletes(constrained: false);

            $table->index(['country_id', 'province_id', 'city_id', 'district_id'], 'geo_sub_districts_parent_index');
            $table->index(['code', 'local_code', 'name', 'postal_code'], 'geo_sub_districts_main_index');
            $table->index(['longitude', 'latitude', 'altitude'], 'geo_sub_districts_location_index');
        });
    }

    public function geoTemporary(): void
    {
        Schema::create('geo_temporary', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('parent_id')->nullable();
            $table->string('type');
            $table->string('sub_type');
            $table->string('name');
            $table->jsonb('additional')->nullable();
            $table->jsonb('raw')->nullable();

            $table->index(['parent_id', 'type', 'sub_type'], 'geo_temporary_main_index');
        });
    }

    private function location(Blueprint $table): void
    {
        $table->string('longitude')->nullable();
        $table->string('latitude')->nullable();
        $table->string('altitude')->nullable();
    }
};

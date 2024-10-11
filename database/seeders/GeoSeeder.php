<?php

namespace Database\Seeders;

use App\Models\Geo\City;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Fluent;

class GeoSeeder extends Seeder
{
    private static Fluent $country;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        self::$country = new Fluent();

        $this->command->outputComponents()->task('Cleaning geo data', fn () => $this->cleanAllData());
        if (File::exists(database_path("data/geo_sub_districts.sql"))) {
            $this->seedGeoData();
        } else {
            $this->command->outputComponents()
                ->task('Seeding temporary geo data', fn() => $this->seedTemporary());
            $this->command->outputComponents()
                ->task('Seeding Countries', fn() => $this->seedCountry());
            $this->command->outputComponents()
                ->task('Seeding Provinces until Sub districts (may take long time)', fn() => $this->seedGeo());
        }
    }

    private function cleanAllData(): void
    {
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
    }

    private function seedTemporary(): void
    {
        $sql = File::get(database_path("data/geo_temp.sql"));
        DB::unprepared($sql);
        DB::commit();
    }

    private function seedGeoData(): void
    {
        $geos = ['countries', 'provinces', 'cities', 'districts', 'sub_districts'];
        foreach ($geos as $geo) {
            $this->command->outputComponents()
                ->task("Seeding geo {$geo} data", function () use ($geo) {
                    try {
                        $sql = File::get(database_path("data/geo_{$geo}.sql"));
                        DB::unprepared($sql);
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                    }
                });
        }
    }

    private function seedCountry(): void
    {
        if (($open = fopen(database_path("data/geo_countries.csv"), "r")) !== false) {
            $countries = [];
            while (($row = fgetcsv($open, separator: ",")) !== false) {
                if ($row[2] != 'ISO3') {
                    $now = now();
                    $countries[] = [
                        'iso3' => "{$row[2]}",
                        'iso2' => "{$row[1]}",
                        'name' => "{$row[0]}",
                        'timezone' => "{$row[11]}",
                        'locale' => "{$row[13]}",
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (count($countries) == 100) {
                    DB::table('geo_countries')->insert($countries);
                    $countries = [];
                }
            }

            fclose($open);

            $country = DB::table('geo_countries')
                ->where('iso2', 'ID')
                ->first();

            self::$country = new Fluent($country);
        }
    }

    private function seedGeo(): void
    {
        $temps = DB::table('geo_temporary')
            ->select('id', 'parent_id', 'type', 'sub_type', 'name', 'additional')
            ->orderBy('parent_id')
            ->orderBy('id')
            // ->limit(2)
            ->get();

        // of course this is province
        $temps
            ->filter(fn ($it) => $it->type == 'province')
            ->each(function ($province) use ($temps) {
                $additional = new Fluent($province->additional ? json_decode($province->additional) : []);
                $parentIds = [
                    'country_id' => self::$country->get('id'),
                    'country_name' => self::$country->get('name'),
                ];
                $id = DB::table('geo_provinces')->insertGetId(array_merge($parentIds, [
                    'code' => $province->id,
                    'local_code' => str($province->id)->replaceMatches('/\D+/', ''),
                    'name' => $province->name,
                    'postal_code' => $additional->get('postal_code'),
                ]));
                $parentIds['province_id'] = $id;
                $parentIds['province_name'] = $province->name;

                // city
                $temps
                    ->filter(fn ($it) => $it->type == 'city' && $it->parent_id == $province->id)
                    ->each(function ($city) use ($temps, $parentIds) {
                        $additional = new Fluent($city->additional ? json_decode($city->additional) : []);
                        $id = DB::table('geo_cities')->insertGetId(array_merge($parentIds, [
                            'code' => $city->id,
                            'local_code' => str($city->id)->replaceMatches('/\D+/', ''),
                            'name' => $city->name,
                            'postal_code' => $additional->get('postal_code'),
                        ]));
                        $parentIds['city_id'] = $id;
                        $parentIds['city_name'] = $city->name;

                        // district
                        $temps
                            ->filter(fn ($it) => $it->type == 'district' && $it->parent_id == $city->id)
                            ->each(function ($district) use ($temps, $parentIds) {
                                $additional = new Fluent($district->additional ? json_decode($district->additional) : []);
                                $id = DB::table('geo_districts')->insertGetId(array_merge($parentIds, [
                                    'code' => $district->id,
                                    'local_code' => str($district->id)->replaceMatches('/\D+/', ''),
                                    'name' => $district->name,
                                    'postal_code' => $additional->get('postal_code'),
                                ]));
                                $parentIds['district_id'] = $id;
                                $parentIds['district_name'] = $district->name;

                                // sub-district
                                $temps
                                    ->filter(fn ($it) => $it->type == 'sub_district' && $it->parent_id == $district->id)
                                    ->each(function ($subDistrict) use ($temps, $parentIds) {
                                        $additional = new Fluent($subDistrict->additional ? json_decode($subDistrict->additional) : []);
                                        DB::table('geo_sub_districts')->insertGetId(array_merge($parentIds, [
                                            'code' => $subDistrict->id,
                                            'local_code' => str($subDistrict->id)->replaceMatches('/\D+/', ''),
                                            'name' => $subDistrict->name,
                                            'postal_code' => $additional->get('postal_code'),
                                        ]));
                                    });
                            });
                    });
            });
    }
}

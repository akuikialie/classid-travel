<?php

namespace Database\Seeders;

use App\Models\Geo\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedCity extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cities = [
            'name' => 'Surabaya',
            'name' => 'Jakarta',
        ];
        foreach ($cities as $city){
            City::query()->create([
                'name' => $city,
            ]);
        }
    }
}

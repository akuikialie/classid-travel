<?php

namespace Database\Seeders;

use App\Models\Destination\Destination;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Services\PackageService;
use Illuminate\Database\Seeder;

class SeedPackages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $destinations = Destination::query()->get()->pluck('id')->toArray();
        $facilities = PlanFacility::query()->get()->pluck('id')->toArray();
        for ($i = 0; $i < rand(5, 15); $i++) {
            /* begin:: start package service */

            $packageService = new PackageService(1);

            \DB::beginTransaction();
            try {
                $newPackage = PlanPackage::query()->create([
                    'tenant_id' => rand(1, 2),
                    'plan_id' => 1,
                    'name' => fake()->name(),
                    'description' => fake()->text(),
                    'amount' => fake()->randomNumber(8),
                    'long_days' => fake()->randomNumber(2),
                ]);
                \DB::commit();

                $packageService
                    ->byHash($newPackage->hash)
                    ->addDestinations($destinations[rand(0, count($destinations)-1)])
                    ->addFacilities($facilities);

                /* end:: start package service */
            }catch (\Throwable $e){
                \DB::rollBack();
                $this->command->info($e->getMessage());
            }

        }

    }
}

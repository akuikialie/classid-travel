<?php

namespace Database\Seeders;

use App\Models\Destination\Destination;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Models\Tenant\Tenant;
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

        $tenants_count = Tenant::query()->count();

        for ($i = 0; $i < 30; $i++) {
            /* begin:: start package service */

            $packageService = new PackageService(1);

            \DB::beginTransaction();
            try {
                $newPackage = PlanPackage::query()->create([
                    'tenant_id' => rand(1, $tenants_count),
                    'plan_id' => 1,
                    'name' => fake()->name(),
                    'description' => fake()->text(),
                    'amount' => fake()->randomNumber(8),
                    'long_days' => rand(10, 20),
                ]);
                \DB::commit();

                $packageService
                    ->byHash($newPackage->hash)
                    ->addDestinations([$destinations[rand(0, count($destinations)-1)]])
                    ->addFacilities($facilities);

                /* end:: start package service */
            }catch (\Throwable $e){
                \DB::rollBack();
                $this->command->info($e->getMessage());
            }

        }

    }
}

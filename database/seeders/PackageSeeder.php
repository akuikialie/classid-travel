<?php

namespace Database\Seeders;

use App\Models\Destination\Destination;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use App\Models\Tenant\Tenant;
use App\Services\PackageService;
use DB;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $destinations = Destination::query()->get()->pluck('id')->toArray();
        $facilities = PlanFacility::query()->get()->pluck('id')->toArray();

        $tenants_count = Tenant::query()->count('id');

        for ($i = 0; $i < 30; $i++) {
            $tenantId = rand(1, $tenants_count);
            $packageService = new PackageService($tenantId);

            DB::beginTransaction();
            try {
                $newPackage = PlanPackage::query()->create([
                    'tenant_id' => $tenantId,
                    'plan_id' => 1,
                    'name' => fake()->name(),
                    'description' => fake()->text(),
                    'amount' => fake()->randomNumber(8),
                    'long_days' => rand(10, 20),
                ]);
                DB::commit();

                $packageService
                    ->setPackage($newPackage)
                    ->addDestinations([$destinations[rand(0, count($destinations)-1)]])
                    ->addFacilities($facilities);

            }catch (\Throwable $e){
                DB::rollBack();
                $this->command->info($e->getMessage());
            }
        }
    }
}

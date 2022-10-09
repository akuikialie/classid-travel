<?php

namespace Database\Seeders;

use App\Models\Plan\Plan;
use App\Models\Plan\PlanPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeedPackages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* create plan */
        try {
            collect([
                'Umrah', 'Haji', 'Wisata',
            ])->each(fn ($cat, $i) => Plan::create([
                'type' => 'plan',
                'key' => Str::slug($cat),
                'value' => $cat,
                'order' => $i
            ]));

            /* add package to plan */
            $packages = [
                [
                    'name' => 'Paket Umrah Reguler 12 Hari',
                    'description' => null,
                    'amount' => 28000000,
                ],
                [
                    'name' => 'Paket Umrah Reguler 20 Hari',
                    'description' => null,
                    'amount' => 32000000,
                ],
            ];

            $planUmrah = Plan::query()->where('type', 'plan')->where('key', 'umrah')->first();

            foreach ($packages as $key => $package) {
                $newPackage = new PlanPackage($package);
                $newPackage->myPlan()->associate($planUmrah);
                $newPackage->push();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

namespace App\Services;

use App\Enums\Statuses;
use App\Enums\VirtualAccount;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\Plan;
use App\Models\Plan\PlanFacility;
use App\Models\Plan\PlanPackage;
use Illuminate\Http\Request;
use InvalidArgumentException;

class PackageService
{
    public function __construct()
    {
        //
    }

    public function addPackageToJamaah(PlanPackage $planPackage, Jamaah $jamaah, string $key = 'perencanaan')
    {
        try {
            /* check package on jamaah */
            $existingPackageInJamaah = collect($jamaah->planPackages)->where('id', $planPackage->id)->first();
            if ($existingPackageInJamaah) {
                throw new InvalidArgumentException('Kamu sudah mengambil paket ini!.');
            }

            /* add package to jamaah */
            $jamaah->planPackages()->attach($planPackage->id);

            /* creating va */
            $vaService = new VirtualAccountService;
            $vaService->createVirtualAccount(VirtualAccount::tryFrom($key)->keyValue(), $jamaah, $planPackage);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createNewPackage(int $planId, array $input): PlanPackage
    {
        try {

            $plan = Plan::query()->find($planId);

            $newPackage = new PlanPackage($input);

            $newPackage->myPlan()->associate($plan);
            $newPackage->save();

            return $newPackage->fresh();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addFacilitiesToPackage(PlanPackage $planPackage, array $facilitiyIds): void
    {
        try {
            if (collect($facilitiyIds)->count() > 0) {
                $planPackage->myFacilities()->sync($facilitiyIds);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addDestinationsToPackage(PlanPackage $planPackage, array $destinationIds): void
    {
        try {
            if (collect($destinationIds)->count() > 0) {
                $planPackage->myDestinations()->sync($destinationIds);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changePackageVisibility(PlanPackage $planPackage, string $status): void
    {
        try {
            /* update package status */
            $planPackage->status = Statuses::tryFrom($status)->keyValue();

            /* save */
            $planPackage->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changePackageStatus(PlanPackage $planPackage, bool $visibility): void
    {
        try {
            /* update package visibility */
            $this->planPackage->is_publish = $visibility;

            /* save */
            $planPackage->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addThumbnailPackage(PlanPackage $planPackage, Request $request)
    {
        try {
            if ($request->hasfile('thumbnail')) {
                $planPackage->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

namespace App\Services;

use App\Exceptions\HandleCatchableException;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\Tenant\Tenant;
use App\Traits\HasTenant;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

class PackageService
{
    use HasTenant;

    private ?PlanPackage $package = null;

    public function __construct(
        private readonly int $tenantId
    )
    {}

    /**
     * @param array $destinationIds
     * @return $this
     * @throws Throwable
     */
    public function addDestinations(array $destinationIds): static
    {
        if (count($destinationIds) != count($destinationIds, COUNT_RECURSIVE)) {
            $destinationIds = collect($destinationIds)->first();
        }

        if (collect($destinationIds)->count() > 0) {
            $addDestinations = $this->getPackage();
            $addDestinations->myDestinations()->sync($destinationIds);
        }
        return $this;
    }

    /**
     * @param array $facilityIds
     * @return $this
     * @throws Throwable
     */
    public function addFacilities(array $facilityIds): static
    {
        if (count($facilityIds) != count($facilityIds, COUNT_RECURSIVE)) {
            $facilityIds = collect($facilityIds)->first();
        }
        if (collect($facilityIds)->count() > 0) {
            $facility = $this->getPackage();
            $facility->myFacilities()->sync($facilityIds);
        }

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function addPackageToJamaah(PlanPackage $planPackage, Jamaah $jamaah, string $key = 'perencanaan'): void
    {
        (new JamaahService($this->tenantId))
            ->setPackage(package: $planPackage)
            ->setJamaah(jamaah: $jamaah)
            ->addPackage(key: $key);

    }

    /**
     * @param int $planId
     * @param array $input
     * @return $this
     */
    public function createNewPackage(int $planId, array $input): static
    {
        $input = array_merge([
            'tenant_id' => $this->tenantId,
            'plan_id' => $planId,
        ], $input);

        $this->package = PlanPackage::query()->create($input);

        return $this;
    }

    /**
     * @param array $input
     * @return void
     * @throws HandleCatchableException
     */
    public function updateExistingPackage(array $input): void
    {
        $package = $this->getPackage();
        foreach ($input as $key => $value){
            $package->$key = $value;
        }
        $package->push();
    }

    /**
     * @param Request $request
     * @return PackageService
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws HandleCatchableException
     */
    public function addThumbnailPackage(Request $request): static
    {
        if ($request->hasfile('thumbnail')) {
            $package = $this->getPackage();
            $package->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
        }

        return $this;
    }

    /**
     * @param PlanPackage $package
     * @return PackageService
     */
    public function setPackage(PlanPackage $package): static
    {
        $this->package = $package;

        return $this;
    }

    /**
     * @return PlanPackage|null
     * @throws HandleCatchableException
     */
    public function getPackage(): ?PlanPackage
    {
        if (!$this->package instanceof Tenant) {
            throw HandleCatchableException::catchable('Paket tidak di ditemukan!');
        }
        return $this->package;
    }

}

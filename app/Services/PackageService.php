<?php

namespace App\Services;

use App\Actions\Jamaah\AddJamaahHistory;
use App\Enums\Statuses;
use App\Enums\VirtualAccount;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\Plan;
use App\Models\Plan\PlanPackage;
use App\Traits\HasTenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use InvalidArgumentException;
use LaravelIdea\Helper\App\Models\Plan\_IH_PlanPackage_QB;
use Throwable;

class PackageService
{
    use HasTenant;
    protected Builder $query;

    private PlanPackage $package;

    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = PlanPackage::query();
    }

    /**
     * @param array $with
     * @return $this
     */
    public function with(array $with): static
    {
        $this->query->with($with);
        return $this;
    }

    /**
     * @param array $withCount
     * @return $this
     */
    public function withCount(array $withCount): static
    {
        $this->query->withCount($withCount);
        return $this;
    }

    /**
     * @param string $hash
     * @return $this
     */
    public function byHash(string $hash): static
    {
        $this->query->byHashOrFail($hash);
        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     * @throws Exception
     */
    public function setStatus(bool $status): static
    {
        try {
            $tenant = $this->getPackage();
            $tenant->is_active = $status;
            $tenant->save();
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    /**
     * @param ...$destinationIds
     * @return $this
     * @throws Throwable
     */
    public function addDestinations(...$destinationIds): static
    {
        try {
            if (count($destinationIds) != count($destinationIds, COUNT_RECURSIVE))
            {
                $destinationIds = collect($destinationIds)->first();
            }
            $addDestinations = $this->getPackage();
            $addDestinations->myDestinations()->sync($destinationIds);
        }catch (Throwable $e){
            throw $e;
        }
        return $this;
    }

    /**
     * @param array ...$facilityIds
     * @return $this
     * @throws Throwable
     */
    public function addFacilities(array ...$facilityIds): static
    {
        try {
            if (count($facilityIds) != count($facilityIds, COUNT_RECURSIVE))
            {
                $facilityIds = collect($facilityIds)->first();
            }
            $facility = $this->getPackage();
            $facility->myFacilities()->sync($facilityIds);
        }catch (Throwable $e){
            throw $e;
        }

        return $this;
    }

    public function addPackageToJamaah(PlanPackage $planPackage, Jamaah $jamaah, string $key = 'perencanaan'): void
    {
        try {
            /* check package on jamaah */
            $existingPackageInJamaah = collect($jamaah->planPackages)->where('id', $planPackage->id)->first();
            if ($existingPackageInJamaah) {
                throw new InvalidArgumentException('Kamu sudah mengambil paket ini!.');
            }

            /* add package to jamaah */
            $jamaah->planPackages()->attach($planPackage->id);

            /* add jamaah history */
            $jamaahHistory = new AddJamaahHistory();
            $jamaahHistory->handle($jamaah, $planPackage, null);

            /* creating va */
            $vaService = new VirtualAccountService($this->tenantId);
            $vaService->createVirtualAccount(VirtualAccount::tryFrom($key)->keyValue(), $jamaah, $planPackage);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function createNewPackage(int $planId, array $input): PlanPackage
    {
        try {

            $input = array_merge(['tenant_id' => $this->tenantId], $input);

            $plan = Plan::query()->find($planId);

            $newPackage = new PlanPackage($input);

            $newPackage->myPlan()->associate($plan);
            $newPackage->save();

            return $newPackage->fresh();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function addThumbnailPackage(PlanPackage $planPackage, Request $request): void
    {
        try {
            if ($request->hasfile('thumbnail')) {
                $planPackage->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }
        } catch (Throwable $th) {
            throw $th;
        }
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
     * @throws Exception
     */
    public function getPackage(): PlanPackage
    {
        if ($this->query->count() > 1) {
            if (isset($this->package)) {
                $package = $this->package;
            } else {
                throw new Exception('Data harus spesifik!.');
            }
        } else {
            $package = $this->query->first();
        }

        return $package;
    }

}

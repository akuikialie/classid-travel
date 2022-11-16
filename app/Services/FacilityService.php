<?php

namespace App\Services;

use App\Models\Destination\Destination;
use App\Models\Plan\PlanFacility;
use App\Traits\HasTenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Throwable;

class FacilityService
{
    private Builder $query;
    private PlanFacility $planFacility;

    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = PlanFacility::query();
    }

    /**
     * @param int $id
     * @return $this
     */
    public function facilityId(int $id): static
    {
        $this->query->where('id', $id);
        return $this;
    }

    public function addGallery(Request $request): static
    {
        try {
            if ($request->hasfile('photo_collection')) {
                $facility = $this->getFacility();
                $facility->addMultipleMediaFromRequest(['photo_collection'])
                    ->each(fn($media) => $media->toMediaCollection('photo_collections'));
            }
        } catch (Throwable $th) {
            throw $th;
        }

        return $this;
    }

    /**
     * @param array $input
     * @return $this
     */
    public function createFacility(array $input): static
    {
        $input = array_merge(['tenant_id' => $this->tenantId], $input);
        $input = array_merge($input, [
            'name' => ucwords($input['name'])
        ]);
        $this->planFacility = $this->query->create($input);
        return $this;
    }

    public function getFacility(): PlanFacility
    {
        if ($this->query->count() > 1){
            if (isset($this->planFacility) and $this->planFacility instanceof PlanFacility){
                $facility = $this->planFacility;
            }else{
                throw new Exception('Data harus spesifik!');
            }
        }else{
            $facility = $this->query->first();
        }

        return $facility;
    }

    public function get(): Model|Builder|Destination|null
    {
        return $this->query->first();
    }

    /**
     * @param array $input
     * @return Model|Builder|Destination|null
     * @throws Exception
     */
    public function update(array $input): Model|Builder|Destination|null
    {
        $facility = $this->getFacility();
        $facility->name = $input['name'];
        $facility->type = $input['type'];
        $facility->save();

        return $facility->fresh();
    }

    /**
     * @param bool $status
     * @return $this
     * @throws Exception
     */
    public function setStatus(bool $status): static
    {
        try {
            $facility = $this->getFacility();
            $facility->is_active = $status;
            $facility->save();
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }












    public function createNewFacility(array $input): PlanFacility
    {
        try {

            $input = array_merge($input, [
                'name' => ucwords($input['name'])
            ]);

            $newFacility = PlanFacility::query()->create($input);

            return $newFacility;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function AddImagesToFacility(PlanFacility $planFacility, Request $request)
    {
        try {
            if ($request->hasfile('photo_collection')) {
                $planFacility->addMultipleMediaFromRequest(['photo_collection'])
                    ->each(function ($media) {
                        $media->toMediaCollection('photo_collections');
                    });

            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @param PlanFacility $planFacility
     * @return FacilityService
     */
    public function setPlanFacility(PlanFacility $planFacility): static
    {
        $this->planFacility = $planFacility;
        return $this;
    }
}

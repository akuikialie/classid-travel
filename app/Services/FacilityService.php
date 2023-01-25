<?php

namespace App\Services;

use App\Exceptions\HandleCatchableException;
use App\Models\Destination\Destination;
use App\Models\Plan\PlanFacility;
use App\Models\Tenant\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class FacilityService
{
    private ?PlanFacility $planFacility = null;

    public function __construct(
        private readonly int $tenantId
    )
    {
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws Exception
     */
    public function addGallery(Request $request): static
    {
        if ($request->hasfile('photo_collection')) {
            $facility = $this->getPlanFacility();
            $facility->addMultipleMediaFromRequest(['photo_collection'])
                ->each(fn($media) => $media->toMediaCollection('photo_collections'));
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
        $this->planFacility = PlanFacility::query()->create($input);
        return $this;
    }

    /**
     * @param array $input
     * @return Model|Builder|Destination|null
     * @throws Exception
     */
    public function update(array $input): Model|Builder|Destination|null
    {
        $facility = $this->getPlanFacility();
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
        $facility = $this->getPlanFacility();
        $facility->is_active = $status;
        $facility->save();

        return $this;
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

    /**
     * @return PlanFacility|null
     * @throws HandleCatchableException
     */
    public function getPlanFacility(): ?PlanFacility
    {
        if (!$this->planFacility instanceof Tenant) {
            throw HandleCatchableException::catchable('Fasilitas tidak di ditemukan!');
        }
        return $this->planFacility;
    }
}

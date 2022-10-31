<?php

namespace App\Services;

use App\Models\Plan\PlanFacility;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;

class FacilityService
{

    use HasTenant;

    public Builder $query;
    public function __construct()
    {
        $this->query = PlanFacility::query();
        $this->query->withCount(['media', 'packages']);
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
}

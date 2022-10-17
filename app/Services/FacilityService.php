<?php

namespace App\Services;

use App\Models\Plan\PlanFacility;
use Illuminate\Http\Request;

class FacilityService
{
    public function __construct()
    {
        //
    }

    public function createNewFacility(array $input): PlanFacility
    {
        try {

            $input = array_merge($input, [
                'name' => ucwords($input['name'])
            ]);

            $newFacility = PlanFacility::query()->create($input);

            return $newFacility;
        } catch (\Throwable $th) {
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
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

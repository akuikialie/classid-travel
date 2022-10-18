<?php

namespace App\Services;

use App\Models\Destination\Destination;
use App\Models\Master\Address;
use Exception;
use Illuminate\Http\Request;

class DestinationService
{
    public function __construct()
    {
    }

    public function createNewDestination(array $input): Destination
    {
        try {
            if ((int)$input['roaming_in_destination'] < 20) {
                throw new Exception('Roaming in destination to short', 500);
            }

            $newDestination = Destination::query()->create($input);

            /* add destination address when posible */
            if (isset($input['address']) && !empty($input['address'])) {
                $this->addDestinationAddress($newDestination, $input);
            }

            return $newDestination;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addDestinationAddress(Destination $destination, array $input): void
    {
        try {
            $newAddress = new Address([
                'address' => $input['address']
            ]);
            $destination->myAddress()->save($newAddress);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateDestinationAddress(Destination $destination, array $input)
    {
        try {
            $destination->myAddress->address = $input['address'];
            $destination->myAddress->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addImageToDestination(Destination $destination, Request $request)
    {
        try {
            if ($request->hasfile('photo_collection')) {
                $destination->addMultipleMediaFromRequest(['photo_collection'])
                    ->each(function ($media) {
                        $media->toMediaCollection('photo_collections');
                    });
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

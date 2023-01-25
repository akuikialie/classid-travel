<?php

namespace App\Services;

use App\Exceptions\HandleCatchableException;
use App\Models\Destination\Destination;
use App\Models\Master\Address;
use App\Models\Tenant\Tenant;
use App\Traits\HasTenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

class DestinationService
{
    private ?Destination $destination = null;

    public function __construct(
        private readonly int $tenantId
    )
    {}

    /**
     * @param Request $request
     * @return $this
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws Exception
     */
    public function addGallery(Request $request): static
    {
        if ($request->hasfile('photo_collection')) {
            $destination = $this->getDestination();
            $destination->addMultipleMediaFromRequest(['photo_collection'])
                ->each(fn($media) => $media->toMediaCollection('photo_collections'));
        }
        return $this;
    }

    /**
     * @param array $input
     * @return $this
     */
    public function createDestination(array $input): static
    {
        $input = array_merge(['tenant_id' => $this->tenantId], $input);
        $this->destination = Destination::query()->create($input);

        return $this;
    }

    /**
     * @param array $input
     * @return $this
     * @throws Throwable
     */
    public function addAddress(array $input): static
    {
        if (isset($input['address'])){
            $newAddress = new Address([
                'address' => $input['address']
            ]);

            $destination = $this->getDestination();

            $destination->myAddress()->save($newAddress);
        }
        return $this;
    }

    /**
     * @param array $input
     * @return Model|Builder|Destination|null
     * @throws Exception
     */
    public function update(array $input): Model|Builder|Destination|null
    {
        $destination = $this->getDestination();
        $destination->name = $input['name'];
        $destination->save();

        return $destination->fresh();
    }

    /**
     * @param bool $status
     * @return $this
     * @throws Exception
     */
    public function setStatus(bool $status): static
    {
        $destination = $this->getDestination();
        $destination->is_active = $status;
        $destination->save();

        return $this;
    }

    /**
     * @param Destination $destination
     * @return DestinationService
     */
    public function setDestination(Destination $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return Destination|null
     * @throws HandleCatchableException
     */
    public function getDestination(): ?Destination
    {
        if (!$this->destination instanceof Destination){
            throw HandleCatchableException::catchable('Destinasi tujuan tidak di ditemukan!');
        }
        return $this->destination;
    }
}

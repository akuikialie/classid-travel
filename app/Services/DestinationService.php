<?php

namespace App\Services;

use App\Models\Destination\Destination;
use App\Models\Master\Address;
use App\Traits\HasTenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use LaravelIdea\Helper\App\Models\Destination\_IH_Destination_QB;
use Throwable;

class DestinationService
{
    use HasTenant;

    private Builder $query;
    private Destination $destination;

    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = Destination::query();
        $this->query->with(['myAddress'])
            ->withCount(['media', 'packages']);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function destinationId(int $id): static
    {
        $this->query->where('id', $id);
        return $this;
    }

    public function addGallery(Request $request): static
    {
        try {
            if ($request->hasfile('photo_collection')) {
                $destination = $this->destination();
                $destination->addMultipleMediaFromRequest(['photo_collection'])
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
    public function createDestination(array $input): static
    {
        $input = array_merge(['tenant_id' => $this->tenantId], $input);
        $this->destination = $this->query->create($input);

        return $this;
    }

    /**
     * @param array $input
     * @return $this
     * @throws Throwable
     */
    public function addAddress(array $input): static
    {
        try {
            if (isset($input['address'])){
                $newAddress = new Address([
                    'address' => $input['address']
                ]);

                $destination = $this->destination();

                $destination->myAddress()->save($newAddress);
            }
            return $this;
        } catch (Throwable $th) {
            throw $th;
        }
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
        $destination = $this->destination();
        $destination->name = $input['name'];
        $destination->save();

        return $destination->fresh();
    }

    /**
     * @throws Exception
     */
    private function destination(): Model|Builder|Destination|null
    {
        if ($this->query->count() > 1){
            if (isset($this->destination) and $this->destination instanceof Destination){
                $destination = $this->destination;
            }else{
                throw new Exception('Tujuan belum di konfigurasi');
            }
        }else{
            $destination = $this->query->first();
        }

        return $destination;
    }

    /**
     * @param array $input
     * @return $this
     * @throws Throwable
     */
    public function createNewDestination(array $input): static
    {
        $newDestination = $this->query->create($input);

        /* add destination address when posible */
        if (isset($input['address']) && !empty($input['address'])) {
            $this->addDestinationAddress($newDestination, $input);
        }


        return $this;
    }

    public function updateDestinationAddress(Destination $destination, array $input)
    {
        try {
            $destination->myAddress->address = $input['address'];
            $destination->myAddress->save();
        } catch (Throwable $th) {
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
        } catch (Throwable $th) {
            throw $th;
        }
    }
}

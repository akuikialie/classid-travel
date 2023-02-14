<?php

namespace App\Services;

use App\Exceptions\HandleCatchableException;
use App\Models\Itinerary\Itinerary;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Laravel\Octane\Exceptions\DdException;

class ItineraryService
{
    private ?Itinerary $itinerary = null;

    private ?Model $model = null;

    private array $activities = [];

    public function __construct(
        private readonly int $tenantId
    )
    {
    }

    /* activity for itineraries table */
    /**
     * @param array $itineraries
     * @return $this
     * @throws DdException
     * @throws HandleCatchableException
     */
    public function addItineraries(array $itineraries): static
    {
        $model = $this->getModel();
        foreach ($itineraries as $key => $_itinerary){

            $itinerary = Itinerary::query()
                ->where([
                    'tenant_id' => $this->tenantId,
                    'day' => $key ?? null,
                    'model_id' => $model->id,
                    'model_type' => $model::class,
                ])->first();

            if (!$itinerary){
                /* begin:: create itinerary */
                $newItinerary = new Itinerary([
                    'tenant_id' => $this->tenantId,
                    'name' => $_itinerary['name'] ?? null,
                    'day' => $key ?? null,
                ]);

                $model->myItineraries()->save($newItinerary);
                /* end:: create itinerary */

                $itinerary = $newItinerary;
            }else{
                $itinerary->name = $_itinerary['name'] ?? null;
                $itinerary->save();
            }

            /* begin:: add itinerary activity*/
            $activities = [];
            $activitiesTime = [];
            foreach ($_itinerary['itineraries'] ?? [] as $activity){
                $activities[] = $activity['itinerary'];
                $activitiesTime[] = [
                    'tenant_id' => $this->tenantId,
                    'time' => $activity['time']
                ];
            }
            $data = array_combine($activities, $activitiesTime);
            $itinerary->activities()->sync($data);
            /* end:: add itinerary activity*/
        }

        return $this;
    }

    /**
     * @param mixed $model
     * @return ItineraryService
     */
    public function setModel(mixed $model): static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return Itinerary|null
     * @throws Exception
     */
    public function getItinerary(): ?Itinerary
    {
        if ($this->itinerary instanceof Itinerary){
            throw HandleCatchableException::catchable('Data tidak ditemukan');
        }
        return $this->itinerary;
    }

    /**
     * @param Itinerary|null $itinerary
     * @return ItineraryService
     */
    public function setItinerary(?Itinerary $itinerary): static
    {
        $this->itinerary = $itinerary;
        return $this;
    }

    /**
     * @return Model|null
     * @throws HandleCatchableException
     */
    public function getModel(): ?Model
    {
        if (!$this->model instanceof Model){
            throw HandleCatchableException::catchable('Model tidak ditemukan!');
        }
        return $this->model;
    }

}

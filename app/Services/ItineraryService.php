<?php

namespace App\Services;

use App\Models\Itinerary\Itinerary;
use App\Models\Itinerary\ItineraryActivity;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Octane\Exceptions\DdException;

class ItineraryService
{
    private Builder $query;
    protected Itinerary $itinerary;

    private $model;

    private array $activities = [];

    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = Itinerary::query();
    }

    /* activity for itineraries table */
    /**
     * @param array $itineraries
     * @return $this
     * @throws DdException
     */
    public function addItineraries(array $itineraries): static
    {
        try {
            foreach ($itineraries as $key => $_itinerary){

                $itinerary = Itinerary::query()
                    ->where([
                        'tenant_id' => $this->tenantId,
                        'day' => $key ?? null,
                    ])->first();

                if (!$itinerary){
                    /* begin:: create itinerary */
                    $newItinerary = new Itinerary([
                        'tenant_id' => $this->tenantId,
                        'name' => $_itinerary['name'] ?? null,
                        'day' => $key ?? null,
                    ]);

                    $this->model->myItineraries()->save($newItinerary);
                    /* end:: create itinerary */

                    $itinerary = $newItinerary;
                }else{
                    $itinerary->name = $_itinerary['name'] ?? null;
                    $itinerary->save();
                }

                /* begin:: add itinerary activity*/
                $activities = [];
                $activitiesTime = [];
                foreach ($_itinerary['itineraries'] ?? [] as $key2 => $activity){
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
//            dd(collect(array_column($lastData['day-1'], 'itinerary'))->unique());

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
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
     * @return Itinerary
     * @throws Exception
     */
    private function getItinerary(): Itinerary
    {
        if ($this->query->count() > 1){
            if (isset($this->itinerary) and $this->itinerary instanceof Itinerary){
                $itinerary = $this->itinerary;
            }else{
                throw new Exception('Data harus spesifik!.');
            }
        }else{
            $itinerary = $this->query->first();
        }

        return $itinerary;
    }
}

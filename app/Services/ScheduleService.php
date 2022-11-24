<?php

namespace App\Services;

use App\Models\Schedule\Schedule;
use App\Traits\HasTenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class ScheduleService
{

    private Builder $query;
    private Schedule $schedule;

    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = Schedule::query();
    }

    /**
     * @param bool $status
     * @return $this
     * @throws Exception
     */
    public function setStatus(bool $status): static
    {
        try {
            $model = $this->getSchedule();
            $model->is_active = $status;
            $model->save();
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    /**
     * @return Schedule
     * @throws Exception
     */
    public function getSchedule(): Schedule
    {
        if ($this->query->count() > 1){
            if (isset($this->planFacility)){
                $facility = $this->planFacility;
            }else{
                throw new Exception('Data harus spesifik!');
            }
        }else{
            $facility = $this->query->first();
        }

        return $facility;
    }

    /**
     * @param Schedule $schedule
     * @return ScheduleService
     */
    public function setSchedule(Schedule $schedule): static
    {
        $this->schedule = $schedule;
        return $this;
    }

}

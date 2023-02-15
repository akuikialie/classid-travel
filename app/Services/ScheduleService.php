<?php

namespace App\Services;

use App\Exceptions\HandleCatchableException;
use App\Models\Schedule\Schedule;
use App\Traits\HasTenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class ScheduleService
{

    private Builder $query;
    private ?Schedule $schedule = null;

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
        $model = $this->getSchedule();
        $model->is_active = $status;
        $model->save();

        return $this;
    }

    /**
     * @return Schedule
     * @throws Exception
     */
    public function getSchedule(): Schedule
    {
        if (!$this->schedule instanceof Schedule){
            throw HandleCatchableException::catchable('Jadwal tidak ditemukan!');

        }
       return $this->schedule;
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

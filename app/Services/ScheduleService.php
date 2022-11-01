<?php

namespace App\Services;

use App\Models\Schedule\Schedule;
use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Builder;

class ScheduleService
{
    use HasTenant;

    protected Builder $query;
    public function __construct()
    {
        $this->query = Schedule::query();
    }

    public function createSchedule(array $input): Schedule
    {

    }
}

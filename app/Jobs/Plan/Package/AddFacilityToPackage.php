<?php

namespace App\Jobs\Plan\Package;

use App\Models\Plan\PlanPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddFacilityToPackage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $planPackage;
    protected $facilitiyIds;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlanPackage $planPackage, array $facilitiyIds)
    {
        $this->planPackage = $planPackage;
        $this->facilitiyIds = $facilitiyIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (collect($this->facilitiyIds)->count() > 0) {
                $this->planPackage->myFacilities()->sync($this->facilitiyIds);
            }

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

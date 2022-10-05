<?php

namespace App\Jobs\Plan\Package;

use App\Models\Plan\PlanPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddDestinationToPackage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $planPackage;
    protected $destinationIds;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlanPackage $planPackage, array $destinationIds)
    {
        $this->planPackage = $planPackage;
        $this->destinationIds = $destinationIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (collect($this->destinationIds)->count() > 0) {
                $this->planPackage->myDestinations()->sync($this->destinationIds);
            }

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

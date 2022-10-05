<?php

namespace App\Jobs\Plan\Package;

use App\Models\Plan\PlanPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePackageVisibility implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $planPackage;
    protected $visibility;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlanPackage $planPackage, bool $visibility)
    {
        $this->planPackage = $planPackage;
        $this->visibility = $visibility;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
             /* update package visibility */
             $this->planPackage->is_publish = $this->visibility;

             /* save */
             $this->planPackage->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

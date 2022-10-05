<?php

namespace App\Jobs\Plan\Package;

use App\Enums\Statuses;
use App\Models\Plan\PlanPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePackageStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $planPackage;
    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlanPackage $planPackage, string $status)
    {
        $this->planPackage = $planPackage;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /* update package status */
            $this->planPackage->status = Statuses::tryFrom($this->status)->keyValue();

            /* save */
            $this->planPackage->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

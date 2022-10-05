<?php

namespace App\Jobs\Plan\Facility;

use App\Models\Plan\PlanFacility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateExistingFacility implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $planFacility;
    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlanFacility $planFacility, array $input)
    {
        $this->planFacility = $planFacility;
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

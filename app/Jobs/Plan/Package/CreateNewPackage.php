<?php

namespace App\Jobs\Plan\Package;

use App\Models\Plan\Plan;
use App\Models\Plan\PlanPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateNewPackage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $plan;
    protected $input;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Plan $plan, array $input)
    {
        $this->plan = $plan;
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $newPackage = new PlanPackage([
                'name' => $this->input['name'],
            ]);

            /* add facilities to plan package */
            dispatch(new AddFacilityToPackage($newPackage, $this->input['facilities']));

            $newPackage->myPlan()->associate($this->plan);
            $newPackage->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}

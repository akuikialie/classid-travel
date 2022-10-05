<?php

namespace App\Jobs\Plan\Package;

use App\Enums\Statuses;
use App\Models\Plan\Plan;
use App\Models\Plan\PlanPackage;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;

class UpdateExistingPackage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $plan;
    protected $planPackage;
    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Plan $plan, PlanPackage $planPackage, $input)
    {
        $this->plan = $plan;
        $this->planPackage = $planPackage;
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

            /* check is package already published*/
            if ($this->planPackage->is_publish == true) {
                throw new Exception('This package already published!.', 500);
            }

            /* check is package status == nonactive*/
            if ($this->planPackage->status == Statuses::tryFrom('nonactive')->keyValue()) {
                throw new Exception('This package is non-active!.', 500);
            }

            /* update */
            /* check new facilities want to add */
            $newFacilities = $this->input['facilities'];
            $existingFacilities = $this->planPackage->myFacilities?->pluck('id');
            $compareFacilities = array_diff($newFacilities, $existingFacilities);
            if (collect($compareFacilities)->count() > 0) {
                /* new input have new facilities */
                dispatch(new AddFacilityToPackage($this->planPackage, $this->input['facilities']));
            }

            /* check new detination want to add to package */
            $newDestination = $this->input['destinations'];
            $existingDestination = $this->planPackage->myFacilities?->pluck('id');
            $compareDestination = array_diff($newDestination, $existingDestination);
            if (collect($compareDestination)->count() > 0) {
                /* new input have new destination */
                dispatch(new AddDestinationToPackage($this->planPackage, $this->input['destinations']));
            }

            $this->planPackage->name = $this->input['name'];

            $this->planPackage->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}

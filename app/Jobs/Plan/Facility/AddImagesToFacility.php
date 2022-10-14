<?php

namespace App\Jobs\Plan\Facility;

use App\Models\Plan\PlanFacility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class AddImagesToFacility implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PlanFacility $planFacility;
    protected Request $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlanFacility $planFacility, Request $input)
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
            if ($this->input->hasfile('photo_collection')) {
                $this->planFacility->addMultipleMediaFromRequest(['photo_collection'])
                    ->each(function ($media) {
                        $media->toMediaCollection('photo_collections');
                    });

            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

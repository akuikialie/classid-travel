<?php

namespace App\Jobs\Plan\Facility;

use App\Models\Plan\PlanFacility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CreateNewFacility implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $input;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): PlanFacility
    {
        try {

            $input = array_merge($this->input, [
                'name' => ucwords($this->input['name'])
            ]);

            $newFacility = new PlanFacility($this->input);

            $newFacility->save();

            return $newFacility->fresh();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

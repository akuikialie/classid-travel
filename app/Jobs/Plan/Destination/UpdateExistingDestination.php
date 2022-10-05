<?php

namespace App\Jobs\Plan\Destination;

use App\Models\Destination\Destination;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateExistingDestination implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $destination;
    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Destination $destination, array $input)
    {
        $this->destination = $destination;
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
            if ((int)$this->input['roaming_in_destination'] < 20 ) {
                throw new Exception('Roaming in destination to short', 500);
            }
            $this->destination->name = $this->input['name'];
            $this->destination->roaming_in_destination = $this->input['roaming_in_destination'];
            $this->destination->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

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
use Illuminate\Support\Facades\DB;

class CreateNewDestination implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $input;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( array $input)
    {
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
            if ((int)$this->input['roaming_in_destination'] < 20 ) {
                throw new Exception('Roaming in destination to short', 500);
            }

            $newDestination = new Destination($this->input);

            /* add destination address when posible */
            if (isset($this->input['addresses']) && !empty($this->input['addresses'])) {
                dispatch(new AddDestinationAddress($newDestination, $this->input['addresses']));
            }

            $newDestination->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}

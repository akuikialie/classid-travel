<?php

namespace App\Jobs\Plan\Destination;

use App\Models\Destination\Destination;
use App\Models\Master\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AddDestinationAddress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $destination;
    protected $inputAddress;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Destination $destination, array $inputAddress)
    {
        $this->destination = $destination;
        $this->inputAddress = $inputAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $newAddress = new Address([
                'address' => $this->inputAddress['address']
            ]);
            dd($newAddress);
            $this->destination->myAddress()->save($newAddress);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

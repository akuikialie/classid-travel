<?php

namespace App\Jobs\Plan\Destination;

use App\Models\Destination\Destination;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;


class AddImagesToDestination implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Destination $destination;
    protected Request $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Destination $destination, Request $input)
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
            if ($this->input->hasfile('photo_collection')) {
                $this->destination->addMultipleMediaFromRequest(['photo_collection'])
                    ->each(function ($media) {
                        $media->toMediaCollection('photo_collections');
                    });

            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

namespace App\Jobs\Referal;

use App\Models\Plan\PlanPackage;
use App\Models\Referal\ReferalLink;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateReferalLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($input)
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
            $hashable = Str::random(10);
            $newReferalLink = new ReferalLink([
                'link' => route('invite.link', [$hashable, 'login']),
                'hash' => $hashable,
            ]);

            /* insert package */
            $package = PlanPackage::query()->where('id', $this->input['package_id'])->first();
            $newReferalLink->package()->associate($package);

            /* insert created by */
            $user = User::query()->find(auth()->user()->id);
            $newReferalLink->createdBy()->associate($user);

            $newReferalLink->push();
            // DB::commit();
            return $newReferalLink;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}

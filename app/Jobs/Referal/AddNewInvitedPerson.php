<?php

namespace App\Jobs\Referal;

use App\Jobs\Plan\Package\AddPackageToJamaah;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\Referal\ReferalLink;
use App\Models\Referal\UserInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AddNewInvitedPerson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $referalLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ReferalLink $referalLink)
    {
        $this->referalLink = $referalLink;
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
            /* add user invited detail */
            $peopleInvited = new UserInvitation();

            /* insert user id */
            $user = User::query()->find(auth()->user()->id);
            $peopleInvited->user()->associate($user);

            /* insert invited by */
            $invitedBy = User::query()->find($this->referalLink->created_by);
            $peopleInvited->invitedBy()->associate($invitedBy);

            /* insert referal link */
            $peopleInvited->referalLink()->associate($this->referalLink);

            $peopleInvited->push();

            $jamaah = Jamaah::query()->where('user_id', $user->id)->first();
            $package = PlanPackage::query()->where('id', $this->referalLink->package_id)->first();

            dispatch(new AddPackageToJamaah($package, $jamaah));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\Referral\ReferralLink;
use App\Models\Referral\UserInvitation;
use App\Models\User;

use Illuminate\Support\Str;

class ReferalService
{
    public function __construct()
    {
        //
    }

    public function saveInvitedPerson(ReferralLink $referalLink, User $user = null): void
    {
        try {
            /* add user invited detail */
            $peopleInvited = new UserInvitation(
                [
                    'tenant_id' => 1,
                ]
            );

            /* insert user id */
            $user = User::query()->find(isset($user) ? $user->id : auth()->user()->id);
            $peopleInvited->user()->associate($user);

            /* insert invited by */
            $invitedBy = User::query()->find($referalLink->created_by);
            $peopleInvited->invitedBy()->associate($invitedBy);

            /* insert referal link */
            $peopleInvited->referalLink()->associate($referalLink);

            $peopleInvited->push();

            $jamaah = Jamaah::query()->where('user_id', $user->id)->first();
            $package = PlanPackage::query()->where('id', $referalLink->package_id)->first();

            /* add package to jamaah */
            $packageService = new PackageService(1);
            $packageService->addPackageToJamaah($package, $jamaah);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function createReferalLink(array $input)
    {
        try {
            $hashable = Str::random(10);
            $newReferalLink = new ReferralLink([
                'tenant_id' => 1,
                'link' => route('invite.link', [$hashable, 'login']),
                'hash' => $hashable,
            ]);

            /* insert package */
            $package = PlanPackage::query()->where('id', $input['package_id'])->first();
            $newReferalLink->package()->associate($package);

            /* insert created by */
            $user = User::query()->find(auth()->user()->id);
            $newReferalLink->createdBy()->associate($user);

            $newReferalLink->push();
            return $newReferalLink;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

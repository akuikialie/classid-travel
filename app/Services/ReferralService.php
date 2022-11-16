<?php

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\Referral\ReferralLink;
use App\Models\Referral\UserInvitation;
use App\Models\User;

use Illuminate\Support\Str;

class ReferralService
{
    public function __construct(
        private readonly int $tenantId
    )
    {
        //
    }

    public function saveInvitedPerson(ReferralLink $referralLink, User $user = null): void
    {
        try {
            /* add user invited detail */
            UserInvitation::query()->create(
                [
                    'tenant_id' => $referralLink->tenant_id,
                    'user_id' => $user->id,
                    'invited_by' => $referralLink->created_by,
                    'link_id' => $referralLink->id,
                ]
            );

            $jamaah = Jamaah::query()->where('user_id', $user->id)->first();
            $package = PlanPackage::query()->where('id', $referralLink->package_id)->first();

            /* add package to jamaah */
            $packageService = new PackageService($referralLink->tenant_id);
            $packageService->addPackageToJamaah($package, $jamaah);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function createReferralLink(array $input, User $user): ReferralLink
    {
        try {
            $hashable = Str::random(10);
            $newReferralLink = new ReferralLink([
                'tenant_id' => $this->tenantId,
                'package_id' => $input['package_id'],
                'created_by' => $user->id,
                'link' => route('invite.link', [$hashable, 'login']),
                'hash' => $hashable,
            ]);
            $newReferralLink->push();
            return $newReferralLink;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

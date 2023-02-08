<?php

namespace App\Services;

use App\Exceptions\HandleCatchableException;
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
    }

    /**
     * @throws \Throwable
     * @throws HandleCatchableException
     */
    public function saveInvitedPerson(ReferralLink $referralLink, User $user = null): void
    {
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

        /* begin:: add package to jamaah */

        /* begin:: validation */
        // check package on jamaah
        $existingPackageInJamaah = collect($jamaah->planPackages)->where('id', $package->id)->first();
        if ($existingPackageInJamaah) {
            throw HandleCatchableException::catchable('Kamu sudah mengambil paket ini!.');
        }
        /* end:: validation */

        (new JamaahService($referralLink->tenant_id))
            ->setPackage(package: $package)
            ->setJamaah(jamaah: $jamaah)
            ->addPackage();
        /* end:: add package to jamaah */
    }


    public function createReferralLink(array $input, User $user): ReferralLink
    {
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
    }
}

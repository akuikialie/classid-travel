<?php

namespace App\Services;

use App\Enums\VirtualAccount;
use App\Exceptions\HandleCatchableException;
use App\Models\Jamaah\Jamaah;
use App\Models\Jamaah\JamaahHistory;
use App\Models\Plan\PlanPackage;
use App\Models\Tenant\Tenant;
use App\Models\User;
use Throwable;

class JamaahService
{

    private ?Jamaah $jamaah = null;
    private ?PlanPackage $package = null;
    private ?User $user = null;

    public function __construct(
        private readonly int $tenantId
    )
    {
    }

    /**
     * @param string $key
     * @return void
     * @throws Throwable
     */
    public function addPackage(string $key = 'perencanaan'): static
    {
        /* begin:: initialize */
        $jamaah = $this->getJamaah();
        $package = $this->getPackage();
        /* end:: initialize */

        /* begin:: add package to jamaah */
        $jamaah->planPackages()->attach($this->package->id);
        /* end:: add package to jamaah */

        /* begin:: add departure */
        $this->addDeparture();
        /* end:: add departure */

        /* begin:: creating va for payment package */
        $VAService = new VirtualAccountService($this->tenantId);
        $VAService->vaType(VirtualAccount::tryFrom($key)->keyValue())
            ->addToPlan($package)
            ->createFor($jamaah)
            ->createVA();
        /* end:: creating va for payment package */

        return $this;
    }

    public function addDeparture(string $detail = null): static
    {
        $jamaah = $this->getJamaah();
        $newDepartureHistory = new JamaahHistory([
            'tenant_id' => $this->tenantId,
            'detail' => $detail,
        ]);

        $jamaah->departureHistory()->save($newDepartureHistory);

        return $this;
    }

    /**
     * @param array $input
     * @return JamaahService
     * @throws HandleCatchableException
     */
    public function createJamaah(array $input = []): static
    {
        $user = $this->getUser();
        $input = array_merge([
            'tenant_id' => $this->tenantId,
            'user_id' => $user->id,
        ], $input);
        $this->jamaah = Jamaah::query()->create($input);
        return $this;
    }

    /**
     * @param array $linkIds
     * @return $this
     * @throws HandleCatchableException
     */
    public function linkDeparture(array $linkIds): static
    {
        $jamaah = $this->getJamaah();
        foreach ($linkIds as $key => $value) {
            $jamaah->$key = $value;
        }
        $jamaah->save();
        return $this;
    }

    /**
     * @return Jamaah
     * @throws HandleCatchableException
     */
    public function getJamaah(): Jamaah
    {
        if (!$this->jamaah instanceof Jamaah) {
            throw HandleCatchableException::catchable('Jamaah tidak di ditemukan!');
        }
        return $this->jamaah;
    }

    /**
     * @param Jamaah $jamaah
     * @return JamaahService
     */
    public function setJamaah(Jamaah $jamaah): static
    {
        $this->jamaah = $jamaah;
        return $this;
    }

    /**
     * @param PlanPackage $package
     * @return JamaahService
     */
    public function setPackage(PlanPackage $package): JamaahService
    {
        $this->package = $package;
        return $this;
    }

    /**
     * @return PlanPackage
     * @throws HandleCatchableException
     */
    public function getPackage(): PlanPackage
    {
        if (!$this->package instanceof PlanPackage) {
            throw HandleCatchableException::catchable('Paket tidak di ditemukan!');
        }
        return $this->package;
    }

    /**
     * @param User $user
     * @return JamaahService
     */
    public function setUser(User $user): JamaahService
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     * @throws HandleCatchableException
     */
    public function getUser(): User
    {
        if (!$this->user instanceof User) {
            throw HandleCatchableException::catchable('User tidak di ditemukan!');
        }
        return $this->user;
    }
}

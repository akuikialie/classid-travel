<?php

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use App\Services\EWallet\Entity\WalletUser;
use App\Services\EWallet\WalletService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class VirtualAccountService
{
    private $query;
    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = VirtualAccount::query();
    }

    /**
     * @param string $hash
     * @return $this
     */
    public function byHash(string $hash): static
    {
        $this->query->byHashOrFail($hash);
        return $this;
    }

    protected $model;

    /**
     * @param $model
     * @return $this
     * @throws \Throwable
     */
    public function createFor($model): static
    {
        $this->model = $model;
        return $this;
    }

    protected string $vaType = 'tabungan';

    /**
     * @param $vaType
     * @return $this
     */
    public function vaType($vaType): static
    {
        $this->vaType = $vaType;
        return $this;
    }

    protected PlanPackage $planPackage;
    /**
     * @param PlanPackage $planPackage
     * @return $this
     */
    public function addToPlan(PlanPackage $planPackage): static
    {
        $this->planPackage = $planPackage;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function createVA(): VirtualAccount
    {
        /* begin:: generate new VA ID */
        $VA = VirtualAccount::query()
            ->where(function (Builder $subQuery) {
                $subQuery
                    ->where('va_label', $this->vaType)
                    ->where('tenant_id', $this->tenantId)
                    ->whereMonth('created_at', Carbon::now());
            })
            ->latest('id')
            ->first('va_number');

        $VANumber = createNewVA($this->vaType, $VA->va_number ?? null);

        $setEmail = "{$VANumber}@prohajj.app";

        $newVA = new VirtualAccount([
            'tenant_id' => $this->tenantId,
            'va_number' => $VANumber,
            'va_label' => $this->vaType,
            'email' => $setEmail,
        ]);
        $this->model->tabungan()->save($newVA);

        $name = null;
        if (isset($this->planPackage) and $this->model instanceof Jamaah){
            $name = $this->model->user->name . ' - '. $this->planPackage->name;
            $newVA->myPackage()->associate($this->planPackage);
            $newVA->save();
        }

        if ($this->model instanceof User){
            $name = $this->model->name . ' - Tabungan';
        }

        if (is_null($name)){
            throw new Exception('Pemilik va tidak di ketahui!');
        }
        $newVA->name = $name;
        $newVA->password = "{$newVA->id}@{$VANumber}";
        $newVA->push();

        $newVA = $newVA->fresh();

        $wallet = new WalletService();
        $wallet->admin();
        $createWallet = $wallet->createUser($newVA->id, $VANumber, $name, email: $setEmail);

        if (!$createWallet instanceof WalletUser){
            throw new Exception('Virtual account gagal di buat!');
        }

        return $newVA;
        /* end:: create new VA */
    }
}

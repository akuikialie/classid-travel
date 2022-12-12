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
use http\Exception\InvalidArgumentException;
use Laravel\Octane\Exceptions\DdException;

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
            ->where(function ($subQuery) {
                $subQuery->where('va_label', $this->vaType)
                    ->whereMonth('created_at', Carbon::now());
            })->max('va_number');


        $VANumber = createNewVA($this->vaType, $VA);

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

//    public function createVirtualAccount($va_type, Jamaah $jamaah = null, PlanPackage $planPackage = null): void
//    {
//        try {
//            /* create new VA */
//            $VA = VirtualAccount::query()
//                ->where(function ($subQuery) use($va_type) {
//                    $subQuery->where('va_label', $va_type)
//                        ->whereMonth('created_at', Carbon::now());
//                })->max('va_number');
//
//            $newVANumber = createNewVA($va_type, $VA);
//
//            switch ($va_type) {
//                case 'tabungan':
//                    $user = User::query()->find(auth()->user()->id);
//                    $newVA = new VirtualAccount([
//                        'tenant_id' => 1,
//                        'va_number' => $newVANumber,
//                        'va_label' => $va_type,
//                    ]);
//                    $user->tabungan()->save($newVA);
//                    $user->save();
//                    break;
//
//                case 'perencanaan':
//                    $newVA = new VirtualAccount([
//                        'tenant_id' => 1,
//                        'va_number' => $newVANumber,
//                        'va_label' => $va_type,
//                    ]);
//                    $jamaah->tabunganPackages()->save($newVA);
//
//                    $newVA->myPackage()->associate($planPackage);
//                    $newVA->save();
//                    break;
//
//                default:
//                    break;
//            }
//        } catch (\Throwable $th) {
//            throw $th;
//        }
//    }
}

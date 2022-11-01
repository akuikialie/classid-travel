<?php

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Laravel\Octane\Exceptions\DdException;

class VirtualAccountService
{
    public $query;
    public function __construct()
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
     * @throws DdException
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
        $newVA = new VirtualAccount([
            'tenant_id' => $this->model->tenant?->id,
            'va_number' => $VANumber,
            'va_label' => 'tabungan',
        ]);

        $this->model->tabungan()->save($newVA);

        if (isset($this->planPackage) and $this->model instanceof Jamaah){
            $newVA->myPackage()->associate($this->planPackage);
            $newVA->save();
        }

        return $newVA;
        /* end:: create new VA */
    }

    public function createVirtualAccount($va_type, Jamaah $jamaah = null, PlanPackage $planPackage = null)
    {
        try {
            /* create new VA */
            $VA = VirtualAccount::query()
                ->where(function ($subQuery) use($va_type) {
                    $subQuery->where('va_label', $va_type)
                        ->whereMonth('created_at', Carbon::now());
                })->max('va_number');

            $newVANumber = createNewVA($va_type, $VA);

            switch ($va_type) {
                case 'tabungan':
                    $user = User::query()->find(auth()->user()->id);
                    $newVA = new VirtualAccount([
                        'tenant_id' => 1,
                        'va_number' => $newVANumber,
                        'va_label' => $va_type,
                    ]);

                    $user->tabungan()->save($newVA);
                    $user->save();
                    break;

                case 'perencanaan':
                    $newVA = new VirtualAccount([
                        'tenant_id' => 1,
                        'va_number' => $newVANumber,
                        'va_label' => $va_type,
                    ]);

                    $jamaah->tabunganPackages()->save($newVA);

                    $newVA->myPackage()->associate($planPackage);
                    $newVA->save();
                    break;

                default:
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\Jamaah\JamaahHistory;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService
{
    private Builder $query;

    private User $user;
    public function __construct(
        private readonly ?int $tenantId = null
    )
    {
        $this->query = User::query();
    }

    /**
     * @throws Throwable
     */
    public function createVa(string $vaType): static
    {
        /* begin:: start Virtual Account Service */
        $user = $this->getUser();
        $VAService = new VirtualAccountService($this->tenantId);
        $VAService->vaType($vaType)
            ->createFor($user)
            ->createVA();
        /* end:: start Virtual Account Service */

        return $this;
    }

    /**
     * @param string $status
     * @return $this
     * @throws Exception
     */
    public function setStatus(string $status): static
    {
        $user = $this->getUser();
        $user->status = $status;
        $user->save();

        return $this;
    }

    /**
     * @param array $input
     * @param bool $isJamaah
     * @return $this
     */
    public function createNewUser(array $input, bool $isJamaah = true): static
    {
        if (is_int($this->tenantId)) {
            $input = array_merge(['tenant_id' => $this->tenantId], $input);
        }

        $input = array_merge($input, ['password' => Hash::make($input['password'])]);
        $this->user = $this->query->create($input);

        if ($isJamaah) {
            $newJamaah = new Jamaah([
                'tenant_id' => $this->tenantId,
            ]);
            $this->user->jamaah()->save($newJamaah);
        }

        return $this;
    }

    /**
     * @param string|null $status
     * @param string|null $detail
     * @return $this
     * @throws Exception
     */
    public function setDepartureStatus(string $status = null, string $detail = null): static
    {
        try {
            $user = $this->getUser()->fresh(['jamaah']);

            $input = [
                'tenant_id' => $this->tenantId,
                'jamaah_id' => $user->jamaah->id,
            ];

            if (!is_null($status)) {
                $input = array_merge($input, [
                    'departure_status' => $status,
                ]);
            }
            if (!is_null($detail)) {
                $input = array_merge($input, [
                    'detail' => $detail,
                ]);
            }

            JamaahHistory::query()->create($input);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param ...$roles
     * @return $this
     * @throws Exception
     */
    public function setRole(...$roles): static
    {
        /* begin:: permissions Service */
        $user = $this->getUser();
        $permissionService = new PermissionService(tenantId: $this->tenantId);
        $permissionService->syncRole($user, $roles);
        /* end:: permissions Service */
        return $this;
    }

    /**
     * @param bool $isSuper
     * @return $this
     * @throws Exception
     */
    public function setIsSuper(bool $isSuper = false): static
    {
        try {
            $user = $this->getUser();
            $user->is_super = $isSuper;
            $user->save();
            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array|null $withRelation
     * @return Model|Builder|User|null
     */
    public function get(?array $withRelation = []): Model|Builder|User|null
    {
        return $this->query
            ->when(count($withRelation) > 0, function (Builder $subQuery) use ($withRelation){
                $subQuery->with($withRelation);
            })
            ->first();
    }

    /**
     * @throws Exception
     */
    public function getUser(): User
    {
        if ($this->query->count() > 1) {
            if (isset($this->user)) {
                $user = $this->user;
            } else {
                throw new Exception('Data harus spesifik!.');
            }
        } else {
            $user = $this->query->first();
        }

        return $user;
    }

    /**
     * @param User $user
     * @return UserService
     */
    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Octane\Exceptions\DdException;
use Throwable;

class UserService
{
    public Builder $query;
    public function __construct()
    {
        $this->query = User::query();
    }

    /**
     * @param $hash
     * @return $this
     */
    public function byHash(string $hash): static
    {
        $this->query->byHashOrFail($hash);
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function byId(int $id): static
    {
        $this->query->findOrFail($id);
        return $this;
    }

    /**
     * @param int $id
     * @return User|array|Builder|Collection|Model|string|null
     */
    public function findById(int $id)
    {
        return $this->query->where('id', $id)->first();
    }

    /**
     * @throws Throwable
     * @throws DdException
     */
    public function createVa(string $vaType): VirtualAccount
    {
        $user = $this->query->first();

        /* begin:: start Virtual Account Service */
        $VAService = new VirtualAccountService();
        return $VAService->vaType($vaType)
            ->createFor($user)
            ->createVA();
        /* end:: start Virtual Account Service */
    }
}

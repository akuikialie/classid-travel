<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Jamaah\Jamaah;
use App\Models\Jamaah\JamaahHistory;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Laravel\Octane\Exceptions\DdException;
use Throwable;

class UserService
{
    private Builder $query;

    private User $user;

    public function __construct(
        private readonly int $tenantId
    )
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

    public function createVa(string $vaType): static
    {
        /* begin:: start Virtual Account Service */
        try {
            $user = $this->user();
            $VAService = new VirtualAccountService($this->tenantId);
            $VAService->vaType($vaType)
                ->createFor($user)
                ->createVA();
        } catch (Throwable $e) {
            throw $e;
        }
        /* end:: start Virtual Account Service */

        return $this;
    }

    /**
     * @param array $input
     * @return $this
     */
    public function createNewUser(array $input): static
    {
        $input = array_merge(['tenant_id' => $this->tenantId], $input);
        $input = array_merge($input, ['password' => Hash::make($input['password'])]);
        $this->user = $this->query->create($input);
        $newJamaah = new Jamaah([
            'tenant_id' => $this->tenantId,
        ]);
        $this->user->jamaah()->save($newJamaah);

        return $this;
    }

    /**
     * @param array $input
     * @return $this
     * @throws Exception
     */
    public function setDepartureStatus(string $status = null, string $detail = null): static
    {
        try {
            $user = $this->user()->fresh(['jamaah']);

            $input = [
                'tenant_id' => $this->tenantId,
                'jamaah_id' => $user->jamaah->id,
            ];

            if (!is_null($status)){
                $input = array_merge($input, [
                    'departure_status' => $status,
                ]);
            } if (!is_null($detail)){
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

    public function setRole(...$roles)
    {
        try {
            $user = $this->user();
            $user->syncRoles($roles);
            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get()
    {
        return $this->query->first();
    }

    public function user(): User
    {
        if ($this->query->count() > 1) {
            if (isset($this->user) and $this->user instanceof User) {
                $user = $this->user;
            } else {
                throw new Exception('Tujuan belum di konfigurasi');
            }
        } else {
            $user = $this->query->first();
        }

        return $user;
    }
}

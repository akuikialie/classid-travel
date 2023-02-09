<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\HandleCatchableException;
use App\Models\Jamaah\JamaahHistory;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService
{
    private ?User $user = null;
    public function __construct(
        private readonly ?int $tenantId = null
    ) {
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
     * @throws HandleCatchableException
     */
    public function createNewUser(array $input, bool $isJamaah = true): static
    {
        if (is_int($this->tenantId)) {
            $input = array_merge(['tenant_id' => $this->tenantId], $input);
        }

        $input = array_merge($input, ['password' => Hash::make($input['password'])]);

        /* begin:: create new user */
        $this->user = User::query()->create($input);
        /* end:: create new user */

        /* begin:: add jamaah */
        if ($isJamaah) {
            (new JamaahService(tenantId: $this->tenantId))
                ->setUser($this->user)
                ->createJamaah();
        }
        /* end:: add jamaah */

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
        (new PermissionService(tenantId: $this->tenantId))
            ->syncRole($roles, $user);
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
        $user = $this->getUser();
        $user->is_super = $isSuper;
        $user->save();
        return $this;
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

    /**
     * @throws HandleCatchableException
     */
    public function unsetAvatar(): static
    {
        $avatar = $this->getUser();
        $avatar->clearMediaCollection('avatars');
        return $this;
    }

    /**
     * @return $this
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws Exception
     */
    public function setAvatar(Request $request): static
    {
        $user = $this->getuser();

        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')
                ->toMediaCollection('avatars');
        }
        return $this;
    }

    /**
     * @param array $input
     * @param User
     * @return Tenant|null
     * @throws Exception
     */
    public function update(array $input)
    {
        $user = $this->getuser();
        // if (isset($user) and $user->id != null) {
        //     $user->name = $input['name'];
        //     $user->username = $input['username'];
        //     $user->phone = $input['phone'];
        // }

        foreach ($input as $key => $value) {
            $user->$key = $value;
        }
        // $request = request()->only(['name', 'username','phone']);

        $user->save();

        return $this;
    }
}

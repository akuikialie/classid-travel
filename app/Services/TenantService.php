<?php

namespace App\Services;

use App\Exceptions\HandleCatchableException;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantData;
use App\Models\User;
use App\Models\Base\Media;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TenantService
{
    private ?Tenant $tenant = null;

    /**
     * @param int|null $tenantId
     */
    public function __construct(
        private readonly ?int $tenantId = null
    ) {
    }

    /**
     * @param bool $status
     * @return $this
     * @throws Exception
     */
    public function setStatus(bool $status): static
    {
        $tenant = $this->getTenant();
        $tenant->is_active = $status;
        $tenant->save();

        return $this;
    }

    /**
     * @throws HandleCatchableException
     * @throws Exception
     */
    public function createNewTenant(array $input): static
    {
        /* begin: check app domain is existed */
        $appDomain = Str::lower($input['app_domain']);
        if (str_contains($input['app_domain'], ' ')) {
            $appDomain = Str::lower(str_replace(' ', '.', $input['app_domain']));
        }

        $input = array_merge($input, [
            'is_active' => false,
            'app_domain' => $appDomain,
            'wallet_login' => json_encode([
                'WALLET_URL' => "https://demo.biznet.class.id",
                'WALLET_BCN' => "857400",
                'WALLET_ADMIN_USER' => "fahrudinsidik88@gmail.com",
                'WALLET_ADMIN_PASS' => "password",
            ])
        ]);
        $validAppDomain = dns_get_record($input['app_domain']);
        if (!is_array($validAppDomain) || count($validAppDomain) < 1) {
            throw HandleCatchableException::catchable(message: 'App domain tidak tersedia!');
        }
        /* end: check app domain is existed */

        /* begin:: create new tenant */
        $this->tenant = Tenant::query()->create($input);
        /* end:: create new tenant */

        /* begin:: user service -- create admin account + set is super == true (fix) */
        (new UserService(tenantId: $this->tenant->id))
            ->createNewUser([
                'name' => $input['name'],
                'phone' => $input['phone'],
                'username' => 'admin',
                'password' => 'admin',
            ], false)
            ->setRole('administrator')
            ->setIsSuper(true);
        /* end:: user service -- create admin account + set is super == true (fix) */

        return $this;
    }

    /**
     * @param Tenant $tenant
     * @return $this
     */
    public function setTenant(Tenant $tenant): static
    {
        $this->tenant = $tenant;

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
        $tenant = $this->getTenant();

        if ($request->hasFile('avatar')) {
            $tenant->addMediaFromRequest('avatar')
                ->toMediaCollection('avatars');
        }
        return $this;
    }

    /**
     * @throws HandleCatchableException
     */
    public function unsetAvatar(): static
    {
        $avatar = $this->getTenant();
        $avatar->clearMediaCollection('avatars');
        return $this;
    }

    /**
     * @throws \Throwable
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addMediaCollection(Request $request, string $collectionName): static
    {
        $tenant = $this->getTenant();
        if ($request->hasfile('collections')) {
            foreach ($request->file('collections') as $key => $media) {
                $tenant
                    ->addMedia($media)
                    ->withCustomProperties([
                        'order' => $key,
                        'url' => 'some url',
                        'short description' => 'some description',
                    ])
                    ->toMediaCollection($collectionName);
            }
        }

        return $this;
    }

    /**
     * @param array $input
     * @param User|null $user
     * @return Tenant|null
     * @throws Exception
     */
    public function update(array $input, User $user = null): ?Tenant
    {
        $tenant = $this->getTenant();
        if (isset($user) and $user->tenant_id === null) {
            $tenant->BCN = $input['BCN'];
            $tenant->app_domain = $input['app_domain'];
        } else if (isset($user) and $user->tenant_id !== null) {
            $tenant->name = $input['name'];
            $tenant->slug = $input['slug'];
        }
        $tenant->save();

        return $tenant->fresh();
    }

    /**
     * @return Tenant|null
     * @throws HandleCatchableException
     */
    public function getTenant(): ?Tenant
    {
        if (!$this->tenant instanceof Tenant) {
            throw HandleCatchableException::catchable('Travel tidak di ditemukan!');
        }
        return $this->tenant;
    }

    /**
     * @param array $themes
     * @return $this
     */
    public function changeTheme( array $themes): static
    {
        foreach ($themes as $key => $theme){
            TenantData::query()->updateOrCreate([
                'tenant_id' => $this->tenantId,
                'key' => $key,
                ],[
                'value' => $theme,
                'options' => null,
                'is_active' => true
            ]);
        }
        return $this;
    }
}

<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\Tenant\Tenant;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TenantService
{
    private Builder $query;
    private Tenant $tenant;

    public function __construct(
        private readonly int $tenantId
    )
    {
        $this->query = Tenant::query();
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

    public function unsetAvatar()
    {

    }

    /**
     * @throws \Throwable
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addMediaCollection(Request $request, string $collectionName): static
    {
        try {
            $tenant = $this->getTenant();
            if ($request->hasfile('collections')) {
                foreach ($request->file('collections') as $key => $media){
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
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this;
    }

    /**
     * @param array $input
     * @param User|null $user
     * @return Tenant|null
     * @throws Exception
     */
    public function update(array $input,User $user = null): ?Tenant
    {
        $tenant = $this->getTenant();
        if (isset($user) and $user->tenant_id === null){
            $tenant->BCN = $input['BCN'];
            $tenant->app_domain = $input['app_domain'];
        }else if (isset($user) and $user->tenant_id !== null){
            $tenant->name = $input['name'];
            $tenant->slug = $input['slug'];
        }
        $tenant->save();

        return $tenant->fresh();
    }

    /**
     * @return Model|Builder|null
     */
    public function get(): Model|Builder|null
    {
        return $this->query->first();
    }

    /**
     * @return Tenant
     * @throws Exception
     */
    public function getTenant(): Tenant
    {
        if ($this->query->count() > 1) {
            if (isset($this->tenant)) {
                $tenant = $this->tenant;
            } else {
                throw new Exception('Data harus spesifik!.');
            }
        } else {
            $tenant = $this->query->first();
        }

        return $tenant;
    }

    /**
     * @param Tenant $tenant
     */
    public function setTenant(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

}

<?php

namespace App\Services;

use App\Models\Tenant\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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
     * @param int|null $tenantId
     * @return $this
     */
    public function tenantId(int $tenantId = null): static
    {
        $this->query->where('id', ($tenantId ?? $this->tenantId));
        return $this;
    }

    /**
     * @return $this
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setAvatar(Request $request): static
    {
        try {
            $tenant = $this->tenant();

            if ($request->hasFile('avatar')) {
                $tenant->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatars');
            }
            return $this;
        } catch (FileDoesNotExist|FileIsTooBig|Exception $e) {
            throw $e;
        }
    }

    public function unsetAvatar()
    {

    }

    public function get()
    {
        return $this->query->first();
    }

    public function tenant(): Tenant
    {
        if ($this->query->count() > 1) {
            if (isset($this->tenant) and $this->tenant instanceof Tenant) {
                $tenant = $this->tenant;
            } else {
                throw new Exception('Tenant belum di konfigurasi');
            }
        } else {
            $tenant = $this->query->first();
        }

        return $tenant;
    }

}

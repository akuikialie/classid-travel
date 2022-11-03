<?php

namespace App\Services;

use App\Models\Tenant\Tenant;
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

    /**
     * @throws \Throwable
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addMediaCollection(Request $request, string $collectionName): static
    {
        try {
            $tenant = $this->tenant();
            if ($request->hasfile('collections')) {
//                $tenant->clearMediaCollection($collectionName);
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
     * @return Tenant|null
     * @throws Exception
     */
    public function update(array $input): ?Tenant
    {
        $tenant = $this->tenant();
        $tenant->name = $input['name'];
        $tenant->slug = $input['slug'];
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

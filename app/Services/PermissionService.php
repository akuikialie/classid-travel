<?php

namespace App\Services;

use App\Models\Spatie\Role;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class PermissionService
{
    public function __construct(
        private readonly ?int $tenantId = null
    )
    {
//
    }

    /**
     * @param array $input
     * @param string|null $guard
     * @return $this
     */
    public function createNewRole(array $input, ?string $guard = 'web'): static
    {
        if ($guard){
            $input = array_merge($input, [
                'guard_name' => $guard,
            ]);
        }
        Role::query()->create($input);
        return $this;
    }

    /**
     * @param $model
     * @param array $roles
     * @return $this
     */
    public function syncRole($model, array $roles): static
    {
        $role = Role::query();
        if (is_null($this->tenantId)){
            $role->whereNull('tenant_id')
                ->whereIn('name', collect($roles)->toArray());
        }else{
            $role
                ->tenantId(tenantId: $this->tenantId)
                ->whereIn('name', collect($roles)->toArray());

            $checkRole = $this->isRoleExists($role);
            if (!$checkRole){
                $role->orWhereNull('tenant_id')
                    ->whereIn('name', collect($roles)->toArray());
            }
        }

        $checkRole = $this->isRoleExists($role);
        if ($checkRole){
            $model->syncRoles($role->pluck('id')->toArray());
        }else{
            throw RoleDoesNotExist::named(collect($roles)->first());
        }

        return $this;
    }

    private function isRoleExists(Builder $query): bool
    {
        if ($query->count() > 0){
            return true;
        }
        return false;
    }
}

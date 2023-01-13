<?php

namespace App\Services;

use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Octane\Exceptions\DdException;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use function PHPUnit\Framework\isNull;

class PermissionService
{
    private $model = null;
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
                'tenant_id' => $this->tenantId,
                'guard_name' => $guard,
            ]);
        }
        $this->model = Role::query()->create($input);
        return $this;
    }

    /**
     * @param null $modelClass
     * @param array $permissions
     * @return $this
     * @throws DdException
     */
    public function syncPermissions(array $permissions, $modelClass = null): static
    {
        $checkTypeData = collect($permissions)->first();
        $column = 'name';
        if (is_int($checkTypeData)){
            $column = 'id';
        }
        $permission = Permission::query()
            ->whereIn($column, $permissions)->get()->pluck('id')->toArray();

        ($modelClass ?? $this->model)->syncPermissions($permission);
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

<?php

namespace App\Services;

use App\Enums\PermissionType;
use App\Exceptions\HandleCatchableException;
use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class PermissionService
{
    private ?Model $model = null;

    public function __construct(
        private readonly int|null $tenantId = null
    )
    {
    }

    /**
     * @param array $input
     * @param string|null $guard
     * @return $this
     */
    public function createNewRole(array $input, string|null $guard = 'web'): static
    {
        if ($guard) {
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
        if (is_int($checkTypeData)) {
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
     * @throws DdException
     */
    public function syncRole(array $roles, $model): static
    {
        $role = Role::query();

        if (is_null($this->tenantId)) {
            $role->whereNull('tenant_id')
                ->whereIn('name', collect($roles)->toArray());
        } else {
            $role
                ->tenantId(tenantId: $this->tenantId)
                ->whereIn('name', collect($roles)->toArray());

            if (!$this->isRoleExists($role)) {
                $role->orWhereNull('tenant_id')
                    ->whereIn('name', collect($roles)->toArray());
            }

            if (!$this->isRoleExists($role)) {
                $permissions = Permission::query()->get()->pluck('id')->toArray();

                foreach (collect($roles) as $newRole) {
                    $this->createNewRole([
                        'label' => $newRole,
                        'name' => $newRole,
                        'type' => PermissionType::tenant->keyValue(),
                    ])
                        ->syncPermissions($permissions);
                }
            }
        }

        if ($this->isRoleExists($role)) {
            $model->syncRoles($role->pluck('id')->toArray());
        } else {
            throw RoleDoesNotExist::named(collect($roles)->first());
        }

        return $this;
    }

    private function isRoleExists(Builder $query): bool
    {
        if ($query->count() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @return Model|null
     * @throws HandleCatchableException
     */
    public function getModel(): ?Model
    {
        if (!$this->model instanceof Model){
            throw HandleCatchableException::catchable('Model tidak ditemukan!');
        }
        return $this->model;
    }

    /**
     * @param Model|null $model
     */
    public function setModel(?Model $model): void
    {
        $this->model = $model;
    }
}

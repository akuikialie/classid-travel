<?php

namespace App\Services;

use App\Contracts\RBAC\InteractsWithPermission;
use App\Enums\PermissionType;
use App\Exceptions\HandleCatchableException;
use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class PermissionService extends BaseService
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

    /**
     * @param InteractsWithPermission $permission
     * @param string $guardName
     * @param string|null $tenantId
     * @return Permission
     * @throws \Exception
     */
    public function createPermission(InteractsWithPermission $permission, string $guardName = 'web'): Permission
    {
        $permissionCheck = Permission::query()
            ->where('label', '=', $permission->getPermissionName())
            ->where('guard_name', '=', $guardName)
            ->first();

        if (!empty($permissionCheck)) {
            throw new \Exception(message: 'Permission already exists');
        }

        $newPermission = new Permission();
        $newPermission->name = $permission->getPermissionName();
        $newPermission->tenant_id = $this->tenantId;
        $newPermission->type = $permission->usesOn();
        $newPermission->label = $permission->getLabel();
        $newPermission->group = $permission::getGroupName();
        $newPermission->guard_name = $guardName;
        $newPermission->save();

        return $newPermission;
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

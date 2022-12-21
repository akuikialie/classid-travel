<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use App\Models\Spatie\Permission;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Database\Seeder;

class SeedRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedRoles = [
            [
                'name' => 'super-administrator',
                'type' => PermissionType::tenant->keyValue(),
            ],[
                'tenant_id' => 6,
                'name' => 'administrator',
                'type' => PermissionType::tenant->keyValue(),
            ],[
                'name' => 'jamaah',
                'type' => PermissionType::app->keyValue(),
            ],
        ];

        foreach ($seedRoles as $input){
            \DB::beginTransaction();
            try {
                $permissions = Permission::query()->get()->pluck('id')->toArray();
                /* begin: permission service */
                $permissionService = new PermissionService($input['tenant_id'] ?? null);
                $permissionService
                    ->createNewRole($input)
                    ->syncPermissions(permissions: $permissions);
                /* end: role service */
                \DB::commit();
            }catch (\Throwable $e){
                \DB::rollBack();
                $this->command->error($e->getMessage());
            }
        }
    }
}

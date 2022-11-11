<?php

namespace Database\Seeders;

use App\Enums\RoleType;
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
                'type' => RoleType::tenant->keyValue(),
            ],[
                'name' => 'administrator',
                'type' => RoleType::tenant->keyValue(),
            ],[
                'tenant_id' => 6,
                'name' => 'administrator',
                'type' => RoleType::tenant->keyValue(),
            ],[
                'name' => 'jamaah',
                'type' => RoleType::app->keyValue(),
            ],
        ];

        foreach ($seedRoles as $input){
            \DB::beginTransaction();
            try {
                /* begin: permission service */
                $permissionService = new PermissionService($input['tenant_id'] ?? null);
                $permissionService
                    ->createNewRole($input);
                /* end: role service */
                \DB::commit();
            }catch (\Throwable $e){
                \DB::rollBack();
                $this->command->error($e->getMessage());
            }
        }
    }
}

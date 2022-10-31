<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Spatie\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedRoles = RoleEnum::cases();

        foreach ($seedRoles as $key => $role) {
            Role::create([
                'name' => $role->keyValue(),
            ]);
        }
    }
}

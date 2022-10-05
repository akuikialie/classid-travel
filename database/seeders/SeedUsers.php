<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SeedUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedUser = [
            'name' => 'winata bayu',
            'username' => 'winata',
            'email' => 'winatabayu01@gmail.com',
            'password' => Hash::make('bayu'),
        ];

        User::query()->create($seedUser);
    }
}

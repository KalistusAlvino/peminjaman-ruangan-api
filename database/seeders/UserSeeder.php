<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $adminRole = Roles::create([
            'role_name' => 'admin'
        ]);
        $userRole = Roles::create([
            'role_name' => 'pengguna'
        ]);
        User::create([
            'username' => 'admin',
            'password' => 'password123',
            'role_id' => $adminRole->id
        ]);

        User::create([
            'username' => 'user',
            'password' => 'password123',
            'role_id' => $userRole->id
        ]);
    }
}

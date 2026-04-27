<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'a@hookurl.io',
            'password' => 'admin@213',
            'status' => 'active',
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@hookurl.io',
            'password' => 'user@213',
            'status' => 'active',
            'role_id' => 2,
        ]);
    }
}

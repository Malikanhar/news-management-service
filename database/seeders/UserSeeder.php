<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'is_admin' => true,
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Commenter',
            'email' => 'commenter@gmail.com',
            'is_admin' => false,
            'password' => bcrypt('password')
        ]);
    }
}

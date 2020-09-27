<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name'  => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123'),
            'role'  => 'admin'
        ]);

        User::create([
            'name'  => 'User',
            'email' => 'user@user.com',
            'password'  => bcrypt('123'),
            'role'  => 'users'
        ]);
    }
}

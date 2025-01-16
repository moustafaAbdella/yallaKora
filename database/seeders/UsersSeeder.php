<?php

namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'admin',
            'role'     => 'admin',
            'email'    => 'admin@gmail.com',
            'password' =>  bcrypt('123456')
        ],);
        User::factory()
        ->count(10)
        ->create();
    }
}

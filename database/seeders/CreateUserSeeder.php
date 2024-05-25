<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'=>'User',
                'email'=>'user@testing.com',
                'password' => bcrypt('12345678'),
                'role'=>0
            ],
            [
                'name'=>'Admin',
                'email'=>'admin@testing.com',
                'password' => bcrypt('12345678'),
                'role'=>1
            ],
            [
                'name'=>'Crew',
                'email'=>'crew@testing.com',
                'password' => bcrypt('12345678'),
                'role'=>2
            ],
        ];
        foreach ($users as $user) 
        {
            User::create($user);
        }
    }
}

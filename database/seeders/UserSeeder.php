<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
             'name' => 'Miheretab Alemu',
             'email' => 'admin@gmail.com',
             'password' => bcrypt('123456'),
             'role' => \App\Models\User::ROLE_ADMIN,
        ]);

        \App\Models\User::create([
             'name' => 'Daniel Alemu',
             'email' => 'user@gmail.com',
             'password' => bcrypt('123456')
        ]);
    }
}

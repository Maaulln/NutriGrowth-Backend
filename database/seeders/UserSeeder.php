<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();

        $users = [
            [
                'name'       => 'Admin NutriGrowth',
                'email'      => 'admin@gmail.com',
                'password'   => Hash::make('12345678'),
                'role'       => 'admin',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'name'       => 'Siti Rahayu',
                'email'      => 'user@gmail.com',
                'password'   => Hash::make('12345678'),
                'role'       => 'user',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'name'       => 'Budi Santoso',
                'email'      => 'budi@gmail.com',
                'password'   => Hash::make('12345678'),
                'role'       => 'user',
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
            [
                'name'       => 'Dewi Lestari',
                'email'      => 'dewi@gmail.com',
                'password'   => Hash::make('12345678'),
                'role'       => 'user',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'name'       => 'Ahmad Fauzi',
                'email'      => 'ahmad@gmail.com',
                'password'   => Hash::make('12345678'),
                'role'       => 'user',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'name'       => 'Rina Wati',
                'email'      => 'rina@gmail.com',
                'password'   => Hash::make('12345678'),
                'role'       => 'user',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'name'       => 'Hendra Gunawan',
                'email'      => 'hendra@gmail.com',
                'password'   => Hash::make('12345678'),
                'role'       => 'user',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}

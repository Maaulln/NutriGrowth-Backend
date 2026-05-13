<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('children')->truncate();

        $users = User::all();

        $boyNames = ['Budi', 'Aditya', 'Rian', 'Fatur', 'Zikri', 'Aris', 'Galang', 'Dimas'];
        $girlNames = ['Ani', 'Siti', 'Laras', 'Putri', 'Zaskia', 'Indah', 'Maya', 'Rara'];

        foreach ($users as $user) {
            $numChildren = rand(1, 3);
            
            for ($i = 0; $i < $numChildren; $i++) {
                $gender = rand(0, 1) ? 'male' : 'female';
                $name = ($gender === 'male') 
                    ? $boyNames[array_rand($boyNames)] . ' ' . $user->name
                    : $girlNames[array_rand($girlNames)] . ' ' . $user->name;
                
                // Random birth date between 6 months and 5 years ago
                $birthDate = now()->subMonths(rand(6, 60))->format('Y-m-d');

                Child::create([
                    'user_id' => $user->id,
                    'name' => $name,
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'image_url' => null,
                ]);
            }
        }
    }
}

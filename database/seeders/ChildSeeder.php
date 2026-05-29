<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChildSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('children')->truncate();

        // Data anak dengan profil realistis (termasuk beberapa kasus stunting)
        $childrenData = [
            // ── Siti Rahayu (user@gmail.com) ─────────────────────────────────
            [
                'email'      => 'user@gmail.com',
                'name'       => 'Rizky Pratama',
                'gender'     => 'male',
                'birth_date' => now()->subMonths(24)->format('Y-m-d'),
                'weight_kg'  => 10.5,
                'height_cm'  => 82.0,
                'muac_cm'    => 13.5,
            ],
            [
                'email'      => 'user@gmail.com',
                'name'       => 'Naila Sari',
                'gender'     => 'female',
                'birth_date' => now()->subMonths(36)->format('Y-m-d'),
                'weight_kg'  => 13.2,
                'height_cm'  => 91.0,
                'muac_cm'    => 14.8,
            ],

            // ── Budi Santoso ──────────────────────────────────────────────────
            [
                'email'      => 'budi@gmail.com',
                'name'       => 'Daffa Santoso',
                'gender'     => 'male',
                'birth_date' => now()->subMonths(18)->format('Y-m-d'),
                'weight_kg'  => 8.2,   // Potensi stunting
                'height_cm'  => 74.0,
                'muac_cm'    => 12.5,
            ],

            // ── Dewi Lestari ──────────────────────────────────────────────────
            [
                'email'      => 'dewi@gmail.com',
                'name'       => 'Zahra Lestari',
                'gender'     => 'female',
                'birth_date' => now()->subMonths(12)->format('Y-m-d'),
                'weight_kg'  => 8.0,
                'height_cm'  => 72.5,
                'muac_cm'    => 13.0,
            ],
            [
                'email'      => 'dewi@gmail.com',
                'name'       => 'Rafi Lestari',
                'gender'     => 'male',
                'birth_date' => now()->subMonths(48)->format('Y-m-d'),
                'weight_kg'  => 16.0,
                'height_cm'  => 100.0,
                'muac_cm'    => 15.5,
            ],

            // ── Ahmad Fauzi ───────────────────────────────────────────────────
            [
                'email'      => 'ahmad@gmail.com',
                'name'       => 'Keanu Fauzi',
                'gender'     => 'male',
                'birth_date' => now()->subMonths(30)->format('Y-m-d'),
                'weight_kg'  => 11.5,
                'height_cm'  => 86.0,
                'muac_cm'    => 14.2,
            ],

            // ── Rina Wati ─────────────────────────────────────────────────────
            [
                'email'      => 'rina@gmail.com',
                'name'       => 'Aisha Wati',
                'gender'     => 'female',
                'birth_date' => now()->subMonths(20)->format('Y-m-d'),
                'weight_kg'  => 9.8,
                'height_cm'  => 80.0,
                'muac_cm'    => 13.8,
            ],
            [
                'email'      => 'rina@gmail.com',
                'name'       => 'Farhan Wati',
                'gender'     => 'male',
                'birth_date' => now()->subMonths(8)->format('Y-m-d'),
                'weight_kg'  => 7.5,
                'height_cm'  => 67.0,
                'muac_cm'    => 13.0,
            ],

            // ── Hendra Gunawan ────────────────────────────────────────────────
            [
                'email'      => 'hendra@gmail.com',
                'name'       => 'Bintang Gunawan',
                'gender'     => 'male',
                'birth_date' => now()->subMonths(42)->format('Y-m-d'),
                'weight_kg'  => 14.8,
                'height_cm'  => 96.0,
                'muac_cm'    => 15.0,
            ],
        ];

        foreach ($childrenData as $data) {
            $user = User::where('email', $data['email'])->first();
            if (!$user) continue;

            Child::create([
                'user_id'    => $user->id,
                'name'       => $data['name'],
                'gender'     => $data['gender'],
                'birth_date' => $data['birth_date'],
                'weight_kg'  => $data['weight_kg'],
                'height_cm'  => $data['height_cm'],
                'muac_cm'    => $data['muac_cm'],
                'image_url'  => null,
            ]);
        }
    }
}

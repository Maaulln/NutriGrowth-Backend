<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing data to avoid duplicates
        DB::table('foods')->truncate();

        $foods = [
            // --- PROTEIN ---
            [
                'name' => 'Telur Ayam Rebus',
                'category' => 'protein',
                'calories' => 77, 'protein' => 6.3, 'fat' => 5.3, 'carbs' => 0.6,
                'price_per_serving' => 2500, 'serving_size' => '1 butir (50g)',
                'description' => 'Sumber protein hewani yang murah dan mudah didapat.',
            ],
            [
                'name' => 'Tempe Goreng',
                'category' => 'protein',
                'calories' => 150, 'protein' => 14, 'fat' => 7, 'carbs' => 9,
                'price_per_serving' => 1500, 'serving_size' => '2 potong (50g)',
                'description' => 'Protein nabati asli Indonesia dari fermentasi kedelai.',
            ],
            [
                'name' => 'Daging Sapi Cincang',
                'category' => 'protein',
                'calories' => 250, 'protein' => 26, 'fat' => 15, 'carbs' => 0,
                'price_per_serving' => 15000, 'serving_size' => '100g',
                'description' => 'Kaya akan zat besi dan vitamin B12 untuk pembentukan sel darah merah.',
            ],
            [
                'name' => 'Ikan Kembung Tim',
                'category' => 'protein',
                'calories' => 167, 'protein' => 19, 'fat' => 9, 'carbs' => 0,
                'price_per_serving' => 8000, 'serving_size' => '1 ekor sedang (100g)',
                'description' => 'Ikan lokal dengan kandungan Omega-3 yang sangat tinggi, bahkan melebihi Salmon.',
            ],
            [
                'name' => 'Tahu Sutra Rebus',
                'category' => 'protein',
                'calories' => 61, 'protein' => 7, 'fat' => 3.5, 'carbs' => 2,
                'price_per_serving' => 2000, 'serving_size' => '1 blok (100g)',
                'description' => 'Tekstur lembut, sangat cocok untuk MPASI dan balita.',
            ],
            [
                'name' => 'Hati Ayam Rebus',
                'category' => 'protein',
                'calories' => 116, 'protein' => 17, 'fat' => 5, 'carbs' => 0,
                'price_per_serving' => 3000, 'serving_size' => '1 buah (50g)',
                'description' => 'Sumber Vitamin A dan zat besi terbaik untuk mencegah stunting.',
            ],

            // --- CARBO ---
            [
                'name' => 'Nasi Putih',
                'category' => 'carbo',
                'calories' => 130, 'protein' => 2.7, 'fat' => 0.3, 'carbs' => 28,
                'price_per_serving' => 3000, 'serving_size' => '1 porsi (100g)',
                'description' => 'Sumber energi utama sehari-hari.',
            ],
            [
                'name' => 'Ubi Jalar Kukus',
                'category' => 'carbo',
                'calories' => 86, 'protein' => 1.6, 'fat' => 0.1, 'carbs' => 20,
                'price_per_serving' => 2500, 'serving_size' => '1 buah sedang (100g)',
                'description' => 'Karbohidrat kompleks yang kaya Beta-Karoten.',
            ],
            [
                'name' => 'Kentang Tumbuk (Mashed Potato)',
                'category' => 'carbo',
                'calories' => 87, 'protein' => 1.9, 'fat' => 0.1, 'carbs' => 20,
                'price_per_serving' => 4000, 'serving_size' => '1 mangkuk kecil',
                'description' => 'Mudah dicerna dan mengandung vitamin C serta kalium.',
            ],
            [
                'name' => 'Bubur Jagung Manis',
                'category' => 'carbo',
                'calories' => 96, 'protein' => 3.4, 'fat' => 1.5, 'carbs' => 21,
                'price_per_serving' => 3500, 'serving_size' => '100g',
                'description' => 'Sumber serat pangan yang baik untuk pencernaan.',
            ],

            // --- VEGETABLE ---
            [
                'name' => 'Sayur Bayam',
                'category' => 'vegetable',
                'calories' => 23, 'protein' => 2.9, 'fat' => 0.4, 'carbs' => 3.6,
                'price_per_serving' => 2000, 'serving_size' => '1 mangkuk (100g)',
                'description' => 'Kaya akan zat besi dan asam folat.',
            ],
            [
                'name' => 'Wortel Rebus',
                'category' => 'vegetable',
                'calories' => 41, 'protein' => 0.9, 'fat' => 0.2, 'carbs' => 10,
                'price_per_serving' => 1500, 'serving_size' => '2 buah sedang',
                'description' => 'Sangat tinggi Vitamin A untuk kesehatan mata.',
            ],
            [
                'name' => 'Labu Kuning Kukus',
                'category' => 'vegetable',
                'calories' => 26, 'protein' => 1, 'fat' => 0.1, 'carbs' => 6.5,
                'price_per_serving' => 3000, 'serving_size' => '100g',
                'description' => 'Rasa manis alami yang disukai anak-anak.',
            ],
            [
                'name' => 'Brokoli Kukus',
                'category' => 'vegetable',
                'calories' => 34, 'protein' => 2.8, 'fat' => 0.4, 'carbs' => 7,
                'price_per_serving' => 5000, 'serving_size' => '1 mangkuk kecil',
                'description' => 'Kaya kalsium dan vitamin K untuk kesehatan tulang.',
            ],

            // --- FRUIT ---
            [
                'name' => 'Pisang Ambon',
                'category' => 'fruit',
                'calories' => 89, 'protein' => 1.1, 'fat' => 0.3, 'carbs' => 23,
                'price_per_serving' => 2500, 'serving_size' => '1 buah (100g)',
                'description' => 'Energi cepat dan kalium untuk fungsi jantung.',
            ],
            [
                'name' => 'Alpukat Mentega',
                'category' => 'fruit',
                'calories' => 160, 'protein' => 2, 'fat' => 15, 'carbs' => 9,
                'price_per_serving' => 7000, 'serving_size' => '1/2 buah besar',
                'description' => 'Sumber lemak sehat (HDL) terbaik untuk perkembangan otak anak.',
            ],
            [
                'name' => 'Pepaya Matang',
                'category' => 'fruit',
                'calories' => 43, 'protein' => 0.5, 'fat' => 0.3, 'carbs' => 11,
                'price_per_serving' => 2000, 'serving_size' => '1 potong besar',
                'description' => 'Mengandung enzim papain untuk melancarkan pencernaan.',
            ],
            [
                'name' => 'Jeruk Manis',
                'category' => 'fruit',
                'calories' => 47, 'protein' => 0.9, 'fat' => 0.1, 'carbs' => 12,
                'price_per_serving' => 3000, 'serving_size' => '1 buah sedang',
                'description' => 'Vitamin C tinggi untuk meningkatkan imun tubuh.',
            ],

            // --- DAIRY ---
            [
                'name' => 'Susu Pertumbuhan',
                'category' => 'dairy',
                'calories' => 150, 'protein' => 8, 'fat' => 8, 'carbs' => 12,
                'price_per_serving' => 5000, 'serving_size' => '1 gelas (200ml)',
                'description' => 'Terfortifikasi vitamin dan mineral penting.',
            ],
            [
                'name' => 'Yogurt Plain',
                'category' => 'dairy',
                'calories' => 59, 'protein' => 10, 'fat' => 0.4, 'carbs' => 3.6,
                'price_per_serving' => 6000, 'serving_size' => '1 cup (100g)',
                'description' => 'Probiotik untuk menjaga kesehatan usus.',
            ],
            [
                'name' => 'Keju Cheddar Blok',
                'category' => 'dairy',
                'calories' => 402, 'protein' => 25, 'fat' => 33, 'carbs' => 1.3,
                'price_per_serving' => 4000, 'serving_size' => '1 potong kecil (20g)',
                'description' => 'Konsentrasi kalsium yang tinggi untuk gigi kuat.',
            ],
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }
    }
}

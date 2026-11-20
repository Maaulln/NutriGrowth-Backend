<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        Food::truncate();

        $foods = [
            // ── Protein ──────────────────────────────────────────────────────────
            [
                'name' => 'Boiled Egg', 'category' => 'protein',
                'calories' => 155, 'protein' => 12.6, 'fat' => 11.1, 'carbs' => 0.6, 'fiber_g' => 0.0,
                'serving_size' => '1 egg (50g)', 'serving_size_g' => 50,
                'price_per_serving' => 2500, 'price_min' => 2000, 'price_max' => 3000,
                'allergens' => 'egg', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Chicken Breast', 'category' => 'protein',
                'calories' => 165, 'protein' => 31.0, 'fat' => 3.6, 'carbs' => 0.0, 'fiber_g' => 0.0,
                'serving_size' => '3/4 piece (75g)', 'serving_size_g' => 75,
                'price_per_serving' => 8500, 'price_min' => 7000, 'price_max' => 10000,
                'allergens' => 'chicken', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Chicken Liver', 'category' => 'protein',
                'calories' => 101, 'protein' => 16.9, 'fat' => 3.4, 'carbs' => 0.8, 'fiber_g' => 0.0,
                'serving_size' => '1/2 piece (50g)', 'serving_size_g' => 50,
                'price_per_serving' => 5000, 'price_min' => 4000, 'price_max' => 6000,
                'allergens' => 'chicken', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Minced Beef', 'category' => 'protein',
                'calories' => 207, 'protein' => 18.8, 'fat' => 14.0, 'carbs' => 0.0, 'fiber_g' => 0.0,
                'serving_size' => '2 tbsp (50g)', 'serving_size_g' => 50,
                'price_per_serving' => 15000, 'price_min' => 12000, 'price_max' => 18000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Steamed White Tofu', 'category' => 'protein',
                'calories' => 68, 'protein' => 7.8, 'fat' => 4.6, 'carbs' => 1.6, 'fiber_g' => 0.2,
                'serving_size' => '1 large piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Steamed Tempeh', 'category' => 'protein',
                'calories' => 193, 'protein' => 18.3, 'fat' => 11.0, 'carbs' => 9.4, 'fiber_g' => 1.4,
                'serving_size' => '3/4 piece (75g)', 'serving_size_g' => 75,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Steamed Mackerel', 'category' => 'protein',
                'calories' => 103, 'protein' => 21.3, 'fat' => 1.0, 'carbs' => 0.0, 'fiber_g' => 0.0,
                'serving_size' => '1 small fish (75g)', 'serving_size_g' => 75,
                'price_per_serving' => 6500, 'price_min' => 5000, 'price_max' => 8000,
                'allergens' => 'fish', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Fresh Anchovies', 'category' => 'protein',
                'calories' => 77, 'protein' => 16.0, 'fat' => 1.2, 'carbs' => 0.0, 'fiber_g' => 0.0,
                'serving_size' => '3 tbsp (30g)', 'serving_size_g' => 30,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => 'fish', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Shrimp', 'category' => 'protein',
                'calories' => 84, 'protein' => 20.3, 'fat' => 0.4, 'carbs' => 0.0, 'fiber_g' => 0.0,
                'serving_size' => '5-6 small fish (60g)', 'serving_size_g' => 60,
                'price_per_serving' => 10000, 'price_min' => 8000, 'price_max' => 12000,
                'allergens' => 'seafood', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Mung Beans', 'category' => 'protein',
                'calories' => 118, 'protein' => 8.7, 'fat' => 0.5, 'carbs' => 21.2, 'fiber_g' => 7.6,
                'serving_size' => '5 tbsp (80g)', 'serving_size_g' => 80,
                'price_per_serving' => 2750, 'price_min' => 2000, 'price_max' => 3500,
                'allergens' => 'peanut', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Roasted Peanuts', 'category' => 'protein',
                'calories' => 452, 'protein' => 28.3, 'fat' => 30.4, 'carbs' => 16.1, 'fiber_g' => 8.5,
                'serving_size' => '2 tbsp (30g)', 'serving_size_g' => 30,
                'price_per_serving' => 2750, 'price_min' => 2000, 'price_max' => 3500,
                'allergens' => 'peanut', 'age_min_months' => 24, 'texture' => 'padat',
            ],
            [
                'name' => 'Steamed Salmon', 'category' => 'protein',
                'calories' => 208, 'protein' => 20.4, 'fat' => 13.4, 'carbs' => 0.0, 'fiber_g' => 0.0,
                'serving_size' => '1 piece (75g)', 'serving_size_g' => 75,
                'price_per_serving' => 25000, 'price_min' => 20000, 'price_max' => 30000,
                'allergens' => 'fish', 'age_min_months' => 6, 'texture' => 'lembut',
            ],

            // ── Karbohidrat ───────────────────────────────────────────────────────
            [
                'name' => 'White Rice', 'category' => 'carbo',
                'calories' => 175, 'protein' => 2.5, 'fat' => 0.1, 'carbs' => 40.6, 'fiber_g' => 0.3,
                'serving_size' => '1 scoop (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 3000, 'price_min' => 2000, 'price_max' => 4000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Brown Rice', 'category' => 'carbo',
                'calories' => 168, 'protein' => 3.4, 'fat' => 1.2, 'carbs' => 36.2, 'fiber_g' => 1.8,
                'serving_size' => '1 scoop (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Rice Porridge', 'category' => 'carbo',
                'calories' => 62, 'protein' => 1.1, 'fat' => 0.3, 'carbs' => 13.6, 'fiber_g' => 0.1,
                'serving_size' => '1 bowl (200g)', 'serving_size_g' => 200,
                'price_per_serving' => 4500, 'price_min' => 3000, 'price_max' => 6000,
                'allergens' => '', 'age_min_months' => 4, 'texture' => 'cair',
            ],
            [
                'name' => 'Steamed Yellow Sweet Potato', 'category' => 'carbo',
                'calories' => 123, 'protein' => 2.0, 'fat' => 0.1, 'carbs' => 29.6, 'fiber_g' => 3.0,
                'serving_size' => '1 medium piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2750, 'price_min' => 2000, 'price_max' => 3500,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Cassava', 'category' => 'carbo',
                'calories' => 154, 'protein' => 1.2, 'fat' => 0.3, 'carbs' => 36.8, 'fiber_g' => 1.7,
                'serving_size' => '1 piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Potato', 'category' => 'carbo',
                'calories' => 83, 'protein' => 2.0, 'fat' => 0.1, 'carbs' => 19.1, 'fiber_g' => 1.8,
                'serving_size' => '1 medium piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2750, 'price_min' => 2000, 'price_max' => 3500,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Sweet Corn', 'category' => 'carbo',
                'calories' => 96, 'protein' => 3.5, 'fat' => 1.3, 'carbs' => 20.9, 'fiber_g' => 2.4,
                'serving_size' => '1/2 cob (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'White Bread', 'category' => 'carbo',
                'calories' => 248, 'protein' => 8.0, 'fat' => 1.2, 'carbs' => 50.0, 'fiber_g' => 2.7,
                'serving_size' => '2 slices (40g)', 'serving_size_g' => 40,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => 'gluten', 'age_min_months' => 10, 'texture' => 'lembut',
            ],
            [
                'name' => 'Cooked Oatmeal', 'category' => 'carbo',
                'calories' => 71, 'protein' => 2.5, 'fat' => 1.5, 'carbs' => 12.0, 'fiber_g' => 1.7,
                'serving_size' => '1 bowl (160g)', 'serving_size_g' => 160,
                'price_per_serving' => 5500, 'price_min' => 4000, 'price_max' => 7000,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Cornstarch Porridge', 'category' => 'carbo',
                'calories' => 86, 'protein' => 0.3, 'fat' => 0.1, 'carbs' => 21.3, 'fiber_g' => 0.3,
                'serving_size' => '1 bowl (150g)', 'serving_size_g' => 150,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => '', 'age_min_months' => 4, 'texture' => 'cair',
            ],

            // ── Sayuran ───────────────────────────────────────────────────────────
            [
                'name' => 'Boiled Spinach', 'category' => 'vegetable',
                'calories' => 36, 'protein' => 3.5, 'fat' => 0.5, 'carbs' => 3.6, 'fiber_g' => 2.7,
                'serving_size' => '1 bowl (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Water Spinach', 'category' => 'vegetable',
                'calories' => 29, 'protein' => 3.0, 'fat' => 0.3, 'carbs' => 3.7, 'fiber_g' => 2.0,
                'serving_size' => '1 bowl (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 1500, 'price_min' => 1000, 'price_max' => 2000,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Steamed Broccoli', 'category' => 'vegetable',
                'calories' => 34, 'protein' => 2.8, 'fat' => 0.4, 'carbs' => 6.6, 'fiber_g' => 2.6,
                'serving_size' => '1 bowl (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Steamed Carrot', 'category' => 'vegetable',
                'calories' => 41, 'protein' => 0.9, 'fat' => 0.2, 'carbs' => 9.6, 'fiber_g' => 2.8,
                'serving_size' => '1 medium piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2500, 'price_min' => 2000, 'price_max' => 3000,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Steamed Pumpkin', 'category' => 'vegetable',
                'calories' => 26, 'protein' => 1.0, 'fat' => 0.1, 'carbs' => 6.5, 'fiber_g' => 0.5,
                'serving_size' => '1 bowl (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2500, 'price_min' => 2000, 'price_max' => 3000,
                'allergens' => '', 'age_min_months' => 4, 'texture' => 'lembut',
            ],
            [
                'name' => 'Steamed Chayote', 'category' => 'vegetable',
                'calories' => 19, 'protein' => 0.6, 'fat' => 0.1, 'carbs' => 4.5, 'fiber_g' => 1.7,
                'serving_size' => '1/2 piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Long Beans', 'category' => 'vegetable',
                'calories' => 44, 'protein' => 2.9, 'fat' => 0.4, 'carbs' => 7.8, 'fiber_g' => 3.2,
                'serving_size' => '1 handful (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Fresh Tomato', 'category' => 'vegetable',
                'calories' => 20, 'protein' => 0.9, 'fat' => 0.2, 'carbs' => 4.2, 'fiber_g' => 1.2,
                'serving_size' => '1 medium piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Boiled Cassava Leaves', 'category' => 'vegetable',
                'calories' => 56, 'protein' => 6.8, 'fat' => 0.3, 'carbs' => 8.8, 'fiber_g' => 5.0,
                'serving_size' => '3/4 bowl (75g)', 'serving_size_g' => 75,
                'price_per_serving' => 1500, 'price_min' => 1000, 'price_max' => 2000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],

            // ── Buah ──────────────────────────────────────────────────────────────
            [
                'name' => 'Banana', 'category' => 'fruit',
                'calories' => 92, 'protein' => 1.2, 'fat' => 0.2, 'carbs' => 23.4, 'fiber_g' => 2.6,
                'serving_size' => '1 medium piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 2750, 'price_min' => 2000, 'price_max' => 3500,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Ripe Papaya', 'category' => 'fruit',
                'calories' => 40, 'protein' => 0.6, 'fat' => 0.1, 'carbs' => 9.8, 'fiber_g' => 1.8,
                'serving_size' => '1 large piece (150g)', 'serving_size_g' => 150,
                'price_per_serving' => 2000, 'price_min' => 1500, 'price_max' => 2500,
                'allergens' => '', 'age_min_months' => 4, 'texture' => 'lembut',
            ],
            [
                'name' => 'Mango', 'category' => 'fruit',
                'calories' => 65, 'protein' => 0.4, 'fat' => 0.3, 'carbs' => 16.9, 'fiber_g' => 1.8,
                'serving_size' => '1/2 piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Sweet Orange', 'category' => 'fruit',
                'calories' => 45, 'protein' => 0.9, 'fat' => 0.2, 'carbs' => 11.2, 'fiber_g' => 2.4,
                'serving_size' => '1 piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 3250, 'price_min' => 2500, 'price_max' => 4000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Avocado', 'category' => 'fruit',
                'calories' => 160, 'protein' => 2.0, 'fat' => 14.7, 'carbs' => 8.5, 'fiber_g' => 6.7,
                'serving_size' => '1/2 piece (80g)', 'serving_size_g' => 80,
                'price_per_serving' => 6500, 'price_min' => 5000, 'price_max' => 8000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Watermelon', 'category' => 'fruit',
                'calories' => 30, 'protein' => 0.6, 'fat' => 0.2, 'carbs' => 7.6, 'fiber_g' => 0.4,
                'serving_size' => '1 piece (200g)', 'serving_size_g' => 200,
                'price_per_serving' => 2750, 'price_min' => 2000, 'price_max' => 3500,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Melon', 'category' => 'fruit',
                'calories' => 28, 'protein' => 0.6, 'fat' => 0.1, 'carbs' => 6.5, 'fiber_g' => 0.9,
                'serving_size' => '1 piece (150g)', 'serving_size_g' => 150,
                'price_per_serving' => 4000, 'price_min' => 3000, 'price_max' => 5000,
                'allergens' => '', 'age_min_months' => 6, 'texture' => 'lembut',
            ],
            [
                'name' => 'Pear', 'category' => 'fruit',
                'calories' => 57, 'protein' => 0.4, 'fat' => 0.1, 'carbs' => 15.5, 'fiber_g' => 3.1,
                'serving_size' => '1 small piece (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 5500, 'price_min' => 4000, 'price_max' => 7000,
                'allergens' => '', 'age_min_months' => 8, 'texture' => 'lembut',
            ],

            // ── Dairy ─────────────────────────────────────────────────────────────
            [
                'name' => 'Full-Cream Cow Milk', 'category' => 'dairy',
                'calories' => 61, 'protein' => 3.2, 'fat' => 3.3, 'carbs' => 4.8, 'fiber_g' => 0.0,
                'serving_size' => '1 glass (200ml)', 'serving_size_g' => 200,
                'price_per_serving' => 6500, 'price_min' => 5000, 'price_max' => 8000,
                'allergens' => 'milk', 'age_min_months' => 12, 'texture' => 'cair',
            ],
            [
                'name' => 'Plain Yogurt', 'category' => 'dairy',
                'calories' => 59, 'protein' => 3.7, 'fat' => 3.3, 'carbs' => 4.7, 'fiber_g' => 0.0,
                'serving_size' => '1 cup (100g)', 'serving_size_g' => 100,
                'price_per_serving' => 7000, 'price_min' => 5000, 'price_max' => 9000,
                'allergens' => 'milk', 'age_min_months' => 8, 'texture' => 'lembut',
            ],
            [
                'name' => 'Cheddar Cheese', 'category' => 'dairy',
                'calories' => 402, 'protein' => 25.0, 'fat' => 33.1, 'carbs' => 1.3, 'fiber_g' => 0.0,
                'serving_size' => '2 thin slices (30g)', 'serving_size_g' => 30,
                'price_per_serving' => 10000, 'price_min' => 8000, 'price_max' => 12000,
                'allergens' => 'milk', 'age_min_months' => 10, 'texture' => 'lembut',
            ],
            [
                'name' => 'Full-Cream UHT Milk', 'category' => 'dairy',
                'calories' => 63, 'protein' => 3.3, 'fat' => 3.5, 'carbs' => 4.9, 'fiber_g' => 0.0,
                'serving_size' => '1 carton (200ml)', 'serving_size_g' => 200,
                'price_per_serving' => 5500, 'price_min' => 4000, 'price_max' => 7000,
                'allergens' => 'milk', 'age_min_months' => 12, 'texture' => 'cair',
            ],
        ];

        foreach ($foods as $data) {
            Food::create($data);
        }

        $this->command->info('FoodSeeder: ' . count($foods) . ' foods seeded.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\MealCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Et ve Tavuk',
            'Sebze Yemekleri',
            'Kahvaltılıklar',
            'Tahıllar ve Baklagiller',
            'Süt ve Süt Ürünleri',
            'Meyveler',
            'Tatlılar',
            'İçecekler',
        ];

        collect($categories)->each(function ($category, $index) {
            MealCategory::query()->create([
                'name' => $category,
                'order' => $index + 1,
                'is_popular' => false,
                'status' => true,
            ]);
        });

    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1,
            'name_uz' => 'Anny Duperey',
            'name_ru' => 'Анни Дюпрей',
            'name_en' => 'Anny Duperey',
            'year' => 2006,
            'breeder' => 'Meilland, Франция',
            'latest' => 'new',
            'color' => 'yellow',
            'petal' => '70',
            'shape' => 'classic',
            'height' => '110',
            'smell' => 'очень сильный',
            'price' => 1,
            'quantity' => 1200,
            'yesorno' => 'yes',
            'about' => 'Цвет, аромат и романтическая форма цветков этого сорта великолепно сочетаются друг c другом. Роза позволяет создавать великолепные бордеры в старинном стиле. Насыщенный цитрусовый аромат',
        ]);

        Product::create([
            'category_id' => 2,
            'name_uz' => 'Anny',
            'name_ru' => 'Анни',
            'name_en' => 'Anny',
            'year' => 2006,
            'breeder' => 'Франция',
            'latest' => 'new',
            'color' => 'yellow',
            'petal' => '70',
            'shape' => 'classic',
            'height' => '110',
            'smell' => 'очень сильный',
            'price' => 2,
            'quantity' => 1350,
            'yesorno' => 'yes',
            'about' => 'Цвет, аромат и романтическая форма цветков этого сорта великолепно сочетаются друг c другом. Роза позволяет создавать великолепные бордеры в старинном стиле. Насыщенный цитрусовый аромат',
        ]);

    }
}

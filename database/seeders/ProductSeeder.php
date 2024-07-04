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
            'slug' => 'anny-duperey',
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
            'yesorno' => '1',
            'about' => 'Цвет, аромат и романтическая форма цветков этого сорта великолепно сочетаются друг c другом. Роза позволяет создавать великолепные бордеры в старинном стиле. Насыщенный цитрусовый аромат',
            'seo_title' => 'Test',
            'seo_tag' => 'Test',
            'seo_description' => 'Test',
        ]);

        Product::create([
            'category_id' => 2,
            'name_uz' => 'Anny',
            'name_ru' => 'Анни',
            'name_en' => 'Anny',
            'slug' => 'anny',
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
            'yesorno' => '1',
            'about' => 'Цвет, аромат и романтическая форма цветков этого сорта великолепно сочетаются друг c другом. Роза позволяет создавать великолепные бордеры в старинном стиле. Насыщенный цитрусовый аромат',
            'seo_title' => 'Test',
            'seo_tag' => 'Test',
            'seo_description' => 'Test',
        ]);

    }
}

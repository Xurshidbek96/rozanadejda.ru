<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name_uz' => 'Shrab',
            'name_ru' => 'Шраб',
            'name_en' => 'Shrub',
        ]) ;

        Category::create([
            'name_uz' => 'Grandiflora uz',
            'name_ru' => 'Grandiflora ru',
            'name_en' => 'Grandiflora',
        ]) ;

        Category::create([
            'name_uz' => 'Climbing uz',
            'name_ru' => 'Climbing ru',
            'name_en' => 'Climbing',
        ]) ;
    }
}

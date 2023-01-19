<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Strategi Entry',
            ],
            [
                'name' => 'Pola Fibonacci',
            ],
            [
                'name' => 'Pola Candlestick',
            ],
            [
                'name' => 'Pola Chart',
            ],
            [
                'name' => 'Indikator',
            ],
        ];
        foreach ($categories as $key => $value) {
            $category = Category::create($value);
        }
    }
}

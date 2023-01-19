<?php

namespace Database\Seeders;

use App\Models\Strategy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StrategyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strategies = [
            [
                'user_id' => '0',
                'category_id' => '0',
                'name' => 'Entry on Breakout',
                'description' => '',
                'url_picture' => '',
            ],
        ];
        foreach ($strategies as $key => $value) {
            $strategies = Strategy::create($value);
        }
    }
}

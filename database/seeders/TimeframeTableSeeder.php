<?php

namespace Database\Seeders;

use App\Models\Timeframe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeframeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeframes = [
            [
                'name' => '1s',
            ],
            [
                'name' => '5s',
            ],
            [
                'name' => '10s',
            ],
            [
                'name' => '15s',
            ],
            [
                'name' => '30s',
            ],
            [
                'name' => '1m',
            ],
            [
                'name' => '3m',
            ],
            [
                'name' => '5m',
            ],
            [
                'name' => '15m',
            ],
            [
                'name' => '30m',
            ],
            [
                'name' => '45m',
            ],
            [
                'name' => '1h',
            ],
            [
                'name' => '2h',
            ],
            [
                'name' => '3h',
            ],
            [
                'name' => '4h',
            ],
            [
                'name' => '1D',
            ],
            [
                'name' => '1W',
            ],
            [
                'name' => '1M',
            ],
            [
                'name' => '3M',
            ],
            [
                'name' => '6M',
            ],
            [
                'name' => '12M',
            ],
        ];
        foreach ($timeframes as $key => $value) {
            $timeframe = Timeframe::create($value);
        }
    }
}

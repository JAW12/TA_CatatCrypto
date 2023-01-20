<?php

namespace Database\Seeders;

use App\Models\Strategy;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StrategyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('sql/strategies.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $strategies = Strategy::all();
        foreach ($strategies as $key => $strategy) {
            $path = "";
            if($strategy->category_id == 1){
                $path = 'images/strategies/entry-strategy/';
            }
            else if($strategy->category_id == 2){
                $path = 'images/strategies/fibonacci/';
            }
            else if($strategy->category_id == 3){
                $path = 'images/strategies/candlestick/';
            }
            else if($strategy->category_id == 4){
                $path = 'images/strategies/chart/';
            }
            else if($strategy->category_id == 5){
                $path = 'images/strategies/indicator/';
            }
            $strategy->url_picture = $path . Str::slug($strategy->name) . '.png';
            $strategy->save();
        }
    }
}

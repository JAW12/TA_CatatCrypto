<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $memberships = [
            [
                'name' => 'basic',
                'price' => 99000,
                'duration_months' => 1,
                'max_wallets' => 1,
                'max_journals' => 1,
                'trades_quantity_per_month' => 100,
                'enable_binance' => 0,
                'enable_notification' => 0,
            ],
            [
                'name' => 'home',
                'price' => 299000,
                'duration_months' => 2,
                'max_wallets' => 3,
                'max_journals' => 3,
                'trades_quantity_per_month' => 500,
                'enable_binance' => 1,
                'enable_notification' => 0,
            ],
            [
                'name' => 'professional',
                'price' => 459000,
                'duration_months' => 4,
                'max_wallets' => 3,
                'max_journals' => 3,
                'trades_quantity_per_month' => 1500,
                'enable_binance' => 1,
                'enable_notification' => 1,
            ],
            [
                'name' => 'business',
                'price' => 1289000,
                'duration_months' => 6,
                'max_wallets' => -1,
                'max_journals' => -1,
                'trades_quantity_per_month' => -1,
                'enable_binance' => 1,
                'enable_notification' => 1,
            ],
        ];

        foreach ($memberships as $value) {
            Membership::create($value);
        }
    }
}

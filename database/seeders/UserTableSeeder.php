<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'admin',
                'status' => 'active',
            ],
            [
                'first_name' => 'Jem',
                'last_name' => 'Angkasa',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'trial',
                'status' => 'active'
            ]
        ];
        foreach ($users as $key => $value) {
            $user = User::create($value);
            $user->assignRole($value['user_type']);
        }
    }
}

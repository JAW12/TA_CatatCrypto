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
        $today = date("Y-m-d");
        $date = date('Y-m-d', strtotime($today. ' + 1 months'));
        $users = [
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'admin',
            ],
            [
                'first_name' => 'Jem',
                'last_name' => 'Angkasa',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'trial',
                'membership_since' => $today,
                'membership_till' => $date,
            ],
            [
                'first_name' => 'Jem',
                'last_name' => 'Angkasa 2',
                'email' => 'user2@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'trial',
                'membership_since' => $today,
                'membership_till' => $date,
            ]
            ,
            [
                'first_name' => 'Jem',
                'last_name' => 'Angkasa 3',
                'email' => 'user3@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'trial',
                'membership_since' => $today,
                'membership_till' => $date,
            ]
        ];
        foreach ($users as $key => $value) {
            $user = User::create($value);

            if($value['user_type'] == "trial"){
                $user->assignRole("user");
            }
            else{
                $user->assignRole($value['user_type']);
            }
        }
    }
}

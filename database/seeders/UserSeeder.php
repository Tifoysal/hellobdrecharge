<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'email' => 'admin@gmail.com',
            'phone_number' => '01700000000',
            'type' => 'admin',
            'balance' => 1000000000,
            'pin'=>'1234'
        ]);

        User::create([
            'username' => 'seller',
            'password' => bcrypt('123456'),
            'email' => 'seller@gmail.com',
            'phone_number' => '01600000000',
            'type' => 'seller',
            'balance' =>0,
            'pin'=>'1122'
        ]);
        User::create([
            'username' => 'user',
            'password' => bcrypt('123456'),
            'email' => 'user@gmail.com',
            'phone_number' => '01500000000',
            'type' => 'user',
            'balance' =>0,
            'pin'=>'1133'
        ]);
    }
}

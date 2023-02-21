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
            'name' => 'علی اکبر رضایی',
            'email' => 'akrez.like@gmail.com',
            'email_verified_at' => '2022-12-12 00:00:00.000000',
            'password' => bcrypt('12345678'),
        ]);
    }
}

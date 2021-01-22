<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = [
            'name' => 'Josias Velazquez',
            'email' => 'jossias.vel@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$Hw6HdNo7XXKa2X7SGX8wXe/9rI8AzSC26p09VwjUdXOSIz/wi/b/a', // password
            'remember_token' => Str::random(10),
            ];
        $product_owner = [
            'name' => 'Claudia Pimentel',
            'email' => 'clps16@hotmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$Hw6HdNo7XXKa2X7SGX8wXe/9rI8AzSC26p09VwjUdXOSIz/wi/b/a', // password
            'remember_token' => Str::random(10),
        ];

        User::create($developer);
        User::create($product_owner);
    }
}

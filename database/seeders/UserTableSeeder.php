<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'first_name' => 'Prince Randy',
            'middle_name' => 'M.',
            'last_name' => 'Gonzales',
            'email' => 'princerandygonzales@example.com',
            'password' => bcrypt('password'),
        ],
        [
            'first_name' => 'John',
            'middle_name' => null,
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}

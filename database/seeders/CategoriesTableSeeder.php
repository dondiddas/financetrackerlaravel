<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categories::insert([
            ['userID' => 1, 'name' => 'Food', 'type' => 'expense'],
            ['userID' => 1, 'name' => 'Transportation', 'type' => 'expense'],
            ['userID' => 1, 'name' => 'Utilities', 'type' => 'expense'],
            ['userID' => 1, 'name' => 'Entertainment', 'type' => 'expense'],
            ['userID' => 1, 'name' => 'Salary', 'type' => 'income'],
            ['userID' => 1, 'name' => 'Freelance', 'type' => 'income'],
        ]);
    }
}

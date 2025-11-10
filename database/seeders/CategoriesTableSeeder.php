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
   public function run()
    {
        $categories = [
            ['user_id' => 1, 'name' => 'Pocket Money', 'type' => 'allowance'],
            ['user_id' => 1, 'name' => 'Salary', 'type' => 'income'],
            ['user_id' => 1, 'name' => 'Groceries', 'type' => 'expense'],
            ['user_id' => 1, 'name' => 'Electricity Bill', 'type' => 'expense'],
        ];

        foreach ($categories as $category) {
            Categories::updateOrCreate(
                ['user_id' => $category['user_id'], 'name' => $category['name']],
                $category
            );
        }
    }
}

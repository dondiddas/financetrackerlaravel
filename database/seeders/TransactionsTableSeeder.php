<?php

namespace Database\Seeders;

use app\Models\Categories;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        $transactions = [
            [
                'user_id' => 1,
                'category_name' => 'Pocket Money',
                'amount' => 100,
                'note' => 'Weekly allowance',
                'transaction_date' => Carbon::today(),
            ],
            [
                'user_id' => 1,
                'category_name' => 'Groceries',
                'amount' => 50,
                'note' => 'Supermarket',
                'transaction_date' => Carbon::today(),
            ],
            [
                'user_id' => 1,
                'category_name' => 'Electricity Bill',
                'amount' => 75,
                'note' => 'Monthly bill',
                'transaction_date' => Carbon::today()->subDays(2),
            ],
            [
                'user_id' => 1,
                'category_name' => 'Salary',
                'amount' => 1500,
                'note' => 'Monthly salary',
                'transaction_date' => Carbon::today()->subMonth(),
            ],
        ];

        foreach ($transactions as $data) {
            $category = Categories::where('user_id', $data['user_id'])
                ->where('name', $data['category_name'])
                ->first();

            if ($category) {
                Transaction::updateOrCreate(
                    [
                        'user_id' => $data['user_id'],
                        'category_id' => $category->id,
                        'transaction_date' => $data['transaction_date'],
                    ],
                    [
                        'amount' => $data['amount'],
                        'note' => $data['note'],
                    ]
                );
            }
        }
    }
}

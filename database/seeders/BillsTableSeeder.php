<?php

namespace Database\Seeders;

use App\Models\Bills;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class BillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bills::insert([
            [
                'user_id' => 1,
                'bill_name' => 'Electric Bill',
                'amount' => 2500.00,
                'due_date' => Carbon::now()->addDays(5),
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'bill_name' => 'Water Bill',
                'amount' => 800.00,
                'due_date' => Carbon::now()->addDays(10),
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'bill_name' => 'Internet Bill',
                'amount' => 1500.00,
                'due_date' => Carbon::now()->addDays(15),
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

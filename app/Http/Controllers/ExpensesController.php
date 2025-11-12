<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Categories;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function getCurrentMonthExpenses($userid) {
        return Transaction::where('user_id',$userid)
        ->whereHas('category', function($query){
                $query->where('type','expense');
        })
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->sum('amount');
    }

    public function getDailyExpenses($userid) {
        $today = now();
        return Transaction::where('user_id', $userid)
        ->whereHas('category',function($query) {
            $query->where('type','expense');
        })
        ->whereYear('transaction_date', $today->year)
        ->whereMonth('transaction_date',$today->month)
        ->whereDay('transaction_date', $today->day)
        ->sum('amount');
    }

public function getDailyExpenseBreakdown($userId)
{
    return Transaction::where('user_id', $userId)
        ->whereHas('category', function ($query) {
            $query->where('type', 'expense');
        })
        ->whereDate('transaction_date', now()->toDateString())
        ->with('category:id,name')
        ->orderBy('amount', 'desc')
        ->get();
}


    public function addDaily(Request $request)
{
    // Validate the input
    $request->validate([
        'category_name' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'note' => 'required|string|max:255',
    ]);

    // Check if category exists; if not, create it
       $category = Categories::firstOrCreate(
        ['name' => $request->category_name, 'user_id' => auth()->id() ?? 1],
        ['type' => 'expense'] 
    );

    // Create the expense
    Transaction::create([
        'category_id' => $category->id,
        'amount' => $request->amount,
        'note' => $request->note,
        'user_id' => auth()->id() ?? 1, // if you have auth
    ]);

    return redirect()->back()->with([
    'expense_success' => 'Expense added successfully!',
    'open_modal' => 'dailyExpensesModal',
]);

}

    public function getDailyChart($userId) {
        $daysinMonth = now()->daysInMonth;
        $labels = [];
        $dailyExpenses = [];

        for ($day = 1; $day <= $daysinMonth; $day++){
            $labels[] = $day;

            $dailyAmount = Transaction::where('user_id', $userId)
            ->whereHas('category', function($query) {
                $query->where('type','expense');
            })
            ->whereYear('transaction_date',now()->year)
            ->whereMonth('transaction_date',now()->month)
            ->whereDay('transaction_date',now()->day)
            ->sum('amount');

            $dailyExpenses[] = $dailyAmount;
        }
    }




}

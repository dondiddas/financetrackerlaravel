<?php

namespace App\Http\Controllers;
use App\Models\Transaction;

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


    public function addDaily(Request $add_expense) {
    $add_expense->validate([
        'categoryID' => 'required|exists:categories,id',
        'amount' => 'required|numeric|min:0.01',
        'note' => 'nullable|string|max:255',
    ]);

    Transaction::create([
        'userID' => auth()->id() ?? 1, // fallback for testing
        'categoryID' => $add_expense->categoryID,
        'amount' => $add_expense->amount,
        'note' => $add_expense->note,
        'transaction_date' => now(),
    ]);

    return redirect()->back()->with([
    'success' => 'Expense added successfully!',
    'open_modal' => 'dailyExpensesModal'
]);

}


}

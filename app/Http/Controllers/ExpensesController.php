<?php

namespace App\Http\Controllers;
use App\Models\Transaction;

use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function getCurrentMonthExpenses($userID) {
        $currentMonthExpenses = Transaction:: join('categories','transactions.category_id','=','categories.id')
        ->where('categories.type','expense')
        ->where('transactions.userID', $userID)
        ->whereMonth('transactions.transaction_date', now()->month)
        ->whereYear('transactions.transaction_date', now()->year)
        ->sum('transactions.amount');
        return $currentMonthExpenses;
    }

    public function getDailyExpenses($userID) {
        $getdailyExpenses = Transaction::join('categories','transactions.category_id','=','categories.id')
        ->where('categories.type','expense')
        ->where('transactions.userID', $userID)
        ->whereDate('transactions.transaction_date', now()->toDateString())
        ->sum('amount');
        return $getdailyExpenses;
    }

    public function getDailyExpenseBreakdown($userID)
{
    return Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
        ->where('categories.type', 'expense')
        ->where('transactions.userID', $userID)
        ->whereDate('transactions.transaction_date', today())
        ->select('categories.name as category_name', 'transactions.amount', 'transactions.note')
        ->orderBy('transactions.amount', 'desc')
        ->get();
}

    public function addDaily(Request $request) {
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'amount' => 'required|numeric|min:0.01',
        'note' => 'nullable|string|max:255',
    ]);

    Transaction::create([
        'userID' => auth()->id() ?? 1, // fallback for testing
        'category_id' => $request->category_id,
        'amount' => $request->amount,
        'note' => $request->note,
        'transaction_date' => now(),
    ]);

    return redirect()->back()->with('success', 'Expense added successfully!');
}

}

<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
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

    public function getTopExpenses($userId) {
        return Transaction::where('user_id', $userId)
        ->whereHas('category', function($squery) {
            $squery->where('type', 'expense');
        })
        ->selectRaw('category_id, SUM(amount) as total_amount')
        ->with('category:id,name')
        ->groupBy('category_id')
        ->orderByDesc('total_amount')
        ->get();
    }

    public function getWeeklyChart($userId) {
        $labels = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $dailyExpenses = [];

        $startOfWeek = now()->startOfWeek(0);
        $endOfWeek = now()->endOfWeek(6);

        foreach($labels as $index => $label) {
            $date = $startOfWeek->copy()->addDays($index);

            $dailyAmount = Transaction::where('user_id', $userId)
            ->whereHas('category', function($query) {
                $query->where('type','expense');
            })
            ->whereDate('transaction_date', $date)
            ->sum('amount');
            $dailyExpenses[] = $dailyAmount;
        }
        return [
            'labels' => $labels,
            'data' =>$dailyExpenses
        ];
    }

public function getMonthlyChart($userId) {
    $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $monthlyExpenses = [];

    foreach ($labels as $index => $label) {
        $monthStart = Carbon::create(null, $index + 1, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $monthlyAmount = Transaction::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type','expense'))
            ->whereBetween('transaction_date', [$monthStart, $monthEnd])
            ->sum('amount');

        $monthlyExpenses[] = $monthlyAmount;
    }

    return [
        'labels' => $labels,
        'data' => $monthlyExpenses
    ];
}



}
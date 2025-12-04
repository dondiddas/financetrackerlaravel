<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    /**
     * Show list of transactions with filters, pagination and ability to add income/expenses.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at');

        if ($request->filled('type') && in_array($request->type, ['income','expense','allowance'])) {
            $type = $request->type;
            $query->whereHas('category', function($q) use ($type) {
                $q->where('type', $type);
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('from')) {
            $query->whereDate('transaction_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('transaction_date', '<=', $request->to);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('note', 'like', "%{$q}%")
                    ->orWhereHas('category', function($cq) use ($q) {
                        $cq->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $perPage = (int) $request->input('per', 10);
        $transactions = $query->paginate($perPage)->withQueryString();

        $categories = Categories::where('user_id', $userId)->orderBy('name')->get();

        return view('transactions.index', compact('transactions','categories'));
    }


    public function addDaily(Request $request)
{
    $request->validate([
        'category_name' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'note' => 'required|string|max:255',
    ]);

       $category = Categories::firstOrCreate(
        ['name' => $request->category_name, 'user_id' => auth()->id()],
        ['type' => 'expense'] 
    );

    // Create the expense
    Transaction::create([
        'category_id' => $category->id,
        'amount' => $request->amount,
        'note' => $request->note,
        'user_id' => auth()->id(),
    ]);

    if ($request->ajax() || $request->wantsJson()) {
        $userId = auth()->id();
        $dailyTotal = $this->getDailyExpenses($userId);

        return response()->json([
            'success' => true,
            'message' => 'Expense added successfully!',
            'expense' => [
                'amount' => $request->amount,
                'note' => $request->note,
                'category_name' => $category->name,
                'transaction_date' => now()->format('F j, Y'),
            ],
            'totals' => [
                'daily' => $dailyTotal,
            ],
        ]);
    }

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

    public function getAllNamesExpenses($userId) {
        return Transaction::where('user_id', $userId)
        ->whereHas('category', function($query){
            $query->where('type', 'expense');
        })
        ->with(['category' => function($query){
            $query->select('id', 'name');
        }])
        ->select('amount','note', 'transaction_date', 'category_id')
        ->orderBy('transaction_date', 'asc')
        ->get()
        ->groupBy(function($item) {
            return $item->category->name;
        });
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

public function getExpensesPercentage($userId) {
    $startofWeek = Carbon::now()->startOfWeek();
    $endofweek = Carbon::now()->endOfWeek();

    $currentWeekExpenses = Transaction::where('user_id', $userId)
    ->whereBetween('created_at',[$startofWeek, $endofweek])
    ->sum('amount');

    $previousWeekExpenses = Transaction::where('user_id', $userId)
    ->whereBetween('created_at', 
    [Carbon::now()->subWeek()->startOfWeek(),
    Carbon::now()->subWeek()->endOfWeek()])
    ->sum('amount');

    $percentageChange = $previousWeekExpenses > 0
    ? (($currentWeekExpenses - $previousWeekExpenses) / $previousWeekExpenses) * 100
    : 0;

    $trend = $percentageChange>=0 ? 'up' : 'down';

    return[
        'current_week' =>$currentWeekExpenses,
        'previous_week' =>$previousWeekExpenses,
        'percentage_change'=>round($percentageChange, 2),
        'trend' =>$trend
    ];

}

public function getBurnRate($userId)
{
    $last7Days = Transaction::where('user_id', $userId)
        ->whereBetween('created_at', [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay()
        ])
        ->sum('amount');

    $dailyBurn = $last7Days / 7;

    $last30Days = Transaction::where('user_id', $userId)
        ->whereBetween('created_at', [
            now()->subDays(29)->startOfDay(),
            now()->endOfDay()
        ])
        ->sum('amount');

    $weeklyBurn = $last30Days / 4.285;

    $monthlyBurn = $last30Days;

    return [
        'daily'   => round($dailyBurn, 2),
        'weekly'  => round($weeklyBurn, 2),
        'monthly' => round($monthlyBurn, 2),
    ];
}




}
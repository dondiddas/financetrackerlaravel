<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bills;
use App\Models\Budget;
use App\Models\Transaction;
use App\Http\Controllers\UpcomingBillsController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\ExpensesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
    protected $allowanceController;
    protected $expenseController;
    protected $upcomingbill;
    protected $dailyLimiting;

    public function __construct(
        AllowanceController $allowanceController, 
        ExpensesController $expensesController,
        UpcomingBillsController $upcomingBillsController,
        DailyLimitController $dailyLimitController
    ) {
        $this->allowanceController = $allowanceController;
        $this->expenseController = $expensesController;
        $this->upcomingbill = $upcomingBillsController;
        $this->dailyLimiting = $dailyLimitController;
    }

    public function dashboard(Request $request)
    {
        $id = auth()->id();

        // --- Get User Name ---
        $user = User::find($id);
        $userName = $user ? $user->first_name : 'there';

        // --- Timezone---
        date_default_timezone_set('Asia/Manila');
        $hour = date('H');

        if ($hour >= 5 && $hour < 12) {
            $greeting = "Good morning";
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = "Good afternoon";
        } else {
            $greeting = "Good evening";
        }

        // --- Allowance + Expenses + Daily Expenses + Upcoming Bills---
        $AllowanceData = $this->allowanceController->getAllowanceOverview($id);
        $LastAllowanceData = $this->allowanceController->getLastMonthAllowance($id);
        $IncomeData = $this->allowanceController->getIncomeOverview($id);
        $LastIncomeData = $this->allowanceController->getLastMonthIncome($id);

        // Combined totals (allowance + income)
        $AllData = $AllowanceData + $IncomeData;
        $LastAllData = $LastAllowanceData + $LastIncomeData;


        $expensesData = $this->expenseController->getCurrentMonthExpenses($id);
        $dailyExpensesData = $this->expenseController->getDailyExpenses($id);
        $dailyExpensesBreakdown = $this->expenseController->getDailyExpenseBreakdown($id);
        $chartDisplay = $this->expenseController->getWeeklyChart($id);
        $monthlyChart = $this->expenseController->getMonthlyChart($id);
        $topExpenses = $this->expenseController->getTopExpenses($id);
        $expensesName = $this->expenseController->getAllNamesExpenses(($id));
        $ExpensesPercentage = $this->expenseController->getExpensesPercentage($id);
        $burnRate = $this->expenseController->getBurnRate($id);


        $DailyLimit = $this->dailyLimiting->getDailyLimit($id);
        $weeklyLimitChart = $this->dailyLimiting->getWeeklyLimits($id);
        $MonthlyLimitChart = $this->dailyLimiting->getMonthlyLimits($id);
        $upcomingbillsData = $this->upcomingbill->getUpcomingBills($id);

        //bills notification
        $dueSoonBills = collect();
        if ($id) {
            $targetDate = now()->addDays(3)->toDateString();
            $dueSoonBills = Bills::where('user_id', $id)
                ->where('is_paid', 0)
                ->whereDate('due_date', '=', $targetDate)
                ->orderBy('due_date')
                ->take(8)
                ->get();
        }
        $dueCount = $dueSoonBills->count();

        $recentTransactions = Transaction::where('user_id', $id)
            ->with(['category' => function($q){ $q->select('id','name','type'); }])
            ->orderByDesc('transaction_date')
            ->take(200)
            ->get()
            ->groupBy(function($t) {
                return \Carbon\Carbon::parse($t->transaction_date)->format('F j, Y');
            });

        // --- Spending Rate ---
        $spendingRate = ($AllowanceData > 0)
            ? ($expensesData / $AllowanceData) * 100
            : 0;

        // --- Cash Balance ---
        $cashBalanceAllowance = $AllowanceData - $expensesData;
        $cashBalanceIncome = $IncomeData - $expensesData;
        $cashBalanceAll = $AllData - $expensesData;

        // Get overview type from request 
        $overviewType = $request->input('overview_type', 'allowance');
        $overviewLabels = [
            'allowance' => 'Allowance Overview',
            'income' => 'Income Overview',
            'all' => 'All Overview'
        ];
        
        // Set overview data based on selected type
        if ($overviewType === 'income') {
            $overviewCurrent = $IncomeData;
            $overviewPrevious = $LastIncomeData;
        } elseif ($overviewType === 'all') {
            $overviewCurrent = $AllData;
            $overviewPrevious = $LastAllData;
        } else {
            $overviewCurrent = $AllowanceData;
            $overviewPrevious = $LastAllowanceData;
            $overviewType = 'allowance'; 
        }

        // Sync cash balance with overview type (unless explicitly set via balance_type param)
        if (!$request->has('balance_type')) {
            // If no explicit balance_type, use the overview_type
            $balanceType = $overviewType;
        } else {
            // If balance_type is explicitly set, use it
            $balanceType = $request->input('balance_type', 'allowance');
        }
        
        $balanceLabels = [
            'allowance' => 'Allowance Balance',
            'income' => 'Income Balance',
            'all' => 'All Balance'
        ];
        
        // Set cash balance based on selected type
        if ($balanceType === 'income') {
            $cashBalance = $cashBalanceIncome;
        } elseif ($balanceType === 'all') {
            $cashBalance = $cashBalanceAll;
        } else {
            $cashBalance = $cashBalanceAllowance;
            $balanceType = 'allowance'; // normalize
        }

        // --- Colors ---
        $rateColor = $spendingRate < 50 ? 'text-success'
                    : ($spendingRate <= 80 ? 'text-warning' : 'text-danger');
        
        // --- Box Color ===
        $shadowColor = $spendingRate > 80
        ? '0 0 10px 2px rgba(214, 9, 30, 0.6)' // red alert
        : 'none';

        // --- High Spending Pulse Animation ---
        $pulseClass = $spendingRate > 90 ? 'pulse-danger' : '';

        $categories = DB::table('categories')->where('type', 'expense')->get();

        // budget carousel 
        $currentMonth = now()->format('Y-m');
        $budgets = Budget::where('budgets.user_id', $id)
            ->leftJoin('categories', 'budgets.category_id', '=', 'categories.id')
            ->leftJoin(
                DB::raw("(SELECT category_id, SUM(amount) as spent 
                          FROM transactions 
                          WHERE user_id = $id 
                          AND DATE_FORMAT(transaction_date, '%Y-%m') = '$currentMonth'
                          GROUP BY category_id) as t"),
                'budgets.category_id',
                '=',
                't.category_id'
            )
            ->select(
                'budgets.*',
                'categories.name as category_name',
                'categories.type as category_type',
                DB::raw('COALESCE(t.spent, 0) as spent'),
                DB::raw('CASE WHEN budgets.amount > 0 THEN ROUND((COALESCE(t.spent, 0) / budgets.amount) * 100) ELSE 0 END as percent')
            )
            ->get();


                    //  dd($AllowanceData, $LastAllowanceData, $expensesData);
//                     dd([
//      'AllowanceData' => $AllowanceData,
//      'LastAllowanceData' => $LastAllowanceData,
//      'expensesData' => $expensesData,
//      'cashBalance' => $cashBalance,
//     'spendingRate' => $spendingRate,
//  ]);
// dd($dailyExpensesBreakdown->toArray());





        return view('dashboard', compact(
            'expensesData',
            'AllowanceData',
            'spendingRate',
            'rateColor',
            'LastAllowanceData',
            'greeting',
            'userName',
            'cashBalance',
            'cashBalanceAllowance',
            'cashBalanceIncome',
            'cashBalanceAll',
            'balanceType',
            'balanceLabels',
            'overviewType',
            'overviewLabels',
            'overviewCurrent',
            'overviewPrevious',
            'dailyExpensesData',
            'upcomingbillsData',
            'shadowColor',
            'pulseClass',
            'dailyExpensesBreakdown',
            'categories',
            'chartDisplay',
            'DailyLimit',
            'weeklyLimitChart',
            'monthlyChart',
            'MonthlyLimitChart',
            'topExpenses',
            'expensesName',
            'ExpensesPercentage',
            'burnRate',
            'IncomeData', 
            'LastIncomeData',
            'AllData',
            'LastAllData',
            'recentTransactions',
            'dueSoonBills',
            'dueCount',
            'budgets',

        ));
    }

    /**
     * Return recent transactions grouped by date as JSON for AJAX polling
     */
    public function recentTransactionsJson(Request $request)
    {
        $id = auth();

        $recent = Transaction::where('user_id', $id)
            ->with(['category' => function($q){ $q->select('id','name','type'); }])
            ->orderByDesc('transaction_date')
            ->take(200)
            ->get()
            ->map(function($t){
                return [
                    'id' => $t->id,
                    'amount' => number_format($t->amount, 2),
                    'note' => $t->note,
                    'category_name' => $t->category->name ?? 'No Category',
                    'category_type' => $t->category->type ?? '',
                    'transaction_date' => \Carbon\Carbon::parse($t->transaction_date)->format('F j, Y'),
                ];
            })
            ->groupBy('transaction_date')
            ->toArray();

        return response()->json(['groups' => $recent]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function __construct(
        AllowanceController $allowanceController, 
        ExpensesController $expensesController,
        UpcomingBillsController $upcomingBillsController
    ) {
        $this->allowanceController = $allowanceController;
        $this->expenseController = $expensesController;
        $this->upcomingbill = $upcomingBillsController;
    }

    public function dashboard()
    {
        $id = 1;

        // --- Get User Name ---
        $user = User::where('id', $id)->first();
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
        $expensesData = $this->expenseController->getCurrentMonthExpenses($id);
        $dailyExpensesData = $this->expenseController->getDailyExpenses($id);
        $upcomingbillsData = $this->upcomingbill->getUpcomingBills($id);
        $dailyExpensesBreakdown = $this->expenseController->getDailyExpenseBreakdown($id);
        $chartDisplay = $this->expenseController->getWeeklyChart($id);


        // --- Spending Rate ---
        $spendingRate = ($AllowanceData > 0)
            ? ($expensesData / $AllowanceData) * 100
            : 0;

        // --- Cash Balance ---
        $cashBalance = $AllowanceData - $expensesData;

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
            'dailyExpensesData',
            'upcomingbillsData',
            'shadowColor',
            'pulseClass',
            'dailyExpensesBreakdown',
            'categories',
            'chartDisplay',
        ));
    }
}

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
        $userID = 1;

        // --- Get User Name ---
        $user = User::where('userID', $userID)->first();
        $userName = $user ? $user->name : 'there';

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
        $AllowanceData = $this->allowanceController->getAllowanceOverview($userID);
        $LastAllowanceData = $this->allowanceController->getLastMonthAllowance($userID);
        $expensesData = $this->expenseController->getCurrentMonthExpenses($userID);
        $dailyExpensesData = $this->expenseController->getDailyExpenses($userID);
        $upcomingbillsData = $this->upcomingbill->getUpcomingBills($userID);
        $dailyExpensesBreakdown = $this->expenseController->getDailyExpenseBreakdown($userID);


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
            'categories'
        ));
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Spending;
use Illuminate\Http\Request;

class SpendingRateController extends Controller
{
    public static function getSpendingRate(int $userID): array
    {
        // -----------------------------
        // Define date ranges
        // -----------------------------
        $currentMonth = date('Y-m');
        $currentStart = date('Y-m-01');
        $currentEnd   = date('Y-m-t');

        // -----------------------------
        // Get total allowance for current month
        // -----------------------------

    $currentAllowance = DB::table('user_allowance')
    ->where('userID', $userID)
    ->whereMonth('month_year', date('m'))
    ->whereYear('month_year', date('Y'))
    ->sum('amount');


        // -----------------------------
        // Get total expenses for current month
        // -----------------------------
    $totalExpenses = DB::table('transactions')
    ->where('userID', $userID)
    ->where('type', 'expense')
    ->whereBetween('date', [$currentStart, $currentEnd])
    ->sum('amount');

        // -----------------------------
        // Calculate spending rate
        // -----------------------------
        $spendingRate = ($currentAllowance > 0)
            ? round(($totalExpenses / $currentAllowance) * 100, 2)
            : 0;

        $spentDisplay = "₱" . number_format($totalExpenses, 2);

        return [
            'currentAllowance' => $currentAllowance,
            'totalExpenses' => $totalExpenses,
            'spendingRate' => $spendingRate,
            'spentDisplay' => $spentDisplay,
        ];
    }
}

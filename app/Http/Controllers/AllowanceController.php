<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{
    public function getAllowanceOverview($userID)
    {
        return Allowance::where('userID', $userID)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }
    public function getLastMonthAllowance($userID)
    {
        return Allowance::where('userID', $userID)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');
    }
}

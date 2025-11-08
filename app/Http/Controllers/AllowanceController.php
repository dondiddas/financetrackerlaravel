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

    public function addAllowances(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        Allowance::create([
            'userID' => auth()->id() ?? 1,
            'amount' => $request->amount,
        ]);

        return redirect()->back()->with([
    'allowance_success' => 'Allowance added successfully!',
    'open_modal' => 'allowanceovermodal', 
]);

    }
}

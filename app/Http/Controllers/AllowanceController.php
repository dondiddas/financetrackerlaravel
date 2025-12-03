<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{
    public function getAllowanceOverview($userid)
    {
        return Transaction::where('user_id', $userid)
            ->whereHas('category', function($query) {
                $query->where('type','allowance');
            })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
    }

    public function getLastMonthAllowance($userid)
    {
        $lastMonth = now()->subMonth();
        return Transaction::where('user_id', $userid)
            ->whereHas('category', function($query) {
                $query->where('type','allowance');
            })
            ->whereMonth('transaction_date', $lastMonth->month)
            ->whereYear('transaction_date', $lastMonth->year)
            ->sum('amount');
    }

    public function getIncomeOverview($userid)
{
    return Transaction::where('user_id', $userid)
        ->whereHas('category', function($query) {
            $query->where('type','income');
        })
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->sum('amount');
}

public function getLastMonthIncome($userid)
{
    $lastMonth = now()->subMonth();
    return Transaction::where('user_id', $userid)
        ->whereHas('category', function($query) {
            $query->where('type','income');
        })
        ->whereMonth('transaction_date', $lastMonth->month)
        ->whereYear('transaction_date', $lastMonth->year)
        ->sum('amount');
}

    public function addAllowances(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'type' => 'required|in:income,allowance', // must be one of the two
        'note' => 'nullable|string|max:255',
    ]);

    // Find or create the corresponding category (income or allowance)
    $category = Categories::firstOrCreate([
        'name' => ucfirst($request->type), 
        'type' => $request->type,
        'user_id' => auth()->id() ?? 1,
    ]);

    Transaction::create([
        'user_id' => auth()->id() ?? 1,
        'category_id' => $category->id,
        'amount' => $request->amount,
        'note' => $request->note ?? "Added {$request->type}",
        'transaction_date' => now(),
    ]);
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => ucfirst($request->type) . ' added successfully!',
            'type' => $request->type,
            'amount' => $request->amount,
            'note' => $request->note ?? "Added {$request->type}",
            'transaction_date' => now()->format('F j, Y'),
        ]);
    }

    return redirect()->back()->with([
        'allowance_success' => ucfirst($request->type) . ' added successfully!',
        'open_modal' => 'allowanceovermodal',
    ]);
}

}

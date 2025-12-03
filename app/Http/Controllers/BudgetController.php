<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use App\Models\Budget;
use App\Models\Transaction;

class BudgetController extends Controller
{
    /**
     * Display budgets and progress per category for the current month.
     */
    public function index(Request $request)
    {
        $userId = auth()->id() ?? 1;

        // Load budgets for user with category using Eloquent
        $budgets = Budget::with('category')
            ->where('user_id', $userId)
            ->get();

        // Sort in PHP by category name (avoids ambiguous SQL joins)
        $budgets = $budgets->sortBy(function($b) {
            return $b->category->name ?? '';
        })->values();

        // For each budget compute spent this month
        $currentMonthStart = now()->startOfMonth()->toDateString();
        $currentMonthEnd = now()->endOfMonth()->toDateString();

        $categoryIds = $budgets->pluck('category_id')->filter()->unique()->values()->all();

        $spent = [];
        if (!empty($categoryIds)) {
            $rows = Transaction::selectRaw('category_id, SUM(amount) as total')
                ->where('user_id', $userId)
                ->whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
                ->whereIn('category_id', $categoryIds)
                ->groupBy('category_id')
                ->get()
                ->keyBy('category_id');

            foreach ($rows as $cid => $r) {
                $spent[$cid] = (float) $r->total;
            }
        }

        // Compute progress percent and remaining
        $budgets = $budgets->map(function($b) use ($spent) {
            $limit = (float) $b->amount;
            $spentAmt = $spent[$b->category_id] ?? 0.0;
            $percent = $limit > 0 ? min(100, ($spentAmt / $limit) * 100) : 0;
            $remaining = $limit - $spentAmt;
            return (object) array_merge((array) $b, [
                'spent' => round($spentAmt,2),
                'percent' => round($percent,2),
                'remaining' => round($remaining,2),
            ]);
        });

        // Monthly total budget summary
        $totalBudget = $budgets->sum('amount');
        $totalSpent = array_sum(array_values($spent));

        return view('budgets.index', compact('budgets','totalBudget','totalSpent'));
    }
}


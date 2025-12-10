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
        $userId = auth()->id();
        $showTrashed = $request->input('show') === 'trash';

        $budgetsQuery = Budget::with('category')
            ->where('user_id', $userId);

        if ($showTrashed) {
            $budgetsQuery->onlyTrashed();
        }

        $budgets = $budgetsQuery->get();

        $budgets = $budgets->sortBy(function($b) {
            return $b->category->name ?? '';
        })->values();

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

        $budgets = $budgets->map(function($b) use ($spent) {
            $limit = (float) $b->amount;
            $spentAmt = $spent[$b->category_id] ?? 0.0;
            $percent = $limit > 0 ? min(100, ($spentAmt / $limit) * 100) : 0;
            $remaining = $limit - $spentAmt;

            return (object) [
                'id' => $b->id,
                'user_id' => $b->user_id ?? null,
                'category_id' => $b->category_id,
                'category_name' => $b->category->name ?? ($b->category_name ?? ''),
                'category_type' => $b->category->type ?? ($b->category_type ?? ''),
                'amount' => (float) $b->amount,
                'note' => $b->note ?? null,
                'spent' => round($spentAmt,2),
                'percent' => round($percent,2),
                'remaining' => round($remaining,2),
            ];
        });

        // Monthly total budget summary
        $totalBudget = $budgets->sum('amount');
        $totalSpent = array_sum(array_values($spent));

        return view('budgets.index', compact('budgets','totalBudget','totalSpent', 'showTrashed'));
    }

    /**
     * Store a new budget (or update if category already has one for user).
     */
    public function store(Request $request)
    {
        $userId = auth()->id();

        $data = $request->validate([
            'category_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $budget = Budget::updateOrCreate(
            ['user_id' => $userId, 'category_id' => $data['category_id']],
            ['amount' => $data['amount'], 'note' => $data['note'] ?? null]
        );

        return redirect()->route('budgets.index')->with('success', 'Budget saved successfully.');
    }

    /**
     * Update an existing budget by id.
     */
    public function update(Request $request, $id)
    {
        $userId = auth()->id() ?? 1;

        $data = $request->validate([
            'category_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $budget = Budget::where('id', $id)->where('user_id', $userId)->first();
        if (!$budget) {
            return redirect()->route('budgets.index')->with('error', 'Budget not found.');
        }

        $budget->amount = $data['amount'];
        $budget->category_id = $data['category_id'];
        $budget->note = $data['note'] ?? null;
        $budget->save();

        return redirect()->route('budgets.index')->with('success', 'Budget updated successfully.');
    }

    /**
     * Delete a budget for the authenticated user.
     */
    public function destroy($id)
    {
        $userId = auth()->id();

        $budget = Budget::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$budget) {
            return redirect()->route('budgets.index')->with('error', 'Budget not found.');
        }

        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget deleted successfully.');
    }

    /**
     * Restore a soft-deleted budget.
     */
    public function restore($id)
    {
        $userId = auth()->id();

        $budget = Budget::onlyTrashed()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$budget) {
            return redirect()->route('budgets.index', ['show' => 'trash'])->with('error', 'Budget not found or not in trash.');
        }

        $budget->restore();

        return redirect()->route('budgets.index')->with('success', 'Budget restored successfully.');
    }
}


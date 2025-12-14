<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsController extends Controller
{
    /**
     * Show reports dashboard: pie (categories), monthly comparison, categories breakdown
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Date range (optional)
        $from = $request->input('from');
        $to = $request->input('to');

        // For category breakdown (default: this month)
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();
        $start = $from ?: $monthStart;
        $end = $to ?: $monthEnd;

        // Category breakdown: sum by category for the selected range
        $categories = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->whereBetween('transaction_date', [$start, $end])
            ->selectRaw('categories.id as category_id, categories.name as category_name, categories.type as category_type, SUM(transactions.amount) as total')
            ->groupBy('categories.id','categories.name','categories.type')
            ->orderByDesc('total')
            ->get();

        // Monthly comparison (last 6 months)
        $months = [];
        $monthlyTotals = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $label = $m->format('M Y');
            $months[] = $label;
            $mStart = $m->copy()->startOfMonth()->toDateString();
            $mEnd = $m->copy()->endOfMonth()->toDateString();

            $total = DB::table('transactions')
                ->where('user_id', $userId)
                ->whereBetween('transaction_date', [$mStart, $mEnd])
                ->sum('amount');

            $monthlyTotals[] = (float) $total;
        }

        // Pie chart: categories totals (expenses only) for selected range
        $expenseCategories = $categories->where('category_type', 'expense')->values();

        return view('reports.index', [
            'categories' => $categories,
            'expenseCategories' => $expenseCategories,
            'months' => $months,
            'monthlyTotals' => $monthlyTotals,
            'start' => $start,
            'end' => $end,
        ]);
    }

    /**
     * Export transactions as CSV for given filters
     */
    public function exportCsv(Request $request)
    {
        $userId = Auth::id();
        $from = $request->input('from');
        $to = $request->input('to');
        $type = $request->input('type');
        $category = $request->input('category');

        $query = DB::table('transactions')
            ->join('categories','transactions.category_id','=','categories.id')
            ->where('transactions.user_id', $userId)
            ->select('transactions.id','transactions.transaction_date','categories.name as category','categories.type as type','transactions.note','transactions.amount');

        if ($from) $query->whereDate('transaction_date', '>=', $from);
        if ($to) $query->whereDate('transaction_date', '<=', $to);
        if ($type) $query->where('categories.type', $type);
        if ($category) $query->where('categories.id', $category);

        $filename = 'transactions_export_'.now()->format('Ymd_His').'.csv';

        $response = new StreamedResponse(function() use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID','Date','Category','Type','Note','Amount']);

            $query->orderBy('transaction_date','desc')->chunk(200, function($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->transaction_date,
                        $row->category,
                        $row->type,
                        $row->note,
                        $row->amount,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');

        return $response;
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Bills;
use Illuminate\Http\Request;

class UpcomingBillsController extends Controller
{
    public function getUpcomingBills($userid)
    {
        $getUpcomingBills = Bills::select('id','bill_name', 'amount', 'due_date','description','is_recurring','recurrence_interval')
            ->where('user_id', $userid)
            ->whereDate('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->get();

        return $getUpcomingBills; 
    }

    public function getBill($id)
{
    
    $bill = Bills::find($id);

    if (!$bill) {
        return response()->json(['error' => 'Bill not found'], 404);
    }

    return response()->json([
        'bill_name' => $bill->bill_name,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date->format('M d, Y'),
        'is_paid' => $bill->is_paid,
    ]);
}

    public function updateDescription(Request $request, $id)
{
    $bill = Bills::findOrFail($id);
    $bill->description = $request->description;
    $bill->save();

    return redirect()->back()->with('success', 'Description updated successfully!');
}

public function store(Request $request)
{
    $request->validate([
        'bill_name' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'due_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'is_recurring' => 'nullable|boolean',
            'recurrence_interval' => 'nullable|string|in:weekly,monthly,yearly',
    ]);

    Bills::create([
        'user_id' => auth()->id() ?? 1,
        'bill_name' => $request->bill_name,
        'amount' => $request->amount,
        'due_date' => $request->due_date,
            'description' => $request->description,
            'is_recurring' => $request->has('is_recurring') ? true : false,
            'recurrence_interval' => $request->recurrence_interval ?: null,
    ]);

    return redirect()->back()->with('success', 'Bill added successfully!');
}

    // Show all bills (upcoming + overdue)
    public function index(Request $request)
    {
        $userId = auth()->id() ?? 1;

        $query = Bills::where('user_id', $userId);

        // Search
        $search = $request->input('q');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('bill_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter: overdue | upcoming | paid | all
        $status = $request->input('status');
        if ($status === 'overdue') {
            $query->where('is_paid', false)->whereDate('due_date', '<', now());
        } elseif ($status === 'upcoming') {
            $query->where('is_paid', false)->whereDate('due_date', '>=', now());
        } elseif ($status === 'paid') {
            $query->where('is_paid', true);
        }

        // Recurring filter
        $recurring = $request->input('recurring');
        if ($recurring === '1') {
            $query->where('is_recurring', true);
        }

        // Sorting
        $sort = $request->input('sort', 'due_date');
        $dir = strtolower($request->input('dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        if (!in_array($sort, ['due_date','amount','bill_name'])) {
            $sort = 'due_date';
        }

        $perPage = (int) $request->input('per', 10);

        $bills = $query->orderBy($sort, $dir)->paginate($perPage)->appends($request->query());

        return view('bills.index', compact('bills', 'search', 'status', 'recurring', 'sort', 'dir', 'perPage'));
    }

    // Mark a bill as paid. If recurring, create the next occurrence.
    public function markPaid(Request $request, $id)
    {
        $bill = Bills::findOrFail($id);
        $bill->is_paid = true;
        $bill->save();

        // If recurring, create the next occurrence based on recurrence_interval
        if ($bill->is_recurring && $bill->recurrence_interval) {
            $nextDue = null;
            try {
                $dt = \Carbon\Carbon::parse($bill->due_date);
                if ($bill->recurrence_interval === 'monthly') {
                    $nextDue = $dt->addMonth()->toDateString();
                } elseif ($bill->recurrence_interval === 'weekly') {
                    $nextDue = $dt->addWeek()->toDateString();
                } elseif ($bill->recurrence_interval === 'yearly') {
                    $nextDue = $dt->addYear()->toDateString();
                }
            } catch (\Exception $e) {
                $nextDue = null;
            }

            if ($nextDue) {
                Bills::create([
                    'user_id' => $bill->user_id,
                    'bill_name' => $bill->bill_name,
                    'amount' => $bill->amount,
                    'due_date' => $nextDue,
                    'description' => $bill->description,
                    'is_recurring' => true,
                    'recurrence_interval' => $bill->recurrence_interval,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Bill marked as paid.');
    }



}


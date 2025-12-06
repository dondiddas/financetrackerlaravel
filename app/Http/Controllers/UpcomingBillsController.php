<?php

namespace App\Http\Controllers;
use App\Models\Bills;
use Illuminate\Http\Request;

class UpcomingBillsController extends Controller
{
    public function getUpcomingBills($userid)
    {
        // Eager load recurrence type and select the fields we need
        $getUpcomingBills = Bills::with('recurrenceType')
            ->select('id','bill_name', 'amount', 'due_date','description','is_recurring','recurrence_type_id')
            ->where('user_id', $userid)
            ->whereDate('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->get();

        return $getUpcomingBills; 
    }

    public function getBill($id)
{
    
    $bill = Bills::with('recurrenceType')->find($id);

    if (!$bill) {
        return response()->json(['error' => 'Bill not found'], 404);
    }

    return response()->json([
        'id' => $bill->id,
        'bill_name' => $bill->bill_name,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date ? $bill->due_date->toDateString() : null,
        'is_paid' => (bool) $bill->is_paid,
        'description' => $bill->description,
        'is_recurring' => (bool) $bill->is_recurring,
        'recurrence_type_id' => $bill->recurrence_type_id,
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
            'recurrence_type_id' => 'nullable|exists:recurrence_types,id',
        ]);

        Bills::create([
            'user_id' => auth()->id(),
            'bill_name' => $request->bill_name,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'is_recurring' => $request->has('is_recurring') ? true : false,
            'recurrence_type_id' => $request->recurrence_type_id ?: null,
        ]);

    return redirect()->back()->with('success', 'Bill added successfully!');
}

    // Show all bills (upcoming + overdue)
    public function index(Request $request)
    {
        $userId = auth()->id();

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

        // If recurring, create the next occurrence based on recurrence type
        if ($bill->is_recurring && $bill->recurrence_type_id) {
            $nextDue = null;
            try {
                $dt = \Carbon\Carbon::parse($bill->due_date);
                $type = $bill->recurrenceType->name ?? null;
                if ($type === 'monthly') {
                    $nextDue = $dt->addMonth()->toDateString();
                } elseif ($type === 'weekly') {
                    $nextDue = $dt->addWeek()->toDateString();
                } elseif ($type === 'yearly') {
                    $nextDue = $dt->addYear()->toDateString();
                } elseif ($type === 'daily') {
                    $nextDue = $dt->addDay()->toDateString();
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
                    'recurrence_type_id' => $bill->recurrence_type_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Bill marked as paid.');
    }

    /**
     * Soft-delete a bill
     */
    public function destroy(Request $request, $id)
    {
        $bill = Bills::find($id);
        if (!$bill) {
            return redirect()->back()->with('error', 'Bill not found.');
        }

        $bill->delete();

        return redirect()->back()->with('success', 'Bill removed. It can be restored from the trash if needed.');
    }

    /**
     * Update an existing bill
     */
    public function update(Request $request, $id)
    {
        $bill = Bills::findOrFail($id);

        $request->validate([
            'bill_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'is_recurring' => 'nullable|boolean',
            'recurrence_type_id' => 'nullable|exists:recurrence_types,id',
        ]);

        $bill->bill_name = $request->bill_name;
        $bill->amount = $request->amount;
        $bill->due_date = $request->due_date;
        $bill->description = $request->description;
        $bill->is_recurring = $request->has('is_recurring') ? true : false;
        $bill->recurrence_type_id = $request->recurrence_type_id ?: null;
        $bill->save();

        return redirect()->back()->with('success', 'Bill updated successfully.');
    }



}


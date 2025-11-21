<?php

namespace App\Http\Controllers;
use App\Models\Bills;
use Illuminate\Http\Request;

class UpcomingBillsController extends Controller
{
    public function getUpcomingBills($userid)
    {
        $getUpcomingBills = Bills::select('id','bill_name', 'amount', 'due_date','description')
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
    ]);

    Bills::create([
        'user_id' => auth()->id() ?? 1,
        'bill_name' => $request->bill_name,
        'amount' => $request->amount,
        'due_date' => $request->due_date,
        'description' => $request->description,
    ]);

    return redirect()->back()->with('success', 'Bill added successfully!');
}



}


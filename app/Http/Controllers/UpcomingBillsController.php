<?php

namespace App\Http\Controllers;
use App\Models\Bills;
use Illuminate\Http\Request;

class UpcomingBillsController extends Controller
{
    public function getUpcomingBills($userID)
    {
        $getUpcomingBills = Bills::select('title', 'amount', 'due_date')
            ->where('userID', $userID)
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
        'title' => $bill->title,
        'amount' => $bill->amount,
        'due_date' => $bill->due_date->format('M d, Y'),
        'is_paid' => $bill->is_paid,
    ]);
}


    public function markPaid(Request $request, $id)
{
    $bill = Bills::find($id);

    if (!$bill) {
        return response()->json(['error' => 'Bill not found'], 404);
    }

    $bill->is_paid = true;
    $bill->save();

    return response()->json(['success' => true]);
}
}


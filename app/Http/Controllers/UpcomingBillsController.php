<?php

namespace App\Http\Controllers;
use App\Models\Bills;
use Illuminate\Http\Request;

class UpcomingBillsController extends Controller
{
    public function getUpcomingBills($userid)
    {
        $getUpcomingBills = Bills::select('bill_name', 'amount', 'due_date')
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

}


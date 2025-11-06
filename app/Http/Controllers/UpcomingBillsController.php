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
}


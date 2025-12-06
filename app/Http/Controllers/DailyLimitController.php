<?php

namespace App\Http\Controllers;
use Carbon\CarbonInterface;
use App\Models\DailyLimit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyLimitController extends Controller
{
    /**
     * Get today's daily limit for the user.
     */
    public function getDailyLimit($userId)
    {
        $today = Carbon::today()->toDateString();
        return DailyLimit::where('user_id', $userId)
            ->whereDate('limit_date', $today)
            ->value('expense_limit') ?? 0;
    }

    /**
     * Add or update the daily limit.
     */
    public function addDailyLimit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $userId = auth()->id();
        $today = now()->setTimezone('Asia/Manila')->toDateString();

        // Update or create a daily limit record
        DailyLimit::updateOrCreate(
            [
                'user_id' => $userId,
                'limit_date' => $today,
            ],
            [
                'expense_limit' => $request->amount,
            ]
        );

        return redirect()->back()->with('success', 'Daily limit saved successfully!');
    }

    /**
     * Get weekly limits for the current week.
     * Returns array of 7 numbers, Sunday to Saturday.
     */
    public function getWeeklyLimits($userId)
{
    $startOfWeek = now()->setTimezone('Asia/Manila')->startOfWeek(CarbonInterface::SUNDAY); 
    $weeklyLimits = [];

    for ($i = 0; $i < 7; $i++) {
        $date = $startOfWeek->copy()->addDays($i)->toDateString();

        $limit = DailyLimit::where('user_id', $userId)
            ->whereDate('limit_date', $date)
            ->value('expense_limit') ?? 0;

        $weeklyLimits[] = $limit;
    }

    return $weeklyLimits;
}
    /**
     * Get monthly limits for the current year.
     * Returns array of 12 numbers, Jan to Dec.
     */
    public function getMonthlyLimits($userId)
    {
        $monthlyLimits = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::create(null, $m, 1)->startOfMonth()->toDateString();
            $monthEnd = Carbon::create(null, $m, 1)->endOfMonth()->toDateString();

            $monthlyLimit = DailyLimit::where('user_id', $userId)
                ->whereBetween('limit_date', [$monthStart, $monthEnd])
                ->sum('expense_limit');

            $monthlyLimits[] = $monthlyLimit;
        }

        return $monthlyLimits;
    }
}

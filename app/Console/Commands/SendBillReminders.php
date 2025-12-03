<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bills;
use App\Mail\BillReminder;
use Illuminate\Support\Facades\Mail;

class SendBillReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for upcoming and overdue bills';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Upcoming within next 3 days
        $upcoming = Bills::where('is_paid', false)
            ->whereDate('due_date', '<=', now()->addDays(3))
            ->get();

        foreach ($upcoming as $bill) {
            // find user email
            $user = $bill->user_id ? \App\Models\User::find($bill->user_id) : null;
            if ($user && $user->email) {
                Mail::to($user->email)->send(new BillReminder($bill));
            }
        }

        return 0;
    }
}

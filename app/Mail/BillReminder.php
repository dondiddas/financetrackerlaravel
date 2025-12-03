<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BillReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $bill;

    /**
     * Create a new message instance.
     */
    public function __construct($bill)
    {
        $this->bill = $bill;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = ($this->bill->due_date < now()) ? 'Overdue bill reminder' : 'Upcoming bill reminder';
        return $this->subject($subject)
                    ->view('emails.bill_reminder')
                    ->with(['bill' => $this->bill]);
    }
}

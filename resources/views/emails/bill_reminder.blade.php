<div>
    <h3>{{ $bill->bill_name }}</h3>
    <p>Amount: ₱{{ number_format($bill->amount, 2) }}</p>
    <p>Due Date: {{ \Carbon\Carbon::parse($bill->due_date)->format('F j, Y') }}</p>
    @if($bill->is_recurring)
        <p>This is a recurring bill ({{ ucfirst($bill->recurrenceType->name ?? 'monthly') }}).</p>
    @endif
    <p>Please login to your account to manage this bill.</p>
</div>

@forelse ($dailyExpenses as $expense)
    <li>
        {{ $expense->category_name }}
        @if (!empty($expense->note))
            — <small class="text-muted">{{ $expense->note }}</small>
        @endif
        <span class="float-end">₱{{ number_format($expense->amount, 2) }}</span>
    </li>
@empty
    <li class="text-muted">No expenses recorded today</li>
@endforelse

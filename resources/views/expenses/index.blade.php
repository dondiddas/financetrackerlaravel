<div>
    @section('content')
<div class="container">
    <h2>This Month’s Total Expenses</h2>
    <div class="card mt-3 p-3">
        <h4>₱{{ number_format($currentMonthExpenses, 2) }}</h4>
    </div>
</div>
@endsection
</div>

@extends('layout.app')
@section('title', 'Dashboard')
@section('content')


    <div class="position-relative ">

        <div class="col">
            <h4 class="fw-semibold mb-0 fs-2 fs-md-1 pe-5">
                {{ $greeting }}, {{ $userName }}!
            </h4>
            <p class="text-muted mb-2 medium">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-lg-3 col-6 mb-3 d-flex flex-column">
                    <!-- Allowance Overview -->
                    <div class="card border mb-3" data-bs-toggle="modal" data-bs-target="#allowanceovermodal"
                        style="cursor: pointer;">
                        <div class="card-body kpi-allowance">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-piggy-bank text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Allowance Overview</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0 fs-2 fs-md-1">
                                    ₱{{ number_format($AllowanceData, 2) }}
                                </h1>
                                <p class="text-secondary mt-1 mb-0 small">
                                    ₱{{ number_format($LastAllowanceData, 2) }} Previous Month
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Cash Balance --}}
                    <div class="card border no-hover">
                        <div class="card-body kpi-highlight">
                            <div class="d-flex gap-2 mb-1 align-items-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Cash Balance</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0 fs-2 fs-md-1">
                                    ₱{{ number_format($cashBalance, 2) }}
                                </h1>
                                <div class="my-4"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Savings --}}
                    <div class="card border no-hover">
                        <div class="card-body kpi-highlight">
                            <div class="d-flex gap-2 mb-1 align-items-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Savings</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0 fs-2 fs-md-1">
                                    ₱{{ number_format($cashBalance, 2) }}
                                </h1>
                                <div class="my-4"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card border shadow-sm">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center mb-1">
                                <i class="fas fa-fire text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Burn Rate</h6>
                            </div>

                            <div class="text-center">
                                <h1 class="fw-semibold mb-1 fs-2 fs-md-1">
                                    ₱{{ number_format($burnRate['daily'], 2) }}
                                </h1>
                                <p class="text-muted small mb-1">per day on average</p>

                                <div class="mt-2">
                                    <span class="badge bg-light text-dark">
                                        Weekly: ₱{{ number_format($burnRate['weekly'], 2) }}
                                    </span>
                                    <span class="badge bg-light text-dark mt-1">
                                        Monthly: ₱{{ number_format($burnRate['monthly'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                {{-- Allowance Overview Modal --}}
                <div class="modal fade" id="allowanceovermodal" tabindex="-1" aria-labelledby="allowanceovermodal"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title fw-semibold">Add Income or Allowance</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                {{-- Success Message --}}
                                @if (session('allowance_success'))
                                    <div id="successAlert" class="alert alert-success py-2 small mb-2">
                                        {{ session('allowance_success') }}
                                    </div>

                                    <script>
                                        setTimeout(() => {
                                            const alert = document.getElementById('successAlert');
                                            if (alert) {
                                                alert.style.transition = "opacity 0.5s";
                                                alert.style.opacity = "0";
                                                setTimeout(() => alert.remove(), 1000);
                                            }
                                        }, 3000);
                                    </script>
                                @endif

                                {{-- Add Allowance / Income Form --}}
                                <form action="{{ route('allowance.addallowance') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label small">Amount</label>
                                        <input type="number" step="0.01" name="amount" class="form-control"
                                            placeholder="Enter amount" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">Type</label>
                                        <select name="type" class="form-select" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="income">Income</option>
                                            <option value="allowance">Allowance</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">Note (optional)</label>
                                        <input type="text" name="note" class="form-control"
                                            placeholder="Enter note (optional)">
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 mt-2">
                                        Add Entry
                                    </button>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>



                <!-- MOney Spent/Daily Expenses -->
                <div class="col-lg-3 col-6 mb-2">
                    <div class="card border {{ $pulseClass }} no-hover" style="box-shadow: {{ $shadowColor }};">
                        <div class="card-body kpi-outflow">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-chart-pie text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Monthly Outflow</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0 fs-2 fs-md-1">
                                    ₱{{ number_format($expensesData, 2) }}
                                </h1>
                                <p class="{{ $rateColor }} text-secondary mt-1 mb-0 small ">
                                    {{ number_format($spendingRate, 1) }}% spent this month
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Expenses -->
                    <div class="card border" data-bs-toggle="modal" data-bs-target="#dailyExpensesModal"
                        style="cursor: pointer;">
                        <div class="card-body kpi-daily">
                            <div class="d-flex gap-2 mb-1">
                                <i class="fas fa-chart-pie text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Daily Expenses</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0 fs-2 fs-md-1">
                                    {{ number_format($dailyExpensesData, 2) }}
                                </h1>
                                <p class="text-secondary mt-1 mb-0 small border" data-bs-toggle="modal"
                                    data-bs-target="#dailyLimitModal" style="cursor: pointer;"
                                    onclick="event.stopPropagation()">
                                    ₱{{ number_format($DailyLimit, 2) }} Limit Expense
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Daily Limit Expense Modal --}}
                    <div class="modal fade" id="dailyLimitModal" tabindex="-1" aria-labelledby="dailyLimitLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-semibold" id="dailyLimitLabel">
                                        Add Daily Expense Limit
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <form action="{{ route('expenses.addDailyLimit') }}" method="POST">
                                        @csrf

                                        <!-- Expense Limit Amount -->
                                        <div class="mb-3">
                                            <label for="amount" class="form-label fw-semibold">Daily Limit (₱)</label>
                                            <input type="number" name="amount" id="amount" class="form-control"
                                                placeholder="Enter limit amount" min="0" step="0.01">
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success fw-semibold">
                                                Save Limit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Daily Expenses-->
                    <div class="modal fade" id="dailyExpensesModal" tabindex="-1" aria-labelledby="dailyExpensesLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-semibold">Daily Expenses Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <!-- Expenses -->
                                        <div class="col-md-6 border-end">
                                            <p class="mb-1 text-secondary">Breakdown of your daily spending:</p>

                                            <!-- Scrollable -->
                                            <div style="max-height: 220px; overflow-y: auto;">
                                                <ul class="list-unstyled mt-2">
                                                    @forelse ($dailyExpensesBreakdown->take(50) as $expense)
                                                        <li class="mb-1">
                                                            {{ $expense->category->name ?? 'No Category' }}
                                                            @if (!empty($expense->note))
                                                                — <small class="text-muted">{{ $expense->note }}</small>
                                                            @endif
                                                            <span
                                                                class="float-end">₱{{ number_format($expense->amount, 2) }}</span>
                                                        </li>
                                                    @empty
                                                        <li class="text-muted">No expenses recorded today</li>
                                                    @endforelse
                                                </ul>
                                            </div>

                                            <div class="mt-3 border-top pt-2 mb-3 d-flex justify-content-between">
                                                <strong>Total Today:</strong>
                                                <strong>₱{{ number_format($dailyExpensesData, 2) }}</strong>
                                            </div>


                                        </div>
                                        <!-- RIGHT: Form -->
                                        <div class="col-md-6 ps-4 border">

                                            @if (session('expense_success'))
                                                <div id="successAlert" class="alert alert-success py-2 small mb-2">
                                                    {{ session('expense_success') }}
                                                </div>

                                                <script>
                                                    setTimeout(() => {
                                                        const alert = document.getElementById('successAlert');
                                                        if (alert) {
                                                            alert.style.transition = "opacity 0.5s";
                                                            alert.style.opacity = "0";
                                                            setTimeout(() => alert.remove(), 1000);
                                                        }
                                                    }, 3000);
                                                </script>
                                            @endif

                                            <h6 class="fw-semibold mb-2">Add New Expense</h6>

                                            <form action="{{ route('expenses.addDaily') }}" method="POST">
                                                @csrf

                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Category</label>
                                                    <!-- User can type a new category or select existing -->
                                                    <input list="categories" name="category_name" class="form-control"
                                                        placeholder="Select or type a category" required>
                                                    <datalist id="categories">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->name }}">
                                                        @endforeach
                                                    </datalist>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Amount</label>
                                                    <input type="number" name="amount" class="form-control"
                                                        step="0.01" min="0" required>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Note</label>
                                                    <input type="text" name="note" class="form-control" required>
                                                </div>

                                                <button type="submit" class="btn btn-success w-100 mt-2">
                                                    Add Expense
                                                </button>
                                            </form>

                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Goals --}}
                    <div class="card border no-hover">
                        <div class="card-body kpi-highlight">
                            <div class="d-flex gap-2 mb-1 align-items-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Goals</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0 fs-2 fs-md-1">
                                    ₱{{ number_format($cashBalance, 2) }}
                                </h1>
                                <div class="my-4"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card border no-hover">
                        <div class="card-body">
                            <!-- Icon + Title -->
                            <div class="d-flex gap-2 mb-1 align-items-center">
                                <span
                                    class="trend-icon {{ $ExpensesPercentage['trend'] == 'up' ? 'text-danger' : 'text-success' }}">
                                    <i class="fa-solid fa-arrow-{{ $ExpensesPercentage['trend'] }}"></i>
                                </span>
                                <h6 class="mb-0 fw-semibold text-dark">Expense Trends</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0 fs-2 fs-md-1">
                                    {{ $ExpensesPercentage['percentage_change'] }}%
                                </h1>
                                <p class="text-muted mb-0 mt-1">
                                    Spending {{ $ExpensesPercentage['trend'] == 'up' ? 'increased' : 'decreased' }}
                                    compared to last week
                                </p>
                            </div>
                        </div>
                    </div>





                </div>

                <script>
                    window.chartData = {
                        week: {
                            labels: @json($chartDisplay['labels']),
                            data: @json($chartDisplay['data'])
                        },
                        month: {
                            labels: @json($monthlyChart['labels']),
                            data: @json($monthlyChart['data'])
                        }
                    };

                    window.limitData = {
                        week: @json($weeklyLimitChart),
                        month: @json($MonthlyLimitChart)
                    };
                </script>

                <div class="col-lg-6 d-flex flex-column">
                    <!-- Chart Card -->
                    <div class="card no-hover">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0 fw-semibold text-dark">
                                <i class="fas fa-chart-line mr-1 mb-0 fw-semibold text-dark"></i> Expenses Breakdown
                            </h3>

                            <!-- Nav items on the right -->
                            <ul class="nav nav-pills ms-auto" id="dateSwitcher">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#" data-period="week">Week</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-period="month">Month</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart"
                                    style="min-height: 300px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            {{-- Upcoming Bills --}}
                            <div class="card border" style="height: 215px;">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex gap-2 align-items-center">
                                            <i class="fas fa-money-bills text-muted"></i>
                                            <h6 class="mb-0 fw-semibold text-dark">Upcoming Bills</h6>
                                        </div>

                                        <!-- Add New Bill Button -->
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addBillModal">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>

                                    <div class="text-center">
                                        <ul class="list-unstyled top-expenses-list text-start">
                                            @forelse ($upcomingbillsData as $bill)
                                                <li class="d-flex justify-content-between align-items-center border-bottom py-2 px-2 clickable"
                                                    data-bs-toggle="modal" data-bs-target="#billModal{{ $bill->id }}"
                                                    style="cursor: pointer;">
                                                    <div class="text-start flex-grow-1">
                                                        <div class="fw-semibold">{{ $bill->bill_name }}</div>
                                                        <small class="text-muted">
                                                            Due:
                                                            {{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}
                                                        </small>
                                                    </div>
                                                    <div class="fw-semibold text-end me-2">
                                                        ₱{{ number_format($bill->amount, 2) }}
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="text-muted py-2">No upcoming bills</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Add Bill Modal -->
                        <div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title fw-semibold" id="addBillModalLabel">Add New Bill</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <form action="{{ route('upcoming-bills.store') }}" method="POST">
                                        @csrf

                                        <div class="modal-body">

                                            <!-- Bill Name -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Bill Name</label>
                                                <input type="text" class="form-control" name="bill_name" required>
                                            </div>

                                            <!-- Amount -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Amount</label>
                                                <input type="number" step="0.01" class="form-control" name="amount"
                                                    required>
                                            </div>

                                            <!-- Due Date -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Due Date</label>
                                                <input type="date" class="form-control" name="due_date" required>
                                            </div>

                                            <!-- Description -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Description (optional)</label>
                                                <textarea class="form-control" name="description" rows="3"></textarea>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Bill</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Bill Modal -->
                        @foreach ($upcomingbillsData as $bill)
                            <div class="modal fade" id="billModal{{ $bill->id }}" tabindex="-1"
                                aria-labelledby="billModalLabel{{ $bill->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-semibold " id="billModalLabel{{ $bill->id }}">
                                                {{ $bill->bill_name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <div class="row mb-3 justify-content-center text-center">
                                                <div class="col">
                                                    <p class="mb-1"><strong>Amount:</strong></p>
                                                    <p>₱{{ number_format($bill->amount, 2) }}</p>
                                                </div>
                                                <div class="col">
                                                    <p class="mb-1"><strong>Due Date:</strong></p>
                                                    <p>{{ \Carbon\Carbon::parse($bill->due_date)->format('F j, Y') }}</p>
                                                </div>
                                            </div>
                                            <!-- Description Form -->
                                            <form action="{{ route('bills.updateDescription', $bill->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="description{{ $bill->id }}"
                                                        class="form-label fw-semibold">Description</label>
                                                    <textarea class="form-control" id="description{{ $bill->id }}" name="description" rows="3"
                                                        placeholder="Add or update description...">{{ $bill->description }}</textarea>
                                                </div>
                                                <div class="d-flex justify-content-end mb-2">
                                                    <button type="submit" class="btn btn-primary fw-semibold">Save
                                                        Description</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <form {{-- action="{{ route('', $bill->id) }}" --}} method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success fw-semibold">
                                                        Mark as Paid
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-lg-6">
                            {{-- Top Expenses --}}
                            <div class="card border" style="height: 215px;">
                                <div class="card-body">
                                    <div class="d-flex gap-2 mb-2 align-items-center">
                                        <i class="fas fa-money-bills text-muted"></i>
                                        <h6 class="mb-0 fw-semibold text-dark">Top Expenses</h6>
                                    </div>

                                    <div class="text-center">
                                        <ul class="list-unstyled top-expenses-list text-start ">
                                            @forelse ($topExpenses as $l_expense)
                                                @php
                                                    $categorySlug = Str::slug($l_expense->category->name ?? 'No Name');
                                                @endphp
                                                <li class="d-flex justify-content-between align-items-center border-bottom py-2 px-2 clickable"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ExpensesModal{{ $categorySlug }}"
                                                    style="cursor: pointer;">
                                                    <div class="text-start flex-grow-1">
                                                        <div class="fw-semibold">
                                                            {{ $l_expense->category->name ?? 'No Name' }}</div>
                                                    </div>
                                                    <div class="fw-semibold text-end me-2">
                                                        ₱{{ number_format($l_expense->total_amount, 2) }}
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="text-muted py-2">No Expenses Yet</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modals per Category --}}
                        @foreach ($expensesName as $categoryName => $transactions)
                            @php
                                $categorySlug = Str::slug($categoryName);
                            @endphp
                            <div class="modal fade" id="ExpensesModal{{ $categorySlug }}" tabindex="-1"
                                aria-labelledby="ExpensesModalLabel{{ $categorySlug }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-semibold"
                                                id="ExpensesModalLabel{{ $categorySlug }}">
                                                {{ $categoryName }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group list-group-flush">
                                                @foreach ($transactions as $expense)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                        <div class="text-muted" style="width: 120px;">
                                                            {{ \Carbon\Carbon::parse($expense->transaction_date)->format('Y-m-d') }}
                                                        </div>
                                                        <div class="flex-grow-1 px-2">
                                                            {{ $expense->note }}
                                                        </div>
                                                        <div class="fw-semibold text-end" style="width: 80px;">
                                                            ₱{{ number_format($expense->amount, 2) }}
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- success notification for daily expenses and allowance Overview --}}
    @if (session('open_modal'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modalId = "{{ session('open_modal') }}";
                var myModal = new bootstrap.Modal(document.getElementById(modalId));
                myModal.show();
            });
        </script>
    @endif

@endsection

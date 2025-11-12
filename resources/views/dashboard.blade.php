@extends('layout.app')
@section('title', 'Dashboard')
@section('content')


    <div class="position-relative ">

        <div class="col">
            <h4 class="fw-semibold mb-0 pe-5">
                {{ $greeting }}, {{ $userName }}!
            </h4>
            <p class="text-muted mb-2 medium">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="mt-2">
            <div class="row">
                <!-- Left Column: Allowance Overview + Spending Rate -->
                <div class="col-lg-3 col-6 mb-3 d-flex flex-column">
                    <!-- Allowance Overview -->
                    <div class="card border mb-3" data-bs-toggle="modal" data-bs-target="#allowanceovermodal"
                        style="cursor: pointer;">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-piggy-bank text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Allowance Overview</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0">
                                    ₱{{ number_format($AllowanceData, 2) }}
                                </h1>
                                <p class="text-secondary mt-1 mb-0 small">
                                    ₱{{ number_format($LastAllowanceData, 2) }} Previous Month
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Cash Balance --}}
                    <div class="card border">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Cash Balance</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0">
                                    ₱{{ number_format($cashBalance, 1) }}
                                </h1>
                                <div class="my-4"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Top Expenses --}}
                    <div class="card border">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Top Expenses</h6>
                            </div>
                            <div class="text-center">
                                <ul class="list-unstyled top-expenses-list text-start">
                                    @forelse ($upcomingbillsData as $bill)
                                        <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                            <div class="text-start">
                                                <div class="fw-semibold">{{ $bill->title }}</div>
                                                <small class="text-muted">
                                                    Due: {{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}
                                                </small>
                                            </div>
                                            <div class="fw-semibold text-end">
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
                    <div class="card border">
                        <div class="card-body text-center">
                            <div class="d-flex gap-2 mb-2 align-items-center justify-content-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Suggest Content</h6>
                            </div>
                            <p>s</p>
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
                    <div class="card border {{ $pulseClass }}" style="box-shadow: {{ $shadowColor }};">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-chart-pie text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Monthly Outflow</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0">
                                    ₱{{ number_format($expensesData, 2) }}
                                </h1>
                                <p class="{{ $rateColor }} text-secondary mt-1 mb-0 small">
                                    {{ number_format($spendingRate, 1) }}% used this month's allowance
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Expenses -->
                    <div class="card border" data-bs-toggle="modal" data-bs-target="#dailyExpensesModal"
                        style="cursor: pointer;">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2">
                                <i class="fas fa-chart-pie text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Daily Expenses</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold mb-0">
                                    {{ number_format($dailyExpensesData, 2) }}
                                </h1>
                                <p class="text-secondary mt-1 mb-0 small">
                                    ₱{{ number_format($expensesData, 2) }} used
                                </p>
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



                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Upcoming Bills</h6>
                            </div>
                            <div class="text-center">
                                <ul class="list-unstyled top-expenses-list text-start">
                                    @forelse ($upcomingbillsData as $bill)
                                        <li class="d-flex justify-content-between align-items-center border-bottom py-2 top-expense-item"
                                            data-bill-id="{{ $bill->id }}">
                                            <div class="text-start">
                                                <div class="fw-semibold">{{ $bill->bill_name }}</div>
                                                <small class="text-muted">
                                                    Due: {{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}
                                                </small>
                                            </div>
                                            <div class="fw-semibold text-end">
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


                    <div class="card border">
                        <div class="card-body text-center">
                            <div class="d-flex gap-2 mb-2 align-items-center justify-content-center">
                                <i class="fas fa-money-bills text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Motivation</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Column: Donut Chart -->
                <div class="col-lg-6 d-flex flex-column">
                    <!-- Chart Card -->
                    <div class="card flex-fill">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i> Expenses Breakdown
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex gap-2 mb-2 align-items-center">
                                        <i class="fas fa-lightbulb text-muted"></i>
                                        <h6 class="mb-0 fw-semibold text-dark">Allowance vs Expenses per Month</h6>
                                    </div>
                                    <h1>Mini Chart</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex gap-2 mb-2 align-items-center">
                                        <i class="fas fa-lightbulb text-muted"></i>
                                        <h6 class="mb-0 fw-semibold text-dark">Spending Insights</h6>
                                    </div>
                                    <p class="text-muted small">
                                        Get a quick analysis of your spending habits or other financial data here.
                                    </p>

                                    <ul class="mb-0">
                                        <li>Average daily spending: ₱{{ number_format($avgDailySpend ?? 0, 2) }}</li>
                                        <li>Most frequent expense category: {{ $topCategory ?? 'N/A' }}</li>
                                        <li>Predicted savings for next month:
                                            ₱{{ number_format($predictedSavings ?? 0, 2) }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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

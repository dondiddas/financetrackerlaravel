<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hover + Toggle Sidebar </title>

    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
</head>

<body class="bg-light">

    @include('partials.sidebar')

    <!-- Page Content -->
    {{-- <div id="page-content-wrapper">
    <div class="row">
<div class="col-lg-3 col-6">
  <div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-body text-center py-4">
      <!-- Header -->
      <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
        <i class="fas fa-piggy-bank text-secondary"></i>
        <h6 class="mb-0 fw-semibold text-dark">Budget Health</h6>
      </div>

      <!-- Date -->
      <p class="text-muted mb-2 small">{{ now()->format('F Y') }}</p>

      <!-- Main Value -->
      <h1 class="fw-bold mb-0" style="font-size: 2.25rem;">
        {{ number_format($AllowanceData, 2) }}
      </h1>

      <!-- Subtext -->
      <p class="text-secondary mt-1 mb-0 small">
        ₱{{ number_format($LastAllowanceData, 2) }}
      </p>
    </div>
  </div>
</div>

            <!-- ./col -->
            <div class="col-lg-3 col-6">
  <div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-body text-center py-4">
      <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
        <i class="fas fa-money-bill-1 text-secondary"></i>
        <h6 class="mb-0 fw-semibold text-dark">Allowance Overview</h6>
      </div>

      <p class="text-muted mb-2 small">{{ now()->format('F Y') }}</p>

      <h1 class="fw-bold mb-0" style="font-size:2.25rem;">
        {{ number_format($AllowanceData, 2) }}
      </h1>

      <p class="text-secondary mt-1 mb-0 small">
        ₱{{ number_format($LastAllowanceData, 2) }}
      </p>
    </div>
  </div>
</div>

            <!-- ./col -->
            <div class="col-lg-3 col-6">
  <div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-body text-center py-4">
      <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
        <i class="fas fa-chart-pie text-muted"></i>
        <h6 class="mb-0 fw-semibold text-dark">Spending Rate</h6>
      </div>

      <p class="text-muted mb-2 small">{{ now()->format('F Y') }}</p>

      <h1 class="{{ $rateColor }} fw-bold mb-0" style="font-size:2.25rem;">
        {{ number_format($spendingRate, 2) }}%
      </h1>
      <p class="text-secondary mt-1 mb-0 small">
        ₱{{ number_format($expensesData, 2) }} used
      </p>
    </div>
  </div>
</div>

            <!-- ./col -->
            <div class="col-lg-3 col-6">
  <div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-body text-center py-4">
      <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
        <i class="fas fa-bars-progress text-secondary"></i>
        <h6 class="mb-0 fw-semibold text-dark">Goal Progress</h6>
      </div>

      <p class="text-muted mb-2 small">{{ now()->format('F Y') }}</p>

      <h1 class="fw-bold mb-0" style="font-size:2.25rem;">
        {{ number_format($AllowanceData, 2) }}
      </h1>

      <p class="text-secondary mt-1 mb-0 small">
        ₱{{ number_format($LastAllowanceData, 2) }}
      </p>
    </div>
  </div>
</div>

                <div class="container-fluid mt-4">
  <div class="row g-4 justify-content-center">

    <!-- Chart Card -->
    <div class="col-12 col-md-8">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
              <i class="fas fa-chart-line text-secondary"></i>
              <h6 class="fw-semibold mb-0 text-dark">Expenses Overview</h6>
            </div>

            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link active px-3 py-1" href="#revenue-chart" data-toggle="tab">Area</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 py-1" href="#sales-chart" data-toggle="tab">Donut</a>
              </li>
            </ul>
          </div>

          <div class="tab-content p-0">
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
              <canvas id="revenue-chart-canvas" height="300"></canvas>
            </div>
            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
              <canvas id="sales-chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Expenses Card -->
    <div class="col-12 col-md-4">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-chart-pie text-secondary"></i>
            <h6 class="fw-semibold mb-0 text-dark">Mini Chart</h6>
          </div>

          <div style="height: 300px;">
            <canvas id="mini-chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

    </div>

<div class="row g-4 mt-2">
  <!-- Upcoming Bills -->
  <div class="col-lg-4 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 h-100">
      <div class="card-body text-center py-4">
        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
          <i class="fas fa-file-invoice text-secondary"></i>
          <h6 class="mb-0 fw-semibold text-dark">Upcoming Bills</h6>
        </div>
        <p class="text-muted mb-2 small">{{ now()->format('F Y') }}</p>
        <h1 class="fw-bold mb-0" style="font-size: 2.25rem;">
          {{ number_format($AllowanceData, 2) }}
        </h1>
        <p class="text-secondary mt-1 mb-0 small">
          ₱{{ number_format($LastAllowanceData, 2) }}
        </p>
      </div>
    </div>
  </div>

  <!-- Recent Transactions -->
  <div class="col-lg-4 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 h-100">
      <div class="card-body text-center py-4">
        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
          <i class="fas fa-receipt text-secondary"></i>
          <h6 class="mb-0 fw-semibold text-dark">Recent Transactions</h6>
        </div>
        <p class="text-muted mb-2 small">{{ now()->format('F Y') }}</p>
        <h1 class="fw-bold mb-0" style="font-size: 2.25rem;">
          {{ number_format($AllowanceData, 2) }}
        </h1>
        <p class="text-secondary mt-1 mb-0 small">
          ₱{{ number_format($LastAllowanceData, 2) }}
        </p>
      </div>
    </div>
  </div>

  <!-- Top Expenses -->
  <div class="col-lg-4 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 h-100">
      <div class="card-body text-center py-4">
        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
          <i class="fas fa-chart-pie text-secondary"></i>
          <h6 class="mb-0 fw-semibold text-dark">Top Expenses</h6>
        </div>
        <p class="text-muted mb-2 small">{{ now()->format('F Y') }}</p>
        <h1 class="fw-bold mb-0" style="font-size: 2.25rem;">
          {{ number_format($AllowanceData, 2) }}
        </h1>
        <p class="text-secondary mt-1 mb-0 small">
          ₱{{ number_format($LastAllowanceData, 2) }}
        </p>
      </div>
    </div>
  </div>
</div>
</div> --}}
    <div id="page-content-wrapper" class="position-relative mt-3">
        <div class="text center align-items-center">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
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
                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-piggy-bank text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Allowance Overview</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-bold mb-0">
                                    ₱{{ number_format($AllowanceData, 1) }}
                                </h1>
                                <p class="text-secondary mt-1 mb-0 small">
                                    ₱{{ number_format($LastAllowanceData, 1) }} Previous Month
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
                                <h1 class="fw-bold mb-0">
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
                                        <li
                                            class="d-flex justify-content-between align-items-center border-bottom py-2">
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


                <!-- MOney Spent/Daily Expenses -->
                <div class="col-lg-3 col-6 mb-2">
                    <div class="card border {{ $pulseClass }}" style="box-shadow: {{ $shadowColor }};">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-center">
                                <i class="fas fa-chart-pie text-muted"></i>
                                <h6 class="mb-0 fw-semibold text-dark">Money Spent</h6>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-bold mb-0">
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
                                <h1 class="{{ $rateColor }} fw-bold mb-0">
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
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-semibold" id="dailyExpensesLabel">Daily Expenses Details
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-1 text-secondary">Breakdown of your daily spending:</p>
                                    <ul class="list-unstyled mt-2">
                                        @forelse ($dailyExpensesBreakdown as $expense)
                                            <li>
                                                {{ $expense->category_name }}
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

                                    <div class="mt-3 border-top pt-2">
                                        <strong>Total Today:</strong> ₱{{ number_format($dailyExpensesData, 2) }}
                                    </div>
                                    {{-- Add New Daily Expense --}}
                                    <form action="{{ route('expenses.addDaily') }}" method="POST" class="mt-4">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="category" class="form-label small text-muted">Category</label>
                                            <select name="category_id" id="category" class="form-select" required>
                                                <option value="">Select a category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-2">
                                            <label for="amount" class="form-label small text-muted">Amount</label>
                                            <input type="number" name="amount" id="amount" class="form-control"
                                                step="0.01" min="0" required>
                                        </div>

                                        <div class="mb-2">
                                            <label for="note" class="form-label small text-muted">Note</label>
                                            <input type="text" name="note" id="note" class="form-control"
                                                required>
                                        </div>

                                        <button type="submit" class="btn btn-success w-100">Add Expense</button>
                                    </form>

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
                                        <li
                                            class="d-flex justify-content-between align-items-center border-bottom py-2">
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
                            <div class="tab-content p-0">
                                <div class="chart tab-pane active" id="revenue-chart"
                                    style="position: relative; height: 300px;">
                                    <canvas id="revenue-chart-canvas" height="300"></canvas>
                                </div>
                                <div class="chart tab-pane" id="sales-chart"
                                    style="position: relative; height: 300px;">
                                    <canvas id="sales-chart-canvas" height="300"></canvas>
                                </div>
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


    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>

@extends('layout.app')

@section('content')

<style>
    
/* --- Mobile Friendly Table (same as Bills) --- */
@media (max-width: 768px) {
    #transactionsTable thead {
        display: none;
    }
    #transactionsTable tbody tr {
        display: block;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        background: #fff;
    }
    #transactionsTable tbody tr td {
        display: flex;
        justify-content: space-between;
        padding: 6px 8px;
        border: none !important;
    }
    #transactionsTable tbody tr td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #555;
    }
    #transactionsPagination {
        justify-content: center !important;
    }
    
}
</style>

<div class="container py-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Transactions</h3>
        <div class="d-flex gap-2">
            @if($showTrashed)
                @php
                    $params = request()->query();
                    unset($params['show']);
                @endphp
                <a href="{{ route('transactions.index', $params) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Active
                </a>
            @else
                <a href="{{ route('transactions.index', array_merge(request()->query(), ['show' => 'trash'])) }}" class="btn btn-sm btn-outline-warning">
                    <i class="fa-solid fa-trash"></i> View Trash
                </a>
            @endif
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addIncomeModal">Add Income</button>
            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">Add Expense</button>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-3">
      <div class="card-body">
        <form id="transactionsFilters" class="row gy-2 gx-2 align-items-end" method="GET" action="{{ route('transactions.index') }}">

                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        <option value="income" {{ request('type')=='income' ? 'selected':'' }}>Income</option>
                        <option value="expense" {{ request('type')=='expense' ? 'selected':'' }}>Expense</option>
                        <option value="allowance" {{ request('type')=='allowance' ? 'selected':'' }}>Allowance</option>
                    </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Search</label>
                  <input type="text" name="q" class="form-control" placeholder="Search note or category" value="{{ request('q') }}">
                </div>

                <div class="col-md-2 d-flex gap-2">
                  <button type="submit" class="btn btn-primary w-100">Apply</button>
                  <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div id="transactionsSummary" class="mb-2">Showing {{ $transactions->total() }} transactions</div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="table-responsive">
            <table id="transactionsTable" class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td data-label="Date">{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') }}</td>
                            <td data-label="Category">{{ $t->category->name ?? '—' }}</td>
                            <td data-label="Type">{{ $t->category->type ?? '—' }}</td>
                            <td data-label="Note">{{ $t->note }}</td>
                            <td data-label="Amount" class="text-end">₱{{ number_format($t->amount,2) }}</td>
                            <td data-label="Actions" class="text-end">
                              @if($showTrashed)
                                <form action="{{ route('transactions.restore', $t->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  <button class="btn btn-sm btn-outline-success" title="Restore">
                                    <i class="fa-solid fa-undo"></i>
                                  </button>
                                </form>
                              @else
                                <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this transaction?');">
                                  @csrf
                                  @method('DELETE')
                                  <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                  </button>
                                </form>
                              @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-3 text-muted">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

            <div>
              <form method="GET" action="{{ route('transactions.index') }}" class="d-flex align-items-center gap-2">
                @foreach(request()->except('per') as $key => $value)
                  <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <small>Per page:</small>
                <select name="per" class="form-select form-select-sm d-inline-block" style="width:auto" onchange="this.form.submit()">
                  <option value="10" {{ request('per',10)==10 ? 'selected':'' }}>10</option>
                  <option value="25" {{ request('per')==25 ? 'selected':'' }}>25</option>
                  <option value="50" {{ request('per')==50 ? 'selected':'' }}>50</option>
                </select>
              </form>
            </div>
        </div>
    </div>

</div>

<!-- Add Income Modal -->
<div class="modal fade" id="addIncomeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addIncomeForm" action="{{ route('allowance.addallowance') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Income</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Amount</label>
            <input name="amount" type="number" step="0.01" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Note</label>
            <input name="note" type="text" class="form-control">
          </div>
          <input type="hidden" name="type" value="income">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Income</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addExpenseForm" action="{{ route('expenses.addDaily') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Expense</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Category</label>
            <input name="category_name" type="text" class="form-control" placeholder="e.g. Groceries" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Amount</label>
            <input name="amount" type="number" step="0.01" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Note</label>
            <input name="note" type="text" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Expense</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- JS disabled: transactions-filters.js removed --}}

@endsection

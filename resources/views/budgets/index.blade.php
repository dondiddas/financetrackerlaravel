@extends('layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Budgets</h3>
        <small class="text-muted">Monthly budgets and progress</small>
    </div>

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center flex-column flex-md-row gap-2">
            <div>
                <strong>Total budget:</strong> {{ number_format($totalBudget,2) }}
            </div>
            <div>
                <strong>Spent this month:</strong> {{ number_format($totalSpent,2) }}
            </div>
            <div>
                <strong>Remaining:</strong> {{ number_format($totalBudget - $totalSpent,2) }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($budgets as $b)
                <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 py-3">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong class="d-block">{{ $b->category_name }}</strong>
                                <small class="text-muted">{{ ucfirst($b->category_type) }} • {{ $b->note ?? '' }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ number_format($b->amount,2) }}</div>
                                <small class="text-muted">Limit</small>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="progress" style="height:10px">
                                <div class="progress-bar" role="progressbar" style="width: {{ $b->percent }}%;" aria-valuenow="{{ $b->percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between small text-muted">
                            <div>Spent: {{ number_format($b->spent,2) }}</div>
                            <div>Remaining: {{ number_format($b->remaining,2) }}</div>
                            <div>{{ $b->percent }}%</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="list-group-item text-center py-4">No budgets set. Add budgets per category to get started.</div>
                @endforelse
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <a href="#" class="btn btn-sm btn-primary" id="addBudgetBtn">Add Budget</a>
        </div>
    </div>
</div>

<!-- Small Add Budget Modal -->
<div class="modal fade" id="addBudgetModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <form id="addBudgetForm" action="{{ route('upcoming-bills.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Budget</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Category</label>
            <select id="budgetCategory" name="category_id" class="form-select" required>
                @foreach(\App\Models\Categories::where('user_id', auth()->id() ?? 1)->orderBy('name')->get() as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
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
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="{{ asset('js/budgets.js') }}"></script>

@endsection

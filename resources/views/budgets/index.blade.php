@extends('layout.app')

@section('content')
    <div class="container my-3">
        <header class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-3">

            <div>
                <h3 class="mb-0">Budgets</h3>
                <small class="text-muted">Monthly budgets and progress</small>
            </div>

            <div class="d-flex gap-2 align-items-center">
                @if($showTrashed)
                    @php
                        $params = request()->query();
                        unset($params['show']);
                    @endphp
                    <a href="{{ route('budgets.index', $params) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left"></i> 
                    </a>
                @else
                    <a href="{{ route('budgets.index', array_merge(request()->query(), ['show' => 'trash'])) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fa-solid fa-trash"></i> 
                    </a>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBudgetModal"><i class="fa-solid fa-plus"></i></button>
                @endif
            </div>
        </header>

        {{-- Budget-specific alerts (over/near limit) --}}
        <section id="budgetAlerts" class="mb-3">
            @php
                $near = [];
                $over = [];
                foreach ($budgets as $b) {
                    $percent = isset($b->percent)
                        ? (float) $b->percent
                        : (isset($b->amount) && $b->amount > 0
                            ? round((($b->spent ?? 0) / $b->amount) * 100)
                            : 0);
                    if ($percent >= 100) {
                        $over[] = $b;
                    } elseif ($percent >= 80) {
                        $near[] = $b;
                    }
                }
            @endphp

                @if (count($over) > 0)
                <div class="modal fade" id="overLimitModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-body text-center py-4">
                                <div class="text-danger mb-3">
                                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 3rem;"></i>
                                </div>
                                <h5>{{ count($over) }} category(ies) over limit</h5>
                                <p class="mb-0 text-muted">You exceeded the budget for {{ implode(', ', array_map(fn($x) => $x->category_name, $over)) }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = new bootstrap.Modal(document.getElementById('overLimitModal'));
                        modal.show();
                        setTimeout(() => modal.hide(), 3000);
                    });
                </script>
            @endif

                @if (count($near) > 0)
                <div class="modal fade" id="nearLimitModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-body text-center py-4">
                                <div class="text-warning mb-3">
                                    <i class="fa-solid fa-circle-exclamation" style="font-size: 3rem;"></i>
                                </div>
                                <h5>{{ count($near) }} category(ies) near limit</h5>
                                <p class="mb-0 text-muted">Approaching limit for {{ implode(', ', array_map(fn($x) => $x->category_name, $near)) }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = new bootstrap.Modal(document.getElementById('nearLimitModal'));
                        modal.show();
                        setTimeout(() => modal.hide(), 3000);
                    });
                </script>
            @endif
        </section>

{{-- cards --}}
<section class="mb-3">
    <div class="row g-3">

        <!-- Savings -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-money-bills text-muted"></i>
                        <h6 class="mb-0 fw-semibold text-dark">Savings</h6>
                    </div>

                    <h1 class="fw-semibold fs-2 mb-0">
                        ₱{{ number_format($totalBudget ?? 0, 2) }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Spent This Month -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-money-bills text-muted"></i>
                        <h6 class="mb-0 fw-semibold text-dark">Spent This Month</h6>
                    </div>

                    <h1 class="fw-semibold fs-2 mb-0">
                        ₱{{ number_format($totalSpent ?? 0, 2) }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Remaining -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-md-end">
                    <div class="small text-muted">Remaining</div>

                    <div class="h4 fw-bold" id="totalRemaining">
                        {{ number_format(($totalBudget ?? 0) - ($totalSpent ?? 0), 2) }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>




        <!-- Budgets list -->
        <section class="card shadow-sm">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($budgets as $b)
                        @php
                            $spent = $b->spent ?? 0;
                            $amount = $b->amount ?? 0;
                            $percent = $amount > 0 ? round(($spent / $amount) * 100) : 0;
                        @endphp
                        <div class="list-group-item budget-item d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 py-3"
                            data-id="{{ $b->id }}" data-amount="{{ $amount }}"
                            data-spent="{{ $spent }}" data-percent="{{ $percent }}">
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="d-block">{{ $b->category_name }}</strong>
                                        <small class="text-muted">{{ ucfirst($b->category_type) }} •
                                            {{ $b->note ?? '' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">{{ number_format($amount, 2) }}</div>
                                        <small class="text-muted">Limit</small>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="progress" style="height:12px">
                                        <div class="progress-bar progress-fill" role="progressbar" style="width:{{ $percent }}%"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-between small text-muted gap-2">
                                    <div>Spent: <span class="spent">{{ number_format($spent, 2) }}</span></div>
                                    <div>Remaining: <span
                                            class="remaining">{{ number_format(max($amount - $spent, 0), 2) }}</span></div>
                                    <div class="percent fw-semibold text-dark">{{ $percent }}%</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 align-items-start mt-3 mt-md-0">
                                @if(!$showTrashed)
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" 
                                        data-bs-target="#editBudgetModal{{ $b->id }}" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <form action="{{ route('budgets.destroy', $b->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this budget?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('budgets.restore', $b->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success" title="Restore">
                                            <i class="fa-solid fa-undo"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-4">No budgets set. Add budgets per category to get
                            started.</div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <small class="text-muted">Click the edit icon to modify a budget.</small>
            </div>
        </section>

        <!-- Edit Budget Modals (one per budget) -->
        @foreach($budgets as $b)
            @php
                $spent = $b->spent ?? 0;
                $amount = $b->amount ?? 0;
            @endphp
            <div class="modal fade" id="editBudgetModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <form action="{{ route('budgets.update', $b->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">Edit Budget</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" required>
                                        @foreach (\App\Models\Categories::where('user_id', auth()->id() ?? 1)->orderBy('name')->get() as $c)
                                            <option value="{{ $c->id }}" {{ ($b->category_id ?? null) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input name="amount" type="number" step="0.01" value="{{ $amount }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <input name="note" type="text" value="{{ $b->note ?? '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Add Budget Modal -->
        <div class="modal fade" id="addBudgetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <form action="{{ route('budgets.store') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Add Budget</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach (\App\Models\Categories::where('user_id', auth()->id() ?? 1)->orderBy('name')->get() as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input name="amount" type="number" step="0.01" class="form-control" required>
                            </div>
                            <div class="mb-3">
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

        <style>
            .shadow-sm {
                box-shadow: 0 4px 10px rgba(19, 24, 28, 0.06);
            }

            .progress-fill {
                transition: width 900ms cubic-bezier(.2, .8, .2, 1);
            }

            .list-group-item {
                transition: transform .15s ease, box-shadow .15s ease;
                
            }
            .alert i {
    font-size: 1.2rem;
    margin-top: 2px;
}

            .budget-item {
        flex-wrap: wrap;
    }
    .budget-item > div:last-child {
        white-space: nowrap;
    }

            .list-group-item:active {
                transform: scale(.998);
            }

            @media (max-width: 767px) {
                header .form-select {
                    min-width: 160px;
                }
            }
        </style>

    </div>

    {{-- JS disabled: budgets.js removed --}}
@endsection

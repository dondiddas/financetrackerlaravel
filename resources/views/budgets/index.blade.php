@extends('layout.app')

@section('content')
    <div class="container my-3">
        <header class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
            <div>
                <h3 class="mb-0">Budgets</h3>
                <small class="text-muted">Monthly budgets and progress</small>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <label for="monthFilter" class="mb-0 text-muted">Month</label>
                <select id="monthFilter" class="form-select form-select-sm">
                    @php
                        $now = \Carbon\Carbon::now();
                    @endphp
                    @for ($i = 0; $i < 12; $i++)
                        @php $m = $now->copy()->subMonths($i); @endphp
                        <option value="{{ $m->format('Y-m') }}"
                            {{ request('month') == $m->format('Y-m') ? 'selected' : ($i == 0 && !request('month') ? 'selected' : '') }}>
                            {{ $m->format('F Y') }}</option>
                    @endfor
                </select>
                <button class="btn btn-sm btn-primary" id="addBudgetBtn"><i class="fa-solid fa-plus"></i> Add</button>
            </div>
        </header>
        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- alerts --}}
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
                <div class="alert alert-danger shadow-sm d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>{{ count($over) }} category(ies) over limit</strong>
                        <div class="small">You exceeded the budget for
                            {{ implode(', ', array_map(fn($x) => $x->category_name, $over)) }}.</div>
                    </div>
                </div>
            @endif

                @if (count($near) > 0)
                <div class="alert alert-warning shadow-sm d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>{{ count($near) }} category(ies) near limit</strong>
                        <div class="small">Approaching limit for
                            {{ implode(', ', array_map(fn($x) => $x->category_name, $near)) }}.</div>
                    </div>
                </div>
            @endif
        </section>

        {{-- cards --}}
        <section class="mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">

                <!-- Savings -->
                <div class="card border-0 shadow-sm flex-fill">
                    <div class="card-body text-center text-md-start">
                        <div class="d-flex gap-2 mb-1 align-items-center justify-content-center justify-content-md-start">
                            <i class="fas fa-money-bills text-muted"></i>
                            <h6 class="mb-0 fw-semibold text-dark">Savings</h6>
                        </div>

                        <h1 class="fw-semibold mb-0 fs-2">
                            ₱{{ number_format($totalBudget ?? 0, 2) }}
                        </h1>
                    </div>
                </div>

                <!-- Spent This Month -->
                <div class="card border-0 shadow-sm flex-fill">
                    <div class="card-body text-center text-md-start">
                        <div class="d-flex gap-2 mb-1 align-items-center justify-content-center justify-content-md-start">
                            <i class="fas fa-money-bills text-muted"></i>
                            <h6 class="mb-0 fw-semibold text-dark">Spent This Month</h6>
                        </div>
                        <h1 class="fw-semibold mb-0 fs-2">
                            ₱{{ number_format($totalSpent ?? 0, 2) }}
                            </h1>
                    </div>
                </div>

                <!-- Remaining -->
                <div class="card border-0 shadow-sm flex-fill">
                    <div class="card-body text-center text-md-end">
                        <div class="small text-muted">Remaining</div>
                        <div class="h5 fw-bold" id="totalRemaining">
                            {{ number_format(($totalBudget ?? 0) - ($totalSpent ?? 0), 2) }}
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
                                        <div class="progress-bar progress-fill" role="progressbar" style="width:0%"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-between small text-muted gap-2">
                                    <div>Spent: <span class="spent">{{ number_format($spent, 2) }}</span></div>
                                    <div>Remaining: <span
                                            class="remaining">{{ number_format(max($amount - $spent, 0), 2) }}</span></div>
                                    <div class="percent">{{ $percent }}%</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 align-items-start mt-3 mt-md-0">
                                <button class="btn btn-sm btn-outline-secondary editBudgetBtn" data-bs-toggle="modal"
                                    data-bs-target="#editBudgetModal" data-id="{{ $b->id }}"
                                    data-amount="{{ $amount }}" data-spent="{{ $spent }}"
                                    data-note="{{ $b->note ?? '' }}" data-category="{{ $b->category_id ?? '' }}"
                                    title="Edit"><i class="fa-solid fa-pen"></i></button>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-4">No budgets set. Add budgets per category to get
                            started.</div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <small class="text-muted">Tip: Tap a category to edit on mobile.</small>
            </div>
        </section>

        <!-- Add Budget Modal -->
        <div class="modal fade" id="addBudgetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form id="addBudgetForm" action="{{ route('budgets.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Budget</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label class="form-label">Category</label>
                                <select id="budgetCategory" name="category_id" class="form-select" required>
                                    @foreach (\App\Models\Categories::where('user_id', auth()->id() ?? 1)->orderBy('name')->get() as $c)
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

        <!-- Edit Budget Modal -->
        <div class="modal fade" id="editBudgetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form id="editBudgetForm" action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Budget</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editBudgetId">
                            <div class="mb-2">
                                <label class="form-label">Category</label>
                                <select id="editBudgetCategory" name="category_id" class="form-select" required>
                                    @foreach (\App\Models\Categories::where('user_id', auth()->id() ?? 1)->orderBy('name')->get() as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Amount</label>
                                <input id="editAmount" name="amount" type="number" step="0.01"
                                    class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Note</label>
                                <input id="editNote" name="note" type="text" class="form-control">
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

    <script src="{{ asset('js/budgets.js') }}"></script>
@endsection

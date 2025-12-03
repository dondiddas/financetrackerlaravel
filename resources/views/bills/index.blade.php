@extends('layout.app')
@section('title','Bills & Subscriptions')
@section('content')

    <style>
        .bills-toolbar { display:flex; gap: .75rem; align-items:center; flex-wrap:wrap; }
        .bills-card { border-radius: 12px; box-shadow: 0 6px 18px rgba(16,24,40,0.06); }
        .search-input { max-width: 420px; }
        .badge-recurring { background: linear-gradient(90deg,#e6f7ff,#dff6ff); color:#0b5563; }
        @media (max-width:767px){ .search-input{ max-width:100%; } }

        /* --- MOBILE STACKED TABLE --- */
        @media (max-width: 768px) {
            .table-responsive table thead {
                display: none;
            }

            .table-responsive table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 12px;
                background: #fff;
            }

            .table-responsive table tbody tr td {
                display: flex;
                justify-content: space-between;
                padding: 6px 4px;
                border: none !important;
            }

            .table-responsive table tbody tr td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6c757d;
                margin-right: 10px;
            }

            .text-end {
                text-align: left !important;
            }

            /* Stack toolbar controls on mobile */
            .bills-toolbar form.d-flex {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: .5rem;
            }

            .bills-toolbar .input-group,
            .bills-toolbar .form-select,
            .bills-toolbar .ms-auto {
                width: 100% !important;
            }

            .bills-toolbar .ms-auto {
                display: flex;
                justify-content: flex-end;
                gap: .5rem;
            }
        }
    </style>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">Bills & Subscriptions</h3>
                <small class="text-muted">Manage recurring payments, overdue items and reminders</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary d-none d-md-inline">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back
                </a>
                <a href="#addBillModal" data-bs-toggle="modal" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Add Bill
                </a>
            </div>
        </div>

        <div class="card bills-card p-3 mb-3">
            <div class="bills-toolbar mb-2">
                <form method="GET" class="d-flex align-items-center w-100 g-2" style="gap:.5rem;">
                    <div class="d-flex w-100 align-items-center" style="gap:.5rem;">
                        <div class="input-group search-input" style="flex:1;">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="search" name="q" value="{{ old('q', $search ?? '') }}" class="form-control" placeholder="Search bills or description">
                        </div>

                        <a id="filtersToggle" href="#" class="btn btn-outline-secondary d-md-none" title="Show filters">
                            <i class="fa-solid fa-filter"></i>
                        </a>

                    </div>

                    <div id="filterControls" class="d-flex align-items-center" style="gap:.5rem;">
                    <select name="status" class="form-select" style="max-width:160px">
                        <option value="">All status</option>
                        <option value="overdue" {{ (isset($status) && $status=='overdue') ? 'selected' : '' }}>Overdue</option>
                        <option value="upcoming" {{ (isset($status) && $status=='upcoming') ? 'selected' : '' }}>Upcoming</option>
                        <option value="paid" {{ (isset($status) && $status=='paid') ? 'selected' : '' }}>Paid</option>
                    </select>

                    <select name="recurring" class="form-select" style="max-width:160px">
                        <option value="">All</option>
                        <option value="1" {{ (isset($recurring) && $recurring=='1') ? 'selected' : '' }}>Recurring only</option>
                    </select>

                    <div class="ms-auto d-flex gap-2">
                        <button class="btn btn-primary" type="submit">Apply</button>
                        <a href="{{ route('bills.index') }}" class="btn btn-outline">Reset</a>
                    </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">Bill</th>
                            <th class="fw-semibold">Amount</th>
                            <th class="fw-semibold">Due Date</th>
                            <th class="fw-semibold">Recurrence</th>
                            <th class="fw-semibold">Status</th>
                            <th class="text-end fw-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bills as $bill)
                            <tr>
                                <td data-label="Bill" style="min-width:220px;">
                                    <div class="fw-semibold">
                                        {{ $bill->bill_name }}
                                        @if($bill->is_recurring)
                                            <span class="badge badge-recurring ms-2 small">Recurring</span>
                                        @endif
                                    </div>
                                    @if(!empty($bill->description))
                                        <div class="small text-muted">{{ Str::limit($bill->description, 80) }}</div>
                                    @endif
                                </td>

                                <td data-label="Amount">
                                    ₱{{ number_format($bill->amount, 2) }}
                                </td>

                                <td data-label="Due Date">
                                    {{ \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') }}
                                </td>

                                <td data-label="Recurrence">
                                    {{ $bill->recurrence_interval ?? '-' }}
                                </td>

                                <td data-label="Status">
                                    @if($bill->is_paid)
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        @if(\Carbon\Carbon::parse($bill->due_date)->lt(now()))
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Upcoming</span>
                                        @endif
                                    @endif
                                </td>

                                <td data-label="Actions" class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if(!$bill->is_paid)
                                            <form action="{{ route('bills.pay', $bill->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success">
                                                    <i class="fa-solid fa-check me-1"></i>Mark Paid
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">No bills found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="billsSummary" class="text-muted">
                    Showing {{ $bills->firstItem() ?? 0 }} - {{ $bills->lastItem() ?? 0 }} of {{ $bills->total() }} bills
                </div>
                <div id="billsPagination">{{ $bills->links() }}</div>
            </div>
        </div>

    </div>

@endsection

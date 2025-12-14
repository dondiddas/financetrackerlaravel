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

            @if (session('success'))
                <!-- Success Modal -->
                <div class="modal fade" id="billSuccessModal" tabindex="-1" aria-labelledby="billSuccessLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h6 class="modal-title d-flex align-items-center mb-0" id="billSuccessLabel">
                                    <i class="fas fa-check-circle me-2"></i> Success
                                </h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var modalEl = document.getElementById('billSuccessModal');
                        if (modalEl) {
                            var modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    });
                </script>
            @endif
                <h3 class="mb-0">Bills & Subscriptions</h3>
                <small class="text-muted">Manage recurring payments, overdue items and reminders</small>
            </div>
        </div>

        <div class="card bills-card p-3 mb-3">
            <div class="bills-toolbar mb-2">
                <form id="billsFilterForm" method="GET" class="d-flex align-items-center w-100 gap-2 flex-wrap">
                    <div class="d-flex w-100 align-items-center gap-2">
                        <div class="input-group search-input" style="flex:1;">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="search" name="q" value="{{ old('q', $search ?? '') }}" class="form-control" placeholder="Search bills or description">
                        </div>
                        <button type="submit" class="btn btn-outline-secondary">Search</button>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                    <select name="status" class="form-select" style="max-width:160px" onchange="this.form.submit()">
                        <option value="">All status</option>
                        <option value="overdue" {{ (isset($status) && $status=='overdue') ? 'selected' : '' }}>Overdue</option>
                        <option value="upcoming" {{ (isset($status) && $status=='upcoming') ? 'selected' : '' }}>Upcoming</option>
                        <option value="paid" {{ (isset($status) && $status=='paid') ? 'selected' : '' }}>Paid</option>
                    </select>

                    <select name="recurring" class="form-select" style="max-width:160px" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="1" {{ (isset($recurring) && $recurring=='1') ? 'selected' : '' }}>Recurring only</option>
                    </select>

                    <div class="d-flex gap-2">
                        <a href="{{ route('bills.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="#addBillModal" data-bs-toggle="modal" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-1"></i> 
                        </a>
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
                                    {{ $bill->recurrenceType->name ?? '-' }}
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
                                        <div class="d-flex align-items-center" style="gap:.5rem;">
                                            @if(!$bill->is_paid)
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                    data-bs-toggle="modal" data-bs-target="#deductionSourceModal{{ $bill->id }}">
                                                    <i class="fa-solid fa-check me-1"></i>
                                                    Mark Paid
                                                </button>
                                            @endif

                                            <button type="button" class="btn btn-sm btn-outline-secondary edit-bill-btn" data-id="{{ $bill->id }}">
                                                <i class="fa-solid fa-pen me-1"></i>
                                            </button>

                                            <form action="{{ route('bills.destroy', $bill->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this bill? This action can be undone from the trash.');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash me-1"></i>
                                                </button>
                                            </form>
                                        </div>
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
                <div id="billsPagination">{{ $bills->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
    <!-- Deduction Source Modals -->
            @foreach($bills as $bill)
                <div class="modal fade" id="deductionSourceModal{{ $bill->id }}" tabindex="-1"
                    aria-labelledby="deductionSourceLabel{{ $bill->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-semibold" id="deductionSourceLabel{{ $bill->id }}">
                                    Deduct from where?
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted mb-3">Select where to deduct the bill amount of <strong>₱{{ number_format($bill->amount, 2) }}</strong></p>
                                <form action="{{ route('bills.pay', $bill->id) }}" method="POST" class="deduction-form">
                                    @csrf
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="deduction_source" value="income" class="btn btn-outline-primary fw-semibold">
                                            <i class="fas fa-wallet me-2"></i> From Income
                                        </button>
                                        <button type="submit" name="deduction_source" value="allowance" class="btn btn-outline-success fw-semibold">
                                            <i class="fas fa-hand-holding-usd me-2"></i> From Allowance
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


<div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="addBillModalLabel">Add New Bill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <input type="number" step="0.01" class="form-control" name="amount" required>
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

                    <!-- Recurring checkbox -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="is_recurring" name="is_recurring" {{ old('is_recurring', true) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="is_recurring">
                            Recurring bill
                        </label>
                    </div>

                    @php
                        $recurrenceTypes = \App\Models\RecurrenceType::orderBy('name')->get();
                    @endphp
                    <div class="mb-3">
                        <label class="form-label small">Recurrence</label>
                        <select name="recurrence_type_id" class="form-select">
                            <option value="">-- none --</option>
                            @foreach($recurrenceTypes as $rt)
                                <option value="{{ $rt->id }}" {{ (string)old('recurrence_type_id') === (string)$rt->id ? 'selected' : '' }}>{{ ucfirst($rt->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Bill</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Edit Bill Modal -->
<div class="modal fade" id="editBillModal" tabindex="-1" aria-labelledby="editBillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="editBillModalLabel">Edit Bill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editBillForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bill Name</label>
                        <input type="text" id="edit_bill_name" class="form-control" name="bill_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amount</label>
                        <input type="number" step="0.01" id="edit_amount" class="form-control" name="amount" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" id="edit_due_date" class="form-control" name="due_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description (optional)</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="edit_is_recurring" name="is_recurring">
                        <label class="form-check-label small" for="edit_is_recurring">Recurring bill</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Recurrence</label>
                        <select id="edit_recurrence_type_id" name="recurrence_type_id" class="form-select">
                            <option value="">-- none --</option>
                            @foreach($recurrenceTypes as $rt)
                                <option value="{{ $rt->id }}">{{ ucfirst($rt->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function(){
        const editButtons = document.querySelectorAll('.edit-bill-btn');
        if (!editButtons.length) return;

        const editForm = document.getElementById('editBillForm');
        const editModalEl = document.getElementById('editBillModal');

        function getBootstrapModal() {
            try {
                if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
                    return window.bootstrap.Modal.getOrCreateInstance(editModalEl);
                }
            } catch (e) {
                return null;
            }
            return null;
        }

        editButtons.forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                try {
                    const res = await fetch(`/bills/${id}`);
                    if (!res.ok) throw new Error('Failed to load bill');
                    const data = await res.json();

                    // populate form
                    document.getElementById('edit_bill_name').value = data.bill_name || '';
                    document.getElementById('edit_amount').value = data.amount || '';
                    document.getElementById('edit_due_date').value = data.due_date || '';
                    document.getElementById('edit_description').value = data.description || '';
                    document.getElementById('edit_is_recurring').checked = !!data.is_recurring;
                    document.getElementById('edit_recurrence_type_id').value = data.recurrence_type_id || '';

                    // set form action
                    editForm.action = `/bills/${id}`;

                    // Show the modal, waiting briefly if bootstrap isn't ready yet
                    let shown = false;
                    const tryShow = () => {
                        const modal = getBootstrapModal();
                        if (modal) {
                            modal.show();
                            shown = true;
                        }
                    };

                    tryShow();
                    if (!shown) {
                        // try again a few times (in case scripts load later)
                        let attempts = 0;
                        const interval = setInterval(() => {
                            attempts++;
                            tryShow();
                            if (shown || attempts > 10) clearInterval(interval);
                        }, 100);
                    }
                } catch (err) {
                    alert('Could not load bill for editing.');
                }
            });
        });
    })();
</script>
@endsection

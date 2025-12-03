@extends('layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Reports & Insights</h3>
        <div>
            <a href="{{ route('reports.export', ['from' => $start, 'to' => $end]) }}" class="btn btn-sm btn-outline-secondary">Export CSV</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="reportsFilters" class="row gx-2 gy-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from', $start) }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to', $end) }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        <option value="expense" {{ request('type')=='expense' ? 'selected':'' }}>Expense</option>
                        <option value="income" {{ request('type')=='income' ? 'selected':'' }}>Income</option>
                        <option value="allowance" {{ request('type')=='allowance' ? 'selected':'' }}>Allowance</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Categories Breakdown (Pie)</h6>
                    <canvas id="categoriesPie" style="max-height:300px"></canvas>
                </div>
                <div class="card-footer small text-muted">Showing categories by total amount</div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">Monthly Comparison (6 months)</h6>
                    <canvas id="monthlyChart" style="max-height:300px"></canvas>
                </div>
                <div class="card-footer small text-muted">Net totals per month</div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Total</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $c)
                                    <tr>
                                        <td>{{ $c->category_name }}</td>
                                        <td class="text-end">{{ number_format($c->total,2) }}</td>
                                        <td>{{ ucfirst($c->category_type) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    window.REPORTS = {
        categories: {!! json_encode($expenseCategories) !!},
        months: {!! json_encode($months) !!},
        monthlyTotals: {!! json_encode($monthlyTotals) !!}
    };
</script>
<script src="{{ asset('plugins/chart.js/chart.min.js') }}"></script>
<script src="{{ asset('js/reports.js') }}"></script>

@endsection

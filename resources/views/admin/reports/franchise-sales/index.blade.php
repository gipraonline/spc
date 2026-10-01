@extends('layouts.app')

@section('content')
<div class="card w-100 border-0 mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h5 class="mb-0 fw-semibold"><i class="ti ti-building-store me-1"></i> Franchise-wise Sales Summary</h5>
            @canany(['franchise-sales-report.export', 'sales-orders.view'])
            <a href="{{ route('admin.reports.franchise-sales.export', request()->query()) }}" class="btn btn-success text-white">
                <i class="ti ti-file-export me-1"></i> Export to Excel
            </a>
            @endcanany
        </div>

        <form method="GET" action="{{ route('admin.reports.franchise-sales.index') }}" id="fsForm">
            <div class="row g-3 align-items-end mb-4">
                <div class="col-lg-2 col-md-4">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label">State</label>
                    <select name="state_id" class="form-select" onchange="document.getElementById('fsDistrict').value='';this.form.submit()">
                        <option value="">All States</option>
                        @foreach($states as $state)
                        <option value="{{ $state->n_state_id }}" {{ request('state_id') == $state->n_state_id ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label">District</label>
                    <select name="district_id" id="fsDistrict" class="form-select">
                        <option value="">All Districts</option>
                        @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->district_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Franchise</label>
                    <select name="franchise_id" class="form-select">
                        <option value="">All Franchises</option>
                        @foreach($franchises as $f)
                        <option value="{{ $f->n_store_id }}" {{ request('franchise_id') == $f->n_store_id ? 'selected' : '' }}>{{ $f->c_store_name }} ({{ $f->c_store_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn buttonSpc">Filter Report</button>
                    <a href="{{ route('admin.reports.franchise-sales.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
            @if ($errors->any())
            <div class="text-danger mb-3">{{ $errors->first() }}</div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Franchise</th>
                        <th>District</th>
                        <th class="text-end">Orders</th>
                        <th class="text-end">Sales Total</th>
                        <th class="text-end">Discount</th>
                        <th class="text-end">GST</th>
                        <th class="text-end">Net Amount</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Pending</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $r)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            <h6 class="mb-0 fw-semibold">{{ $r->store_name }}</h6>
                            <span class="fs-2 text-muted">{{ $r->store_code }}</span>
                        </td>
                        <td>{{ $r->district ?? '-' }}</td>
                        <td class="text-end">{{ $r->orders }}</td>
                        <td class="text-end">{{ number_format($r->gross, 2) }}</td>
                        <td class="text-end">{{ number_format($r->discount, 2) }}</td>
                        <td class="text-end">{{ number_format($r->gst, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($r->net, 2) }}</td>
                        <td class="text-end">{{ number_format($r->paid, 2) }}</td>
                        <td class="text-end">{{ number_format($r->pending, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted py-4">No sales found for the selected filters.</td></tr>
                    @endforelse
                </tbody>
                @if($rows->count())
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Total</td>
                        <td class="text-end">{{ $totals->orders }}</td>
                        <td class="text-end">{{ number_format($totals->gross, 2) }}</td>
                        <td class="text-end">{{ number_format($totals->discount, 2) }}</td>
                        <td class="text-end">{{ number_format($totals->gst, 2) }}</td>
                        <td class="text-end">{{ number_format($totals->net, 2) }}</td>
                        <td class="text-end">{{ number_format($totals->paid, 2) }}</td>
                        <td class="text-end">{{ number_format($totals->pending, 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Payout Batch Overview</h4>
            <small class="text-muted">Financial breakdown & transaction ledger</small>
        </div>

        <a href="{{ route('admin.payout-reports.index') }}" class="btn btn-light border btn-sm shadow-sm">
            ← Back
        </a>
    </div>

    {{-- KPI WRAPPER --}}
    <div class="row g-3 mb-4 kpi-row ">

        {{-- Batch No --}}
        {{-- Batch No --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm kpi-card bg-gradient-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <div class="small opacity-75">Batch No</div>
                        <div class="fs-5 fw-bold">{{ $batch->batch_no }}</div>
                        <div class="small opacity-75">System generated</div>
                    </div>

                    <div class="kpi-icon bg-white text-primary">
                        <i class="fa-solid fa-receipt"></i>
                    </div>

                </div>
            </div>
        </div>

        {{-- Payout Type --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm kpi-card bg-gradient-warning text-white">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <div class="small opacity-75">Payout Type</div>
                        <div class="fs-5 fw-bold">
                            {{ ucfirst($batch->payout_type) }}
                        </div>
                        <div class="small opacity-75">Processing mode</div>
                    </div>

                    <div class="kpi-icon bg-white text-warning">
                        <i class="fa-solid fa-calendar-week"></i>
                    </div>

                </div>
            </div>
        </div>

        {{-- Employees --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm kpi-card bg-gradient-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <div class="small opacity-75">Employees</div>
                        <div class="fs-5 fw-bold">
                            {{ number_format($batch->employee_count) }}
                        </div>
                        <div class="small opacity-75">Active records</div>
                    </div>

                    <div class="kpi-icon bg-white text-success">
                        <i class="fa-solid fa-users"></i>
                    </div>

                </div>
            </div>
        </div>

        {{-- Total Payout --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm kpi-card bg-gradient-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <div class="small opacity-75">Total Payout</div>
                        <div class="fs-5 fw-bold">
                            ₹ {{ number_format($batch->total_amount, 2) }}
                        </div>
                        <div class="small opacity-75">All employees included</div>
                    </div>

                    <div class="kpi-icon bg-white text-success">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">

            <form method="GET" action="{{ route('admin.payout-reports.show', $batch->id) }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4 position-relative">

                        <label class="form-label">Employee</label>

                        <input type="text" id="employee" name="employee" class="form-control" autocomplete="off"
                            value="{{ request('employee') }}" placeholder="Search employee">

                        <div id="employeeSuggestion" class="list-group position-absolute w-100"
                            style="display:none; top:100%; left:0; z-index:9999; max-height:220px; overflow-y:auto;">
                        </div>

                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-4 d-flex gap-2">

                        <button class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass"></i> Filter
                        </button>

                        <a href="{{ route('admin.payout-reports.show',$batch->id) }}" class="btn btn-secondary">
                            Reset
                        </a>
                        @can('payout-reports.export')
                        <a href="{{ route('admin.payout-reports.export.excel',$batch->id) }}?{{ http_build_query(request()->query()) }}"
                            class="btn btn-success">
                            <i class="fa fa-file-excel me-1"></i>Export to Excel
                        </a>
                        @endcan


                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">
            <div>
                <strong>Transaction Ledger</strong>
                <div class="text-muted small">
                    Latest entries appear first
                </div>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr class="text-uppercase small text-muted">
                        <th>#</th>
                        <th>Employee</th>
                        <th>Wallet</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transactions as $key => $txn)

                    <tr>

                        <td>
                            {{ $transactions->firstItem() + $key }}
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $txn->employee->c_employee_name ?? 'N/A' }}
                            </div>

                            <small class="text-muted">
                                {{ $txn->employee->designation->c_designation ?? 'No Designation' }}
                            </small>
                        </td>

                        <td>
                            #{{ $txn->n_wallet_id }}
                        </td>

                        <td class="fw-bold">
                            ₹ {{ number_format($txn->n_amount,2) }}
                        </td>

                        <td>
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                Success
                            </span>
                        </td>

                        <td>
                            {{ $txn->c_description ?? '-' }}
                        </td>

                        <td>
                            {{ $txn->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="text-center py-5">
                            No transactions found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-footer bg-white">
            {{ $transactions->appends(request()->query())->links() }}
        </div>

    </div>

</div>

{{-- STYLES --}}
<style>
.kpi-row {
    background: #f8fafc;
    padding: 12px;
    border-radius: 18px;
}

.kpi-card {
    border: none !important;
    border-radius: 18px;
    overflow: hidden;
    transition: all .3s ease;
    min-height: 125px;
}

.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, .15);
}

.kpi-card .card-body {
    padding: 1.4rem;
}

.kpi-card .small {
    color: rgba(255, 255, 255, .85);
}

.kpi-card .fw-bold {
    color: #fff;
}

.kpi-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    background: rgba(255, 255, 255, .2);
}

.table td,
.table th {
    vertical-align: middle;
}

/* ---------- Gradients ---------- */

.bg-gradient-primary {
    background: linear-gradient(135deg, #1e3c72, #2a5298) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #7b2ff7, #3f86ed) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e, #38ef7d) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #ff6a00, #ee0979) !important;
}

/* ---------- Icon Colors ---------- */

.text-primary {
    color: #1e3c72 !important;
}

.text-warning {
    color: #7b2ff7 !important;
}

.text-success {
    color: #11998e !important;
}

.text-info {
    color: #ff6a00 !important;
}

#employeeSuggestion {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
}

#employeeSuggestion .list-group-item {
    cursor: pointer;
    border: 0;
}

#employeeSuggestion .list-group-item:hover {
    background: #0d6efd;
    color: #fff;
}
</style>
@endsection
@push('scripts')
<script>
$(function() {

    $("#employee").on("keyup", function() {

        let keyword = $(this).val().trim();

        if (keyword.length < 2) {
            $("#employeeSuggestion").hide().empty();
            return;
        }

        $.ajax({
            url: "{{ route('admin.employee.search') }}",
            type: "GET",
            data: {
                search: keyword
            },
            success: function(response) {

                let html = "";

                if (response.length > 0) {

                    $.each(response, function(i, emp) {

                        html += `
                            <a href="javascript:void(0)"
                               class="list-group-item list-group-item-action employee-item"
                               data-name="${emp.c_employee_name}">
                                ${emp.c_employee_name}
                            </a>`;
                    });

                    $("#employeeSuggestion")
                        .html(html)
                        .show();

                } else {

                    $("#employeeSuggestion")
                        .html(
                            '<div class="list-group-item text-muted">No employee found</div>'
                        )
                        .show();
                }
            }
        });

    });

    $(document).on("click", ".employee-item", function() {

        $("#employee").val($(this).data("name"));

        $("#employeeSuggestion").hide().empty();

    });

    $(document).click(function(e) {

        if (!$(e.target).closest("#employee,#employeeSuggestion").length) {
            $("#employeeSuggestion").hide().empty();
        }

    });

});
</script>
@endpush
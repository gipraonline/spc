@extends('layouts.app')

@push('styles')
<style>
.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffeeba;
    font-weight: 600;
}

.status-paid {
    background-color: #d1e7dd;
    color: #0f5132;
    border-color: #badbcc;
    font-weight: 600;
}

.status-default {
    background-color: #e2e3e5;
    color: #41464b;
    border-color: #d6d8db;
    font-weight: 600;
}

.status-select {
    width: 140px !important;
    min-width: 140px;
}

.remarks-input {
    width: 100%;
    min-width: 280px;
    max-width: 450px;
    resize: vertical;
    overflow-y: auto;
    /* Soft remarks background */
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    color: #6f42c1;
    font-weight: 500;
}

.remarks-input:focus {
    background-color: #d1e7dd;
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.15);
}

.remarks-status {
    display: block;
    margin-top: 3px;
    font-size: 11px;
}

/* Table column widths */
.payment-table {
    min-width: 1300px;
}

.payment-table th,
.payment-table td {
    vertical-align: middle;
}

.payment-table th:nth-child(1),
.payment-table td:nth-child(1) {
    width: 70px;
    min-width: 70px;
    white-space: nowrap;
}

.payment-table th:nth-child(2),
.payment-table td:nth-child(2) {
    width: 180px;
    min-width: 180px;
    white-space: nowrap;
}

.payment-table th:nth-child(3),
.payment-table td:nth-child(3) {
    width: 150px;
    min-width: 150px;
    white-space: nowrap;
}

.payment-table th:nth-child(4),
.payment-table td:nth-child(4) {
    width: 220px;
    min-width: 220px;
}

.payment-table th:nth-child(5),
.payment-table td:nth-child(5) {
    width: 150px;
    min-width: 150px;
    white-space: nowrap;
}

.payment-table th:nth-child(6),
.payment-table td:nth-child(6) {
    width: 170px;
    min-width: 170px;
}

.payment-table th:nth-child(7),
.payment-table td:nth-child(7) {
    width: 160px;
    min-width: 160px;
}

.payment-table th:nth-child(8),
.payment-table td:nth-child(8) {
    width: 350px;
    min-width: 350px;
}

.payment-status-form {
    margin: 0;
}
</style>
@endpush

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            Payment Management
        </h4>
    </div>

    {{-- Filters --}}
    <div class="card">
        <div class="card-body">

            <form method="GET" action="{{ route('admin.payment-management.index') }}">

                <div class="row g-3">

                    {{-- Payment Mode --}}
                    <div class="col-md-3">
                        <label for="payment_mode" class="form-label">
                            Payment Mode
                        </label>

                        <select name="payment_mode" id="payment_mode" class="form-control">
                            <option value="">
                                All
                            </option>

                            @foreach($paymentModes as $paymentMode)
                            <option value="{{ $paymentMode }}"
                                {{ request('payment_mode') == $paymentMode ? 'selected' : '' }}>
                                {{ $paymentMode }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payment Status --}}
                    <div class="col-md-3">
                        <label for="filter_status" class="form-label">
                            Payment Status
                        </label>

                        <select name="status" id="filter_status" class="form-control">
                            <option value="">
                                All
                            </option>

                            @foreach($paymentStatuses as $paymentStatus)
                            <option value="{{ $paymentStatus }}"
                                {{ request('status') == $paymentStatus ? 'selected' : '' }}>
                                {{ ucfirst($paymentStatus) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- From Date --}}
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">
                            From Date
                        </label>

                        <input type="date" name="from_date" id="from_date" class="form-control"
                            value="{{ request('from_date') }}">
                    </div>

                    {{-- To Date --}}
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">
                            To Date
                        </label>

                        <input type="date" name="to_date" id="to_date" class="form-control"
                            value="{{ request('to_date') }}">
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="row mt-3">
                    <div class="col-12 d-flex">

                        <button type="submit" class="btn btn-primary me-2">
                            Filter
                        </button>

                        <a href="{{ route('admin.payment-management.index') }}" class="btn btn-secondary me-2">
                            Reset
                        </a>

                        <a href="{{ route(
                                'admin.payment-management.export',
                                request()->query()
                            ) }}" class="btn btn-success">
                            Export Excel
                        </a>

                    </div>
                </div>

            </form>

        </div>
    </div>

    {{-- Payment Table --}}
    <div class="card mt-4">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle payment-table">

                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Order No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($orders as $index => $order)

                        <tr>

                            {{-- Sl No --}}
                            <td>
                                {{ $orders->firstItem() + $index }}
                            </td>

                            {{-- Order No --}}
                            <td>
                                {{ $order->c_order_no }}
                            </td>

                            {{-- Date --}}
                            <td>
                                {{ $order->d_date?->format('d-m-Y') }}
                            </td>

                            {{-- Customer --}}
                            <td>
                                {{ $order->c_customer_name }}
                            </td>

                            {{-- Amount --}}
                            <td>
                                ₹{{ number_format($order->n_net_sales_amount, 2) }}
                            </td>

                            {{-- Payment Mode --}}
                            <td>
                                {{ $order->c_mode_of_payment }}
                            </td>

                            {{-- Payment Status --}}
                            <td>

                                <form method="POST" action="{{ route(
                                            'admin.payment-management.update-status',
                                            $order
                                        ) }}" class="payment-status-form">

                                    @csrf
                                    @method('PATCH')

                                    <select name="payment_status" class="form-control status-select
                                                @if($order->payment_status === 'pending')
                                                    status-pending
                                                @elseif($order->payment_status === 'paid')
                                                    status-paid
                                                @else
                                                    status-default
                                                @endif" onchange="this.form.submit()">

                                        @foreach($paymentStatuses as $paymentStatus)

                                        <option value="{{ $paymentStatus }}"
                                            {{ $order->payment_status === $paymentStatus ? 'selected' : '' }}>
                                            {{ ucfirst($paymentStatus) }}
                                        </option>

                                        @endforeach

                                    </select>

                                </form>

                            </td>

                            {{-- Remarks --}}
                            <td>

                                <div class="remarks-wrapper">

                                    <textarea class="form-control remarks-input" rows="2" maxlength="1000"
                                        placeholder="Enter remarks..." data-url="{{ route(
                                                'admin.payment-management.update-remarks',
                                                $order
                                            ) }}">{{ $order->latestPaymentStatusLog?->remarks ?? '' }}</textarea>

                                    <small class="remarks-status text-muted"></small>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No payment records found.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $orders->links() }}
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.remarks-input').forEach(function(input) {

        let timeout;

        input.addEventListener('input', function() {

            clearTimeout(timeout);

            const field = this;

            const wrapper = field.closest('.remarks-wrapper');

            const status = wrapper ?
                wrapper.querySelector('.remarks-status') :
                null;

            // Show typing status
            if (status) {
                status.textContent = 'Typing...';
                status.className =
                    'remarks-status text-muted';
            }

            timeout = setTimeout(function() {

                const remarks = field.value.trim();

                /*
                 * Do not allow empty remarks.
                 * This prevents clearing an existing remark.
                 */
                if (remarks === '') {

                    if (status) {
                        status.textContent =
                            'Remark cannot be empty';

                        status.className =
                            'remarks-status text-danger';
                    }

                    return;
                }

                // Show saving status
                if (status) {
                    status.textContent = 'Saving...';
                    status.className =
                        'remarks-status text-warning';
                }

                fetch(field.dataset.url, {

                        method: 'PATCH',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },

                        body: JSON.stringify({
                            remarks: remarks
                        })

                    })

                    .then(async function(response) {

                        let data = {};

                        try {
                            data = await response.json();
                        } catch (e) {
                            // Response is not JSON
                        }

                        if (!response.ok) {
                            throw new Error(
                                data.message ||
                                'Failed to save remarks'
                            );
                        }

                        return data;
                    })

                    .then(function(data) {

                        if (status) {
                            status.textContent = 'Saved ✓';
                            status.className =
                                'remarks-status text-success';
                        }

                        setTimeout(function() {

                            if (status) {
                                status.textContent = '';
                            }

                        }, 2000);
                    })

                    .catch(function(error) {

                        console.error(
                            'Remarks save error:',
                            error
                        );

                        if (status) {
                            status.textContent =
                                error.message || 'Save failed';

                            status.className =
                                'remarks-status text-danger';
                        }
                    });

            }, 1000);

        });

    });

});
</script>

@endpush
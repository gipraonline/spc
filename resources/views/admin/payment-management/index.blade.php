@extends('layouts.app')
@push('styles')
<style>
.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffeeba;
    font-weight: 600;
}

.status-confirmed {
    background-color: #cff4fc;
    color: #055160;
    border-color: #b6effb;
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
    font-weight: 600;
}

.remarks-input {
    min-width: 280px;
    max-width: 400px;
    resize: vertical;
    overflow-y: auto;
}

.remarks-status {
    display: block;
    margin-top: 3px;
    font-size: 11px;
}
</style>
@endpush
@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            Payment Management
        </h4>
    </div>

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
                        <label for="status" class="form-label">
                            Payment Status
                        </label>

                        <select name="status" id="status" class="form-control">

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

    <div class="card mt-4">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

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

                            {{-- Status --}}
                            <td>

                                @php
                                $statusClass = match ($order->payment_status) {
                                'pending' => 'status-pending',
                                'paid' => 'status-paid',
                                default => 'status-default',
                                };
                                @endphp
                                <form method="POST" action="{{ route(
        'admin.payment-management.update-status',
        $order
    ) }}">

                                    @csrf
                                    @method('PATCH')

                                    <select name="payment_status" class="form-select form-select-sm {{ $statusClass }}"
                                        onchange="this.form.submit()">

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
                                @if($order->payment_status === 'paid')

                                <div class="remarks-wrapper">

                                    <textarea class="form-control form-control-sm remarks-input" rows="2"
                                        maxlength="1000" placeholder="Enter remarks..."
                                        data-url="{{ route('admin.payment-management.update-remarks', $order) }}">{{ $order->latestPaymentStatusLog?->remarks ?? '' }}</textarea>

                                    <small class="remarks-status text-muted"></small>

                                </div>

                                @else

                                <span class="text-muted">—</span>

                                @endif
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
                status.className = 'remarks-status text-muted';
            }

            timeout = setTimeout(function() {

                /*
                 * Do not allow empty remarks.
                 * This prevents clearing an existing remark.
                 */
                if (field.value.trim() === '') {

                    if (status) {
                        status.textContent = 'Remark cannot be empty';
                        status.className = 'remarks-status text-danger';
                    }

                    return;
                }

                // Show saving status
                if (status) {
                    status.textContent = 'Saving...';
                    status.className = 'remarks-status text-warning';
                }

                fetch(field.dataset.url, {
                        method: 'PATCH',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },

                        body: JSON.stringify({
                            remarks: field.value.trim()
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

                        console.log('Remarks saved successfully');

                        if (status) {
                            status.textContent = 'Saved ✓';
                            status.className =
                                'remarks-status text-success';
                        }

                        // Remove "Saved ✓" after 2 seconds
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
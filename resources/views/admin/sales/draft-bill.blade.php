@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card w-100 position-relative overflow-hidden mb-4">
            <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-semibold mb-0 lh-sm">Bill Details: {{ $bill_no }}</h5>
                <a href="{{ route('admin.sales.drafts') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Back to Drafts
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light-primary shadow-none border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white d-inline-block px-3 py-2 rounded-2 me-3">
                                        <i class="ti ti-receipt fs-6"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 fw-normal">Total Bill Amount</p>
                                        <h4 class="mb-0 fw-semibold">₹ {{ number_format($totalAmount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Store Code</th>
                                <th>Item Code</th>
                                <th class="text-end">Selling Price</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Subtotal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($drafts as $i => $draft)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $draft->c_store_code }}</td>
                                <td>{{ $draft->c_item_code }}</td>
                                <td class="text-end">{{ number_format($draft->n_selling_price, 2) }}</td>
                                <td class="text-end">{{ $draft->n_quantity }}</td>
                                <td class="text-end fw-semibold text-primary">
                                    {{ number_format($draft->n_selling_price * $draft->n_quantity, 2) }}
                                </td>
                                <td>
                                    @if($draft->c_status === 'valid')
                                        <span class="badge bg-success">Valid</span>
                                    @elseif($draft->c_status === 'error')
                                        <span class="badge bg-danger">Error</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
                                <th class="text-end text-primary fw-bolder">₹ {{ number_format($totalAmount, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

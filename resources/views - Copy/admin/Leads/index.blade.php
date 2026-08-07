@extends('layouts.app')

@push('styles')
<style>
.card form {
    background: #fff;
    border-bottom: 1px solid #eee;
}

.form-label {
    margin-bottom: 8px;
}

.form-control {
    height: 45px;
}

.btn {
    height: 45px;
}
</style>
@endpush

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp
<div class="card w-100 position-relative overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Leads</h5>

        @can('leads.create')
        <a href="{{ route('admin.leads.create') }}" class="btn buttonSpc">
            Add Lead Entry
        </a>
        @endcan
    </div>

    <div class="card-body p-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.leads.index') }}">
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Customer / Mobile / Advisor"
                                   value="{{ request('search') }}">
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date"
                                   name="from_date"
                                   class="form-control"
                                   value="{{ request('from_date') }}">
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date"
                                   name="to_date"
                                   class="form-control"
                                   value="{{ request('to_date') }}">
                        </div>

                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Interested">Interested</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Negotiation">Negotiation</option>
                                <option value="Won">Won</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>

                        <div class="col-lg-3 pt-4 d-flex gap-2">
                            <button class="btn buttonSpc">
                                <i class="ti ti-search"></i> Filter
                            </button>

                            <a href="{{ route('admin.leads.index') }}"
                               class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </form>

        <!-- Statistics -->
        <div class="row mb-4">

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-primary-subtle">
                    <div class="card-body text-center">
                        <h3>{{ $totalLeads ?? 0 }}</h3>
                        <p class="mb-0">Total Leads</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-warning-subtle">
                    <div class="card-body text-center">
                        <h3>{{ $pendingFollowups ?? 0 }}</h3>
                        <p class="mb-0">Follow-ups Pending</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-success-subtle">
                    <div class="card-body text-center">
                        <h3>{{ $readyToBuy ?? 0 }}</h3>
                        <p class="mb-0">Ready to Buy</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 bg-info-subtle">
                    <div class="card-body text-center">
                        <h3>{{ $newCustomers ?? 0 }}</h3>
                        <p class="mb-0">New Customers</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Crop</th>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Next Follow-up</th>
                        <th>Priority</th>
                        <th>Advisor</th>
                        <th>Remarks</th>

                        @canany(['leads.view','leads.edit','leads.delete'])
                        <th>Actions</th>
                        @endcanany
                    </tr>
                </thead>

                <tbody>

                    @if(isset($leads))
                        @forelse($leads as $key => $lead)

                        <tr>

                            <td>{{isset($leads) ?? $leads->firstItem() + $key }}</td>

                            <td>{{ \Carbon\Carbon::parse($lead->followup_date)->format('d M Y') }}</td>

                            <td>
                                <strong>{{ $lead->customer_name }}</strong><br>
                                <small>{{ $lead->mobile }}</small>
                            </td>

                            <td>{{ $lead->location }}</td>

                            <td>{{ $lead->crop }}</td>

                            <td>{{ $lead->product }}</td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $lead->status }}
                                </span>
                            </td>

                            <td>
                                {{ optional($lead->next_followup_date)->format('d M Y') }}
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $lead->priority }}
                                </span>
                            </td>

                            <td>{{ $lead->advisor }}</td>

                            <td>{{ $lead->remarks }}</td>

                            @canany(['leads.view','leads.edit','leads.delete'])
                            <td>

                                <div class="dropdown dropstart">

                                    <a href="#" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical fs-6"></i>
                                    </a>

                                    <ul class="dropdown-menu">

                                        <li>
                                            <a class="dropdown-item"
                                            href="#">
                                                <i class="ti ti-eye me-2"></i>View
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                            href="#">
                                                <i class="ti ti-edit me-2"></i>Edit
                                            </a>
                                        </li>

                                        <li>

                                            <form method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button class="dropdown-item text-danger">
                                                    <i class="ti ti-trash me-2"></i>Delete
                                                </button>

                                            </form>

                                        </li>

                                    </ul>

                                </div>

                            </td>
                            @endcanany

                        </tr>

                        @empty

                        <tr>
                            <td colspan="12" class="text-center">
                                No lead records found.
                            </td>
                        </tr>

                        @endforelse
                    @endif
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            @if(isset($leads))
                {{ $leads->links() }}
            @endif
        </div>

    </div>
</div>
@endsection

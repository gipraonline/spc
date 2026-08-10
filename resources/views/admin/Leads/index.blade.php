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
                        @if(isset($user) && $user->identifier != "FCA")
                            <div class="col-lg-3">
                                <label class="form-label fw-semibold">Farm Care Advisors</label>
                                <select name="n_fca_id" class="form-control mandatory">
                                        <option value="">Select Farm Care Adviser</option>

                                        @foreach($employees as $employee)
                                        <option value="{{ $employee->n_employee_id }}" {{isset($lead->n_fca_id) && $lead->n_fca_id==$employee->n_employee_id ? "selected": ''}}>
                                            {{ $employee->c_employee_name }}
                                        </option>
                                        @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Customer / Mobile "
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
        @if(isset($user) && $user->identifier != "FCA")
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
        @endif
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-responsive table-hover align-middle text-nowrap">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Next Follow-up</th>
                        <th>Priority</th>
                        @if(isset($user) && $user->identifier != "FCA")
                          <th>Farm Care Advisor</th>
                        @endif
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

                            <td>{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</td>

                            <td>
                                <strong>{{ $lead->c_customer_name }}</strong><br>
                                <small>{{ $lead->n_mobile }}</small>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $lead->c_lead_status }}
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

                            @if(isset($user) && $user->identifier != "FCA")
                                <td>{{ $lead->fca->c_employee_name }}</td>
                            @endif

                            <td>{{ $lead->remarks }}</td>

                            @canany(['leads.view','leads.edit','leads.delete'])
                            <td>

                                <div class="dropdown dropstart">

                                    <a href="#" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical fs-6"></i>
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            @can('leads.view-details')
                                            <a class="dropdown-item d-flex align-products-center gap-3"
                                                href="{{ route('admin.leads.show', Crypt::encryptString($lead->n_lead_id)) }}">
                                                <i class="fs-4 ti ti-eye"></i>View Details
                                            </a>
                                            @endcan
                                        </li>
                                        <li>
                                            @can('leads.edit')
                                            <a class="dropdown-item d-flex align-products-center gap-3"
                                                href="{{ route('admin.leads.edit', Crypt::encryptString($lead->n_lead_id)) }}">
                                                <i class="fs-4 ti ti-edit"></i>Edit
                                            </a>
                                            @endcan
                                        </li>
                                        <li>
                                            @can('leads.delete')
                                            <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="dropdown-item d-flex align-products-center gap-3 text-danger">
                                                    <i class="fs-4 ti ti-trash"></i>Delete
                                                </button>
                                            </form>
                                            @endcan
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

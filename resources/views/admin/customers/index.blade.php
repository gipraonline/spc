@extends('layouts.app')

@section('content')

<style>
/* Filter Card */
.filter-card-wrapper {
    background: #fff;
    border: 1px solid #eef2f6;
    border-radius: 12px;
    margin: 1.5rem 2rem;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .02);
}

.filter-header-sub {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.25rem;
    color: #2a3547;
}

.filter-header-sub .icon-box {
    width: 32px;
    height: 32px;
    background: rgba(93, 135, 255, .1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5d87ff;
}

.filter-header-sub span {
    font-size: .9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.premium-filter-container {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 30px;
}

.search-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #1b3e86;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
}

.styled-textbox,
.styled-select {
    height: 54px !important;
    border: 1.5px solid #dfe5ef !important;
    border-radius: 16px !important;
    background: #f8fafc !important;
    padding: 0 18px !important;
}

.styled-textbox:focus,
.styled-select:focus {
    border-color: #7f8ca0 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, .08) !important;
}

.btn-creative-filter,
.btn-reset {
    height: 54px !important;
    min-height: 54px;
    border-radius: 16px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 28px !important;
    font-weight: 600;
}

@media(max-width:768px) {

    .premium-filter-container {
        padding: 15px;
    }

    .filter-card-wrapper {
        margin: 1rem;
        padding: 1rem;
    }

}
</style>

<div class="card w-100 position-relative overflow-hidden">

    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">

        <h5 class="card-title fw-semibold mb-0">
            Customers
        </h5>

        @can('customers.create')
        <a href="{{ route('admin.customers.create') }}" class="btn buttonSpc">
            Add Customer
        </a>
        @endcan

    </div>

    <div class="filter-card-wrapper">

        <div class="filter-header-sub">

            <div class="icon-box">
                <i class="ti ti-filter"></i>
            </div>

            <span>Refine Search</span>

        </div>

        <div class="premium-filter-container">

            <form method="POST" action="{{ route('admin.customers.search') }}">

                @csrf

                <div class="row g-3 align-items-end">

                    <div class="col-md-7">

                        <label class="search-label">
                            Customer
                        </label>

                        <input type="text" name="customer_search" class="form-control styled-textbox"
                            placeholder="Search by Customer Code / Name / Mobile"
                            value="{{ session('customer_search') }}">

                    </div>

                    <div class="col-md-2">

                        <label class="search-label">
                            Status
                        </label>

                        <select name="status_filter" class="form-select styled-select">

                            <option value="">All</option>

                            <option value="Y" {{ session('status_filter')=='Y' ? 'selected':'' }}>
                                Active
                            </option>

                            <option value="N" {{ session('status_filter')=='N' ? 'selected':'' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">
                        <label class="invisible">Action</label>

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn buttonSpc btn-creative-filter flex-fill">
                                <i class="ti ti-search"></i>
                                Filter
                            </button>

                            <a href="{{ route('admin.customers.clearSearch') }}"
                                class="btn btn-outline-primary btn-reset">
                                <i class="ti ti-refresh"></i>
                                Reset
                            </a>

                        </div>
                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card-body p-4">

        @if(Session::has('success'))

        <div class="alert alert-success">

            {{ Session::get('success') }}

        </div>

        @endif

        <div class="table-responsive">

            <table class="table text-nowrap mb-0 align-middle">

                <thead class="text-dark fs-4">

                    <tr>

                        <th>Sl No</th>

                        <th>Customer Code</th>

                        <th>Customer Name</th>

                        <th>Mobile</th>

                        <th>WhatsApp</th>

                        <th>District</th>

                        <th>State</th>

                        <th>Status</th>

                        @canany(['customers.edit','customers.delete'])
                        <th>Actions</th>
                        @endcanany

                    </tr>

                </thead>

                <tbody>
                    @forelse($customers as $key => $customer)

                    <tr>

                        <td class="border-bottom-0 text-center">
                            {{ $customers->firstItem() + $key }}
                        </td>

                        <td class="border-bottom-0">
                            {{ $customer->c_customer_code }}
                        </td>

                        <td class="border-bottom-0">
                            <strong>{{ $customer->c_customer_name }}</strong>
                        </td>

                        <td class="border-bottom-0">
                            {{ $customer->n_mobile }}
                        </td>

                        <td class="border-bottom-0">
                            {{ $customer->n_whatsapp ?? '-' }}
                        </td>

                        <td class="border-bottom-0">
                            {{ $customer->c_district ?? '-' }}
                        </td>

                        <td class="border-bottom-0">
                            {{ $customer->c_state ?? '-' }}
                        </td>

                        <td class="border-bottom-0">

                            <span class="badge {{ $customer->c_status == 'Y' ? 'bg-success' : 'bg-danger' }}">

                                {{ $customer->c_status == 'Y' ? 'Active' : 'Inactive' }}

                            </span>

                        </td>

                        @canany(['customers.edit','customers.delete'])

                        <td class="border-bottom-0">

                            @can('customers.edit')

                            <a href="{{ route('admin.customers.edit',$customer) }}" class="btn btn-sm btn-primary">

                                <i class="ti ti-edit"></i>
                                Edit

                            </a>

                            @endcan


                            @can('customers.delete')

                            <form action="{{ route('admin.customers.destroy',$customer) }}" method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this customer?')">

                                    <i class="ti ti-trash"></i>
                                    Delete

                                </button>

                            </form>

                            @endcan

                        </td>

                        @endcanany

                    </tr>

                    @empty

                    <tr>

                        <td colspan="9" class="text-center py-4">

                            <div class="text-muted">

                                <i class="ti ti-users fs-1 d-block mb-2"></i>

                                No customers found.

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-4">

            {{ $customers->links() }}

        </div>

    </div>

</div>

@endsection
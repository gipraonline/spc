@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light-info border-0 overflow-hidden position-relative">
            <div class="card-body py-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title fw-semibold mb-0 text-dark">KYC Submissions</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item text-muted" aria-current="page">Review employee KYC records
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="mt-3 mt-md-0 d-flex gap-2">
                        @can('kyc-submissions.view')
                        <a href="{{ route('admin.kyc.index') }}"
                            class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                        <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}"
                            class="btn {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
                        <a href="{{ route('admin.kyc.index', ['status' => 'approved']) }}"
                            class="btn {{ $status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">Approved</a>
                        <a href="{{ route('admin.kyc.index', ['status' => 'rejected']) }}"
                            class="btn {{ $status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Rejected</a>
                        @endcan
                        <!-- Employee Name Filter (button style trigger) -->
                        <div class="search-wrap">
                            <i class="bi bi-search"></i>

                            <input type="text" id="employee_search" name="employee_search"
                                value="{{ request('employee_search') }}" placeholder="Search Name / Code">
                        </div>
                        <!-- Export -->
                        @can('kyc-submissions.export')
                        <a href="{{ route('admin.kyc.export', request()->query()) }}" class="btn btn-success">
                            Export Excel
                        </a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">#</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Employee</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Bank Details</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Document</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Submitted On</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-center">Status</h6>
                                </th>
                                @canany(['kyc-submissions.approve', 'kyc-submissions.reject'])
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-end">Actions</h6>
                                </th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $sub)
                            <tr>
                                <td class="border-bottom-0">
                                    <span class="fw-semibold">
                                        {{ $submissions->firstItem() + $loop->index }}
                                    </span>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">{{ $sub->employee->c_employee_name ?? 'Unknown' }}</h6>
                                    <span class="fs-2 text-muted">{{ $sub->employee->c_employee_code ?? 'N/A' }}</span>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="fw-bold d-block text-dark">{{ $sub->bank_name }} -
                                        {{ $sub->bank_branch }}</span>
                                    <span class="fs-2 text-muted d-block">A/C: {{ $sub->account_number }}</span>
                                    <span class="badge bg-light text-dark border mt-1">IFSC:
                                        {{ $sub->ifsc_code }}</span>
                                </td>
                                <td class="border-bottom-0">
                                    @can('kyc-submissions.view')
                                    <a href="{{ route('admin.kyc.show', $sub->id) }}"
                                        class="btn btn-sm btn-outline-info rounded-pill">
                                        <i class="ti ti-eye fs-4"></i> View
                                    </a>
                                    @endcan
                                </td>
                                <td class="border-bottom-0">
                                    <span class="text-muted fs-3">{{ $sub->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="border-bottom-0 text-center">
                                    @php
                                    $badgeClass = [
                                    'pending' => 'bg-warning',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger'
                                    ][$sub->status] ?? 'bg-secondary';
                                    @endphp
                                    <span
                                        class="badge {{ $badgeClass }} rounded-pill font-medium px-3 fs-2 text-uppercase">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                @canany(['kyc-submissions.approve', 'kyc-submissions.reject'])
                                <td class="border-bottom-0 text-end">

                                    @if($sub->status === 'pending')

                                    <div class="d-flex gap-2 justify-content-end">
                                        @can('kyc-submissions.approve')
                                        <form action="{{ route('admin.kyc.approve', $sub->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success shadow-none">
                                                <i class="ti ti-check"></i> Approve
                                            </button>
                                        </form>
                                        @endcan
                                        @can('kyc-submissions.reject')
                                        <form action="{{ route('admin.kyc.reject', $sub->id) }}" method="POST"
                                            onsubmit="return confirm('Reject this KYC?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger shadow-none">
                                                <i class="ti ti-x"></i> Reject
                                            </button>
                                        </form>
                                        @endcan

                                    </div>

                                    @else

                                    <span class="text-muted fs-2">
                                        No further actions
                                    </span>

                                    @endif

                                </td>
                                @endcanany
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <h6 class="text-muted fw-semibold">No KYC submissions found</h6>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!------------------------------------------------------------------------>
<!--------------------Dynamic Search by employee name or code-------------->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    let timer;

    const searchInput = document.getElementById('employee_search');

    if (searchInput) {

        searchInput.addEventListener('keyup', function() {

            clearTimeout(timer);

            let search = this.value;

            timer = setTimeout(() => {

                let url = new URL(window.location.href);

                if (search.length > 0) {
                    url.searchParams.set('employee_search', search);
                } else {
                    url.searchParams.delete('employee_search');
                }

                window.location.href = url.toString();

            }, 500);

        });

    }

});
</script>
@endpush
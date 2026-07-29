@extends('layouts.app')
@section('content')
<style>
:root {
    --primary-red: #ef4444;
    --accent-red: #dc2626;
    --soft-bg: #f8fafc;
    --border-color: #e2e8f0;
    --valid-bg: rgba(57, 181, 74, 0.05);
    --error-bg: rgba(239, 68, 68, 0.05);
}

.draft-container {
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
    background: #fff;
    overflow: hidden;
}

.draft-header {
    background: #fff;
    padding: 1.5rem 2rem;
    border-bottom: 2px solid var(--soft-bg);
    border-top: 4px solid var(--primary-red);
}

.page-title {
    font-weight: 800;
    color: #1e293b;
    letter-spacing: -0.5px;
}

.metric-bar {
    background: #fdfdfe;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    align-items: center;
}

.metric-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.metric-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.5px;
}

.metric-value {
    font-size: 1.25rem;
    font-weight: 800;
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.table-responsive-custom {
    margin: 1.5rem 0;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    overflow: hidden;
}

.table-custom {
    margin-bottom: 0;
}

.table-custom thead {
    background: #f8fafc;
}

.table-custom th {
    font-size: 0.8rem;
    text-transform: uppercase;
    font-weight: 700;
    color: #475569;
    padding: 15px 20px;
    border-bottom: 2px solid #e2e8f0;
}

.table-custom td {
    padding: 14px 20px;
    font-size: 0.9rem;
    color: #334155;
    vertical-align: middle;
}

.row-valid {
    background-color: var(--valid-bg) !important;
    border-left: 4px solid #13deb9;
}

.row-error {
    background-color: var(--error-bg) !important;
    border-left: 4px solid #ef4444;
}

.badge-pill-custom {
    padding: 5px 12px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.2px;
}

.btn-confirm {
    background: var(--primary-red);
    border: none;
    color: #fff;
    font-weight: 700;
    padding: 10px 24px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
    transition: all 0.3s ease;
}

.btn-confirm:hover {
    background: var(--accent-red);
    transform: translateY(-2px);
}
</style>
<div class="card draft-container mb-4">
    <div class="draft-header d-flex justify-content-between align-items-center">
        <h5 class="page-title mb-0">Return Drafts Preview</h5>
        @can('return-upload.upload')
        <a href="{{ route('admin.returns.bulk-upload') }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="ti ti-upload me-1"></i> New Return Upload
        </a>
        @endcan
    </div>

    <div class="card-body p-4">
        @if(session('info'))
        <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 10px;">{{ session('info') }}</div>
        @endif
        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">{{ session('success') }}
        </div>
        @endif

        {{-- Metric Header --}}
        <div class="metric-bar mb-4">
            <div class="metric-item">
                <span class="metric-label">Total Drafts</span>
                <span class="metric-value text-dark">{{ $counts['total'] }}</span>
            </div>
            <div class="vr mx-2 opacity-50"></div>
            <div class="metric-item">
                <span class="metric-label">Valid Rows</span>
                <span class="metric-value text-success">{{ $counts['valid'] }}</span>
            </div>
            <div class="vr mx-2 opacity-50"></div>
            <div class="metric-item">
                <span class="metric-label">Error Rows</span>
                <span class="metric-value text-danger">{{ $counts['error'] }}</span>
            </div>
            @if($counts['pending'] > 0)
            <div class="vr mx-2 opacity-50"></div>
            <div class="metric-item">
                <span class="metric-label">Processing</span>
                <span class="metric-value text-warning">{{ $counts['pending'] }}</span>
            </div>
            @endif
            <div class="ms-auto d-flex align-items-center gap-2">
                <label for="per_page" class="form-label mb-0 small fw-bold text-muted">Show Rows:</label>
                <select name="per_page" id="per_page" class="form-select form-select-sm border-0 bg-light"
                    style="width: 80px;"
                    onchange="window.location.href = '{{ route('admin.returns.drafts') }}?per_page=' + this.value">
                    @foreach([10, 20, 50, 100, 200] as $val)
                    <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if($counts['pending'] > 0)
        <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center" style="border-radius: 10px;">
            <div class="spinner-border spinner-border-sm me-3" role="status"></div>
            <span class="fw-bold">Still processing {{ $counts['pending'] }} row(s). Refresh this page in a
                moment.</span>
        </div>
        @endif

        {{-- Action Buttons --}}
        @if($counts['total'] > 0)
        <div class="action-bar mb-4">
            <div class="d-flex gap-2">
                @can('return-drafts.confirm')
                @if($counts['valid'] > 0)

                <form action="{{ route('admin.returns.confirm-drafts') }}" method="POST"
                    onsubmit="return confirm('Import {{ $counts['valid'] }} valid return(s)?')">
                    @csrf
                    <button type="submit" class="btn btn-confirm">
                        <i class="ti ti-check-double me-1"></i> Confirm {{ $counts['valid'] }} Valid Returns
                    </button>
                </form>
                @endif
                @endcan
                @can('return-drafts.cancel')
                <form action="{{ route('admin.returns.cancel-drafts') }}" method="POST"
                    onsubmit="return confirm('Cancel and delete all return drafts?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger px-4 fw-bold" style="border-radius: 10px;">
                        <i class="ti ti-trash me-1"></i> Cancel All
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @endif

        {{-- Drafts Table --}}
        <div class="table-responsive table-responsive-custom">
            <table class="table table-custom align-middle text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Store Code</th>
                        <th>Bill No</th>
                        <th>Item Code</th>
                        <th>Return Qty</th>
                        <th>Status</th>
                        <th>Notes/Errors</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drafts as $i => $draft)
                    @php
                    $rowHighlight = match ($draft->c_status) {
                    'valid' => 'row-valid',
                    'error' => 'row-error',
                    default => '',
                    };
                    @endphp
                    <tr class="{{ $rowHighlight }}">
                        <td class="fw-bold text-muted">{{ $drafts->firstItem() + $i }}</td>
                        <td class="fw-semibold">{{ $draft->d_date }}</td>
                        <td><span class="badge bg-light text-dark fw-bold border">{{ $draft->c_store_code }}</span></td>
                        <td>
                            @if($draft->c_billno)
                            <a href="{{ route('admin.returns.draft-bill', $draft->c_billno) }}"
                                class="fw-bold text-danger">
                                {{ $draft->c_billno }}
                            </a>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $draft->c_item_code }}</td>

                        <td class="fw-bold">
                            @if(str_contains($draft->c_validation_message, 'Quantity is required'))
                            0
                            @else
                            {{ $draft->n_quantity }}
                            @endif
                        </td>
                        <td>
                            @if($draft->c_status === 'valid')
                            <span class="badge badge-pill-custom bg-success shadow-sm">VALID</span>
                            @elseif($draft->c_status === 'error')
                            <span class="badge badge-pill-custom bg-danger shadow-sm">ERROR</span>
                            @else
                            <span class="badge badge-pill-custom bg-secondary shadow-sm">PENDING</span>
                            @endif
                        </td>
                        <td class="text-danger fw-bold small" style="max-width: 250px; white-space: normal;">
                            {{ $draft->c_validation_message ?: '' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="ti ti-database-off fs-8 d-block mb-2"></i>
                            No return draft records found. Upload a file to begin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $drafts->links() }}
        </div>
    </div>
</div>
@endsection
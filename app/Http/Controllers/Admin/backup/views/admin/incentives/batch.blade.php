@extends('layouts.app')

@section('content')
<style>
:root {
    --primary: #15386e;
    --primary-glow: rgba(16, 185, 129, 0.3);
    --secondary: #6366f1;
    --dark-surface: #0f172a;
    --light-surface: rgba(255, 255, 255, 0.9);
    --glass-border: rgba(255, 255, 255, 0.2);
    --card-radius: 32px;
}




/* Premium Glass Card */
.premium-card {
    background: var(--light-surface);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: var(--card-radius);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.premium-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.08);
}

/* Hero Header */
.hero-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    padding: 1rem 2.5rem;
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid rgba(0, 0, 0, 0.03);
}



/* Dynamic Data Badge */
.data-badge-premium {
    background: white;
    border: 1px solid #e2e8f0;
    padding: 12px 24px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
}

.data-badge-premium:hover {
    border-color: var(--primary);
    transform: scale(1.02);
}

.data-badge-premium i {
    font-size: 1.5rem;
    color: var(--primary);
    background: rgba(16, 185, 129, 0.1);
    padding: 10px;
    border-radius: 12px;
}

/* Launch Button */
.btn-premium-launch {
    background: linear-gradient(135deg, #15386e 0%, #15386e 100%);
    color: white;
    border: none;
    padding: 14px 32px;
    border-radius: 18px;
    font-weight: 800;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 12px 24px -6px var(--primary-glow);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.btn-premium-launch:hover:not(:disabled) {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 35px -8px var(--primary-glow);
    color: white;
}

.btn-premium-launch:disabled {
    background: #e2e8f0;
    color: #94a3b8;
    box-shadow: none;
    cursor: not-allowed;
}

/* Running State Animation */
.running-alert {
    background: #fffbeb;
    border: 2px solid #fef3c7;
    border-radius: 24px;
    padding: 2rem;
    margin: 1.5rem 2.5rem;
    position: relative;
    overflow: hidden;
}

.running-alert::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 200%;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, rgba(251, 191, 36, 0.05) 50%, transparent 100%);
    animation: scan 3s infinite linear;
}

@keyframes scan {
    0% {
        transform: translateX(-50%);
    }

    100% {
        transform: translateX(50%);
    }
}

/* Premium Table */
.table-premium {
    margin-top: 1rem;
}

.table-premium thead th {

    background: transparent;
    color: #94a3b8;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 2px;
    padding: 2rem 1.5rem;
    border: none;
}

.table-premium tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.table-premium tbody tr:hover {
    background: rgba(16, 185, 129, 0.02);

}

.table-premium td {
    padding: 1.75rem 1.5rem;
    vertical-align: middle;
}

/* Status Badges */
.premium-status {
    padding: 8px 16px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-done {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.status-err {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.status-load {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

/* Custom Scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 6px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}

.batch-id-mono {

    font-weight: 700;
    color: var(--dark-surface);
    background: #f8fafc;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.85rem;
}

.sales-stat {
    font-size: 1.25rem;
    font-weight: 300;
    color: #334155;
}

.sales-stat b {
    font-weight: 800;
    color: var(--dark-surface);
}

.suggest-box {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #ddd;
    max-height: 200px;
    overflow-y: auto;
    z-index: 9999;
    display: none;
}

.suggest-item {
    padding: 8px 12px;
    cursor: pointer;
}

.suggest-item:hover {
    background-color: #f1f1f1;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card premium-card w-100 position-relative overflow-hidden mb-5 border-0">
            <!-- Hero Header Section -->
            <div class="hero-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                    <div>
                        <span
                            class="badge bg-soft-primary text-primary px-3 py-1 rounded-pill mb-2 fs-1 fw-bold text-uppercase tracking-widest">Administrator</span>
                        <h2 class="card-title fw-black mb-1 lh-sm display-6 text-dark">Batch Incentive Processing</h2>
                        <p class="text-muted mb-0 fs-3 fw-medium">Calculate and process pending employee sales
                            incentives in bulk.</p>
                    </div>

                    <div style="flex-wrap: wrap;" class="d-flex align-items-center gap-3">
                        <div class="data-badge-premium">
                            <i class="ti ti-clock-hour-4"></i>
                            <div>
                                <span class="d-block fs-1 text-muted text-uppercase fw-bold"
                                    style="line-height: 1;">Pending Stack</span>
                                <span class="fs-4 fw-black text-dark">{{ $pendingCount }} Sales Records</span>
                            </div>
                        </div>
                        @can('incentive-batches.process-batch')
                        <form action="{{ route('admin.incentives.process-batch') }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to process {{ $pendingCount }} pending records?');">
                            @csrf
                            <button type="submit" @if($isRunning || $pendingCount==0) disabled @endif
                                class="btn btn-premium-launch">
                                <i class="ti ti-player-play-filled me-2 fs-4"></i>
                                Process Batch
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <div class="card-body p-4">
                <div class="p-1">
                    @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show mb-4"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center">
                                <i class="ti ti-circle-check fs-5"></i>
                            </div>
                            <span class="fw-bold">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show mb-4"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center">
                                <i class="ti ti-alert-circle fs-5"></i>
                            </div>
                            <span class="fw-bold">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                </div>

                @if ($isRunning)
                <div class="running-alert shadow-sm border-0 d-flex align-items-center" role="alert">
                    <div class="spinner-grow text-warning me-4" style="width: 2.5rem; height: 2.5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>
                        <h5 class="fw-black text-dark mb-1">Batch Operation in Progress</h5>
                        <span class="text-muted fs-3">A process is currently calculating incentives. Please do not
                            refresh this page.</span>
                    </div>
                </div>
                @endif

                <!-- Batch ID,From,To,Status Filters -->
                <!-- FILTER SECTION -->
                <div class="filter-card-wrapper mb-4">

                    <div class="filter-header-sub">
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">

                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>

                            </svg>
                        </div>

                        <span>Refine Search</span>
                    </div>

                    <form action="{{ route('admin.incentives.batch') }}" method="GET">



                        <div class="row g-3">


                            <!-- BATCH ID -->
                            <div class="col-md-2">
                                <div class="custom-filter-group position-relative">

                                    <label for="batch_id">Batch ID</label>

                                    <input type="text" id="batch_id" name="batch_id" class="form-control styled-select"
                                        placeholder="Enter Batch ID" autocomplete="off"
                                        value="{{ request('batch_id') }}">

                                    <div id="suggestions" class="suggest-box"></div>

                                </div>
                            </div>
                            <!-- FROM DATE -->
                            <div class="col-md-3">
                                <div class="custom-filter-group">

                                    <label for="from_date">
                                        From Date
                                    </label>

                                    <input type="date" id="from_date" name="from_date"
                                        class="form-control styled-select" value="{{ request('from_date') }}">

                                </div>
                            </div>

                            <!-- TO DATE -->
                            <div class="col-md-3">
                                <div class="custom-filter-group">

                                    <label for="to_date">
                                        To Date
                                    </label>

                                    <input type="date" id="to_date" name="to_date" class="form-control styled-select"
                                        value="{{ request('to_date') }}">

                                </div>
                            </div>

                            <!-- STATUS -->
                            <div class="col-md-2">
                                <div class="custom-filter-group">

                                    <label for="status">
                                        Status
                                    </label>

                                    <select id="status" name="status" class="form-select styled-select">
                                        <option value="">Select Status</option>

                                        <option value="running" {{ request('status') == 'running' ? 'selected' : '' }}>
                                            Running
                                        </option>

                                        <option value="completed"
                                            {{ request('status') == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>

                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                                            Failed
                                        </option>
                                    </select>

                                </div>
                            </div>

                            <!-- BUTTON -->
                            <div class="col-md-2 filter-action-container">

                                <button type="submit" class="btn btn-primary btn-creative-filter w-100">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>

                                    </svg>

                                    Filter
                                </button>

                            </div>

                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-premium text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th class="ps-5">Batch ID</th>
                                <th>Date & Time</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Sales Processed</th>
                                <th class="pe-5">Error Details</th>
                                <th class="pe-5">Error Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                            <tr>
                                <td class="ps-5">
                                    <span class="batch-id-mono">#{{ $batch->batch_id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span
                                            class="fw-bold text-dark fs-3">{{ $batch->created_at->timezone('Asia/Kolkata')->format('M d, Y') }}</span>
                                        <span class="text-muted fs-2">
                                            {{ $batch->created_at->timezone('Asia/Kolkata')->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($batch->status == 'completed')
                                    <span class="premium-status status-done">
                                        <i class="ti ti-circle-check-filled fs-4"></i> Completed
                                    </span>
                                    @elseif($batch->status == 'failed')
                                    <span class="premium-status status-err">
                                        <i class="ti ti-circle-x-filled fs-4"></i> Failed
                                    </span>
                                    @else
                                    <span class="premium-status status-load d-inline-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm me-2"
                                            style="width: 12px; height: 12px;" role="status"></div>
                                        Running
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="sales-stat"><b>{{ $batch->total_sales_processed }}</b> Sales</span>
                                </td>
                                <td class="pe-5 text-muted" style="max-width: 250px;">
                                    @if($batch->error_message)
                                    <div class="d-flex align-items-center bg-light px-3 py-2 rounded-3">
                                        <i class="ti ti-terminal-2 text-danger me-2 fs-4"></i>
                                        <div class="text-truncate fs-2 fw-medium" title="{{ $batch->error_message }}">
                                            {{ $batch->error_message }}
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted fs-2 italic">—</span>
                                    @endif
                                </td>

                                <td class="text-center pe-5">
                                    @if($batch->error_count > 0)
                                    <span class="premium-status status-err">
                                        <i class="ti ti-alert-circle me-1"></i>
                                        {{ $batch->error_count }}
                                    </span>
                                    @else
                                    <span class="premium-status status-done">
                                        <i class="ti ti-check me-1"></i>
                                        0
                                    </span>
                                    @endif
                                </td>


                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-5">
                                        <div class="bg-light rounded-circle p-4 d-inline-flex mb-4">
                                            <i class="ti ti-clipboard-off fs-10 text-muted"></i>
                                        </div>
                                        <h5 class="text-dark fw-bold mb-1">No incentive batches found.</h5>
                                        <p class="text-muted fs-3">No incentive batches have been processed yet.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($batches->hasPages())
                <div class="p-4 d-flex justify-content-center border-top">
                    {{ $batches->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Badge for Layout.app consistency */
.bg-soft-primary {
    background-color: rgba(99, 102, 241, 0.1);
}
</style>

@push('scripts')


<script>
let timer;

document.getElementById('batch_id').addEventListener('keyup', function() {

    clearTimeout(timer);

    timer = setTimeout(() => {

        this.form.submit();

    }, 1800);

});
</script>
@endpush
@endsection

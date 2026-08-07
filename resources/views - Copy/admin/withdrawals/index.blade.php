@extends('layouts.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #2dd4bf 100%);
    --glass-bg: rgba(255, 255, 255, 0.7);
    --glass-border: rgba(255, 255, 255, 0.4);
    --premium-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
    --accent-success: #10b981;
    --accent-warning: #f59e0b;
    --accent-danger: #ef4444;
    --text-dark: #1e293b;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f8fafc;
    background-image:
        radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.05) 0px, transparent 50%);
    min-height: 100vh;
}

h1,
h2,
h3,
h4,
h5,
h6 {
    font-family: 'Outfit', sans-serif;
}

/* Premium Glass Cards */
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    box-shadow: var(--premium-shadow);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.glass-card:hover {
    transform: translateY(-5px);
    border-color: rgba(99, 102, 241, 0.3);
}

/* Payout Header Styling */
.payout-header {
    background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
    position: relative;
    overflow: hidden;
}

.payout-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
    z-index: 0;
}

/* Button Premium Styling */
.btn-premium-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.btn-premium-primary:hover {
    transform: scale(1.03) translateY(-2px);
    box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5);
    color: white;
}

.btn-premium-success {
    background: white;
    color: var(--accent-success);
    border: 2px solid var(--accent-success);
    transition: all 0.3s ease;
}

.btn-premium-success:hover {
    background: var(--accent-success);
    color: white;
    transform: translateY(-2px);
}

/* Modern Table Design */
.premium-table-container {
    border-radius: 0px;
    overflow: hidden;
}

.premium-table thead th {
    background: rgba(248, 250, 252, 0.8);
    border: none;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05rem;
    padding: 1.5rem 1rem;
}

.premium-table tbody tr {
    border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    transition: all 0.2s ease;
}

.premium-table tbody tr:last-child {
    border-bottom: none;
}

.premium-table tbody tr:hover {
    background: rgba(99, 102, 241, 0.02);
}

/* KYC Badges */
.kyc-badge {
    padding: 6px 14px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.kyc-badge-approved {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.kyc-badge-pending {
    background: rgba(245, 158, 11, 0.1);
    color: var(--accent-warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.kyc-badge-rejected {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.kyc-badge-none {
    background: rgba(100, 116, 139, 0.1);
    color: #64748b;
    border: 1px solid rgba(100, 116, 139, 0.2);
}

/* Modal Creative Styling */
#processApprovedModal .modal-content {
    border: none;
    border-radius: 30px;
    overflow: hidden;
    background: linear-gradient(to bottom right, #ffffff, #f9fafb);
}

.modal-hero-header {
    background: var(--primary-gradient);
    padding: 1rem 2rem;
    text-align: center;
    position: relative;
    color: white;
}

.modal-hero-header .bolt-icon-container {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    animation: pulse-icon 2s infinite;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

@keyframes pulse-icon {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
    }

    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
    }

    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
    }
}

.modal-criteria-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
}

.modal-criteria-item:hover {
    border-color: #6366f1;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
}

.modal-footer {
    padding: 1.5rem 2rem 2rem;
}

/* Wallet Balance Highlight */
.balance-pill {
    background: #1e293b;
    color: #f8fafc;
    padding: 4px 12px;
    border-radius: 8px;
    font-weight: 800;
    letter-spacing: 0.5px;
}

/* Floating Animations */
.float-element {
    animation: float 4s ease-in-out infinite;
}

.filter-box {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    padding: 24px;
    border-radius: 20px;
}

.filter-box label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 8px;
    min-height: 20px;
    /* keeps all labels aligned */
}

.filter-box .form-control,
.filter-box .form-select,
.filter-box .btn {
    height: 48px;
    border-radius: 12px;
}

.filter-box .btn {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-10px);
    }

    100% {
        transform: translateY(0px);
    }
}
</style>

<div class="row mb-w">
    <div class="col-12">
        <div class="card glass-card payout-header border-0 overflow-hidden position-relative">
            <div class="card-body py-5 position-relative z-index-1">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-primary me-3 float-element">
                                <i class="ti ti-cash fs-7"></i>
                            </div>
                            <h2 class="fw-black mb-0 text-dark">Payout Review</h2>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item bg-light-primary text-primary px-3 py-1 rounded-pill fw-bold fs-2 d-inline-flex align-items-center"
                                    aria-current="page">
                                    <i class="ti ti-circle-check-filled me-2"></i> Employees eligible for incentive
                                    settlement (KYC Approved)
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-6 mt-4 mt-lg-0">
                        <div class="d-flex align-items-center justify-content-lg-end gap-3 flex-wrap">
                            @can('payouts.export')
                            <a href="{{ route('admin.withdrawals.export') }}"
                                class="btn btn-premium-success rounded-pill fw-bold px-4 py-2 fs-3">
                                <i class="ti ti-file-spreadsheet fs-6 me-2"></i> Export to Excel
                            </a>
                            @endcan
                            @can('payouts.approve')
                            @if(request()->filled('payout_type'))
                            <button type="button"
                                class="btn btn-premium-primary rounded-pill fw-bold px-4 py-2 fs-3 overflow-hidden"
                                data-bs-toggle="modal" data-bs-target="#processApprovedModal">
                                <i class="ti ti-bolt fs-6 me-2"></i> Process Approved Payouts
                            </button>
                            @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card glass-card w-100 position-relative overflow-hidden mb-5">
    <div class="card-body p-4">
        <div class="p-4 border-bottom bg-white bg-opacity-50">
            @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-circle-check fs-6 me-3"></i>
                    <span class="fw-bold">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-triangle fs-6 me-3"></i>
                    <span class="fw-bold">{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>

        <!-- Filters -->
        <div class="filter-box mb-4">
            <form method="GET" action="{{ route('admin.withdrawals.index') }}">

                <!-- First Row -->
                <div class="row">
                    <div class="col-md-3">
                        <label>Payout Type <span class="text-danger">*</span></label>
                        <select name="payout_type" id="payout_type" class="form-select" required>
                            <option value="">Select Payout Type</option>
                            <option value="daily" {{ request('payout_type') == 'daily' ? 'selected' : '' }}>
                                Daily Payout
                            </option>
                            <option value="weekly" {{ request('payout_type') == 'weekly' ? 'selected' : '' }}>
                                Weekly Payout
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Designation Group</label>
                        <input type="text" id="designation_group" class="form-control" readonly
                            placeholder="Select Payout Type First">
                    </div>

                    <div class="col-md-3">
                        <label>Payout Date <span class="text-danger">*</span></label>
                        <input type="date" name="payout_date" id="payout_date" class="form-control"
                            value="{{ request('payout_date') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="invisible">Search</label>
                        <button type="submit" id="searchBtn" class="btn btn-premium-primary w-100">
                            <i class="ti ti-search me-2"></i>
                            Search Employees
                        </button>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row mt-3">
                    @if(request()->filled('payout_type'))
                    <div class="mb-4">
                        <div class="d-flex gap-2">
                            @can('payouts.export')
                            <a href="{{ route('admin.withdrawals.export', request()->query()) }}"
                                class="btn btn-filter bg-success text-white">
                                <i class="ti ti-file-export me-1"></i> Export to Excel
                            </a>
                            @endcan

                            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>
                        </div>

                        <div class="alert alert-warning py-2 mt-2 mb-0">
                            <i class="ti ti-alert-triangle me-1"></i>
                            <strong>Note:</strong> This export contains only the filtered search results.
                        </div>
                    </div>
                    @endif
                </div>

            </form>
        </div>

        <div class="table-responsive premium-table-container">
            <table class="table premium-table text-nowrap mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Employee Details</th>
                        <th class="text-center">Wallet Balance</th>
                        <th>Bank Details & KYC Status</th>
                        <th class="text-center pe-4">Verification</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wallets as $wallet)
                    <tr>
                        <td class="ps-4 py-4">
                            <div class="d-flex align-items-center">
                                <div class="ms-0">
                                    <h6 class="fw-bold mb-1 text-dark fs-4">
                                        {{ $wallet->employee->c_employee_name ?? 'Unknown' }}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="badge bg-indigo-subtle text-indigo fw-bold fs-1 uppercase px-2 py-1 rounded-1">
                                            {{ $wallet->employee->c_employee_code ?? 'CODE' }}
                                        </span>
                                        <span
                                            class="badge bg-success-subtle text-success fw-bold fs-1 uppercase px-2 py-1 rounded-1">
                                            {{ $wallet->employee->store->c_store_code ?? 'STORE' }}
                                        </span>
                                        <span class="text-muted fs-2">•
                                            {{ $wallet->employee->designation->c_designation ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex">
                                <span class="balance-pill fs-4">₹{{ number_format($wallet->n_balance, 2) }}</span>
                            </div>
                        </td>
                        <td>
                            @if($wallet->employee->kycSubmission)
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-building-bank text-indigo fs-4 me-2"></i>
                                    <span
                                        class="fw-bold fs-3 text-dark tracking-wider">{{ $wallet->employee->kycSubmission->account_number }}</span>
                                </div>
                                <div class="fs-1 text-muted uppercase fw-bold">
                                    {{ $wallet->employee->kycSubmission->ifsc_code }} <span class="mx-1">•</span>
                                    {{ $wallet->employee->kycSubmission->bank_name }}
                                </div>
                            </div>
                            @else
                            <div class="d-flex align-items-center">
                                <i class="ti ti-alert-circle text-danger me-2"></i>
                                <span class="text-danger fs-2 fw-bold italic">No Bank Details Provided</span>
                            </div>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            @php
                            // KYC STATUS
                            $kycStatus = $wallet->employee->kycSubmission->status ?? 'none';

                            $kycClass = [
                            'approved' => 'kyc-badge-approved',
                            'pending' => 'kyc-badge-pending',
                            'rejected' => 'kyc-badge-rejected',
                            'none' => 'kyc-badge-none'
                            ][$kycStatus] ?? 'kyc-badge-none';

                            $kycIcon = [
                            'approved' => 'ti-circle-check-filled',
                            'pending' => 'ti-clock',
                            'rejected' => 'ti-circle-x',
                            'none' => 'ti-user-off'
                            ][$kycStatus] ?? 'ti-question-mark';

                            $kycLabel = [
                            'approved' => 'KYC Verified',
                            'pending' => 'KYC Pending',
                            'rejected' => 'KYC Rejected',
                            'none' => 'No KYC'
                            ][$kycStatus] ?? 'No KYC';


                            // ------------------------
                            // RETURN STATUS
                            // ------------------------
                            $verificationStatus = $wallet->employee?->verification?->status ?? 'none';

                            if ($wallet->return_requested ?? false) {
                            $verificationStatus = 'return_pending';
                            }

                            $verificationClass = [
                            'return_pending' => 'verification-badge-return-pending',
                            'none' => ''
                            ][$verificationStatus] ?? 'verification-badge-none';

                            $verificationIcon = [
                            'approved' => 'ti-circle-check-filled',
                            'pending' => 'ti-clock',
                            'rejected' => 'ti-circle-x',
                            'return_pending' => 'ti-arrow-back-up',
                            'none' => ''
                            ][$verificationStatus] ?? 'ti-question-mark';

                            $verificationLabel = [
                            'approved' => 'Verified',
                            'pending' => 'Pending',
                            'rejected' => 'Rejected',
                            'return_pending' => 'Return Pending',
                            'none' => ''
                            ][$verificationStatus] ?? 'Unknown';

                            @endphp

                            <div class="d-flex flex-column gap-1 align-items-center">

                                <!-- KYC Badge -->
                                <span class="kyc-badge {{ $kycClass }}">
                                    <i class="ti {{ $kycIcon }} fs-4"></i>
                                    {{ $kycLabel }}
                                </span>

                                <!-- Verification Badge -->
                                <span class="verification-badge {{ $verificationClass }}">
                                    <i class="ti {{ $verificationIcon }} fs-4"></i>
                                    {{ $verificationLabel }}
                                </span>

                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-5">
                                <div class="p-4 rounded-circle bg-light d-inline-flex mb-4">
                                    <i class="ti ti-receipt-off fs-10 text-muted"></i>
                                </div>
                                <h5 class="text-dark fw-bold">No eligible payout candidates found.</h5>
                                <p class="text-muted fs-3">KYC Approved Employees will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-column align-items-center py-4">

            {{ $wallets->links() }}
        </div>

    </div>
</div>

<!-- Process Approved Modal -->
<div class="modal fade" id="processApprovedModal" tabindex="-1" aria-labelledby="processApprovedModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow-2xl">
            <div class="modal-hero-header">
                <div class="bolt-icon-container">
                    <i class="ti ti-bolt"></i>
                </div>
                <h3 class="modal-title fw-black mb-1" id="processApprovedModalLabel">Process Approved Payouts</h3>
                <p class="text-white text-opacity-75 fs-3 mb-0">Automated settlement system</p>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.withdrawals.process-approved') }}" method="POST">
                @csrf

                <input type="hidden" name="payout_type" value="{{ request('payout_type') }}">
                <div style="padding-bottom: 0 !important;" class="modal-body p-3">
                    <h5 class="fw-bold text-dark mb-4">Payout Validation Criteria</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="modal-criteria-item d-flex align-items-center">
                                <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success me-3">
                                    <i class="ti ti-user-check fs-6"></i>
                                </div>
                                <div>
                                    <div class="fs-1 text-muted text-uppercase fw-bold">KYC Status</div>
                                    <div class="fs-3 fw-bold text-dark">Approved Profile</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modal-criteria-item d-flex align-items-center">
                                <div class="p-2 rounded-3 bg-indigo bg-opacity-10 text-indigo me-3">
                                    <i class="ti ti-wallet fs-6"></i>
                                </div>
                                <div>
                                    <div class="fs-1 text-muted text-uppercase fw-bold">Minimum Threshold</div>
                                    <div class="fs-3 fw-bold text-dark">Balance > 0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning border-2 border-warning bg-light-warning rounded-4 p-4 mb-0">
                        <div class="d-flex">
                            <i class="ti ti-alert-triangle fs-8 text-warning me-3"></i>
                            <div>
                                <h6 class="fw-black text-warning mb-2 uppercase tracking-wide">Critical Warning</h6>
                                <p class="mb-0 fs-3 text-dark text-opacity-75">This action will deduct balances and
                                    instantly mark transactions as completed. <span class="fw-bold">This action cannot
                                        be undone.</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex gap-3">
                    <button type="button" class="btn btn-light rounded-pill px-5 py-2 fw-bold text-muted"
                        data-bs-dismiss="modal">Dismiss</button>
                    <button type="submit"
                        class="btn btn-premium-primary rounded-pill px-5 py-2 fw-bold shadow-lg">Confirm & Process
                        Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dynamically update the designation group based
on the selected payout type and preserve filter values after page refresh. -->

<script>
document.addEventListener('DOMContentLoaded', function() {

    const payoutType = document.getElementById('payout_type');
    const designationGroup = document.getElementById('designation_group');
    const payoutDate = document.getElementById('payout_date');
    const searchBtn = document.getElementById('searchBtn');

    function updateDesignationGroup() {
        if (payoutType.value === 'daily') {
            designationGroup.value = 'CA, CSA, SM, CLUSTER';
        } else if (payoutType.value === 'weekly') {
            designationGroup.value = 'BM, DC, HO,OPERATIONS';
        } else {
            designationGroup.value = '';
            designationGroup.placeholder = 'Select Payout Type First';
        }
    }

    payoutType.addEventListener('change', function() {
        updateDesignationGroup();
        toggleSearchButton();
    });

    payoutDate.addEventListener('change', toggleSearchButton);

    // For page refresh with existing filters
    updateDesignationGroup();
    toggleSearchButton();
});
</script>

<style>
/* Helper classes for colors if not in bootstrap theme */
.bg-indigo-subtle {
    background-color: rgba(99, 102, 241, 0.15);
}

.text-indigo {
    color: #6366f1;
}

.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.15);
}

.text-success {
    color: #10b981;
}

.bg-indigo {
    background-color: #6366f1;
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.verification-badge {
    padding: 6px 14px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Return Pending (ORANGE - correct UX) */
.verification-badge-return-pending {
    background: rgba(220, 53, 69, 0.15);
    color: #dc3545;
    border: 1px solid rgba(242, 167, 174, 0.4);
}
</style>
@endsection
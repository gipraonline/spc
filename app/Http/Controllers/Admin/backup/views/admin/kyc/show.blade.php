@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card overflow-hidden rounded-4 border-0 shadow-sm mb-4">
            <div class="card-header bg-light-primary border-bottom border-primary py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="ti ti-user-check text-primary fs-5 me-2"></i> KYC Review: {{ $submission->employee->c_employee_name ?? 'Unknown' }}
                    </h5>
                    <span class="fs-2 text-muted ms-4 ps-2">Code: {{ $submission->employee->c_employee_code ?? 'N/A' }}</span>
                </div>
                <div>
                    <a href="{{ route('admin.kyc.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <div class="row mb-5">
                    <div class="col-md-6 mb-4 mb-md-0 border-end border-light pe-md-4">
                        <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Bank Details</h6>
                        <table class="table table-borderless table-sm fs-3 mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-semibold" width="130">Bank Name</td>
                                    <td class="fw-bold text-dark">{{ $submission->bank_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Branch</td>
                                    <td class="fw-bold text-dark">{{ $submission->bank_branch }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Acct. Number</td>
                                    <td class="fw-bold text-dark fs-4 tracking-wider">{{ $submission->account_number }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">IFSC Code</td>
                                    <td class="fw-bold text-dark text-uppercase">{{ $submission->ifsc_code }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Submitted</td>
                                    <td class="fw-bold text-dark">{{ $submission->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-6 ps-md-4">
                        <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Status & Actions</h6>
                        
                        <div class="mb-4">
                            <span class="text-muted d-block fs-2 mb-1">Current Status</span>
                            @if($submission->status === 'pending')
                                <span class="badge bg-warning rounded-pill px-4 py-2 fs-3 text-uppercase shadow-sm">Pending Review</span>
                            @elseif($submission->status === 'approved')
                                <span class="badge bg-success rounded-pill px-4 py-2 fs-3 text-uppercase shadow-sm">
                                    <i class="ti ti-check"></i> Approved
                                </span>
                            @else
                                <span class="badge bg-danger rounded-pill px-4 py-2 fs-3 text-uppercase shadow-sm">
                                    <i class="ti ti-x"></i> Rejected
                                </span>
                            @endif
                        </div>

                        @if($submission->status === 'pending')
                            <div class="d-flex gap-3">
                                <form action="{{ route('admin.kyc.approve', $submission->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 py-2 fs-3 fw-bold shadow-sm">
                                        <i class="ti ti-check fs-5 me-1"></i> Approve KYC
                                    </button>
                                </form>
                                <form action="{{ route('admin.kyc.reject', $submission->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to reject this KYC document?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100 py-2 fs-3 fw-bold shadow-sm">
                                        <i class="ti ti-x fs-5 me-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-secondary border-0 bg-light-secondary rounded-3 fs-3">
                                <i class="ti ti-info-circle me-1"></i> This submission has been {{ $submission->status }} and cannot be modified.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="document-preview-container bg-light rounded-4 border border-light p-2 overflow-hidden" style="height: 600px;">
                    @php
                        $ext = strtolower(pathinfo($submission->document_path, PATHINFO_EXTENSION));
                        $docUrl = route('admin.kyc.document', $submission->id);
                    @endphp

                    @if($ext === 'pdf')
                        <iframe src="{{ $docUrl }}" class="w-100 h-100 border-0 rounded-3"></iframe>
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center overflow-auto rounded-3 bg-white">
                            <img src="{{ $docUrl }}" alt="KYC Document" class="img-fluid" style="object-fit: contain; max-height: 100%;">
                        </div>
                    @endif
                </div>

                <div class="text-center mt-3">
                    <a href="{{ $docUrl }}" target="_blank" class="btn btn-primary rounded-pill px-4">
                        <i class="ti ti-external-link me-2"></i> Open Document in New Tab
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

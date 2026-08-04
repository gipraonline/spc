@extends('layouts.app')

@push('styles')
    <style>
        /* Premium Design Tokens */
        :root {
            --primary-green: #1b3e86;
            --accent-orange: #F7941E;
            --deep-slate: #1e293b;
            --glass-bg: #fdfdfe;
            --input-border: #e2e8f0;
            --border-radius-lg: 18px;
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.04), 0 5px 15px rgba(0, 0, 0, 0.02);
        }

        /* Architectural Layout */
        .premium-form-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            position: relative;
        }

        /* Signature Accent Line */
        .premium-form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-green) 0%, #51cf66 100%);
            z-index: 10;
        }

        .card-header-premium {
            padding: 2.2rem 2.5rem 1.2rem;
            background: #fff;
            border-bottom: none;
        }

        .page-main-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--deep-slate);
            letter-spacing: -0.8px;
        }

        /* Sectional Typography */
        .field-group-title {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--primary-green);
            letter-spacing: 1.5px;
            margin-bottom: 1.4rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .field-group-title::after {
            content: '';
            height: 1px;
            flex: 1;
            background: linear-gradient(90deg, #f1f5f9 0%, transparent 100%);
        }

        /* Modern Inputs */
        .form-label {
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 0.85rem 1.1rem;

            background-color: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            color: var(--deep-slate);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-green);
            background-color: #ffffff;
            box-shadow: 0 8px 20px rgba(57, 181, 74, 0.08);
            transform: translateY(-1px);
        }

        /* Validation Messages */
        .text-danger.fs-2 {
            font-weight: 600;
            font-size: 0.75rem !important;
            padding-left: 4px;
            letter-spacing: 0.2px;
        }

        /* Action Buttons */
        .btn-create-action {
            background: var(--primary-green);
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 800;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(57, 181, 74, 0.15);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-create-action:hover {
            background: #1b3e86;
            box-shadow: 0 12px 25px rgba(57, 181, 74, 0.25);
            transform: translateY(-2px);
        }

        .btn-cancel-action {
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 700;
            border: 2px solid #f1f5f9;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .btn-cancel-action:hover {
            background: #f1f5f9;
            color: #475569;
        }
    </style>

@endpush

@section('content')

<div class="card premium-form-card mb-4">
    <div class="card-header-premium">
        <h5 class="page-main-title mb-0">Add Franchise</h5>
    </div>

    <div class="card-body p-4 p-md-5 pt-md-4">
        <form id="frm_create" method="POST" action="{{ route('admin.franchises.store') }}">
            @csrf

            <!-- Section 1: Record Identity -->
            <div class="field-group-title">
                <i class="ti ti-id-badge-2 fs-5"></i> Identity & Location
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <label for="c_store_code" class="form-label">Franchise Code *</label>
                    <input type="text" id="c_store_code" data-message="Enter valid Store Code" name="c_store_code"
                        value="{{ old('c_store_code') }}" max-length="20" class="form-control mandatory"
                        placeholder="e.g. SPC-001">
                    @error('c_store_code')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-7">
                    <label for="c_store_name" class="form-label">Franchise Name *</label>
                    <input type="text" id="c_store_name" data-message="Please enter Name" name="c_store_name"
                        value="{{ old('c_store_name') }}" maxlength="100" pattern="[A-Za-z0-9\s\-]+"
                        class="form-control mandatory" placeholder="Legal Franchise name">
                    @error('c_store_name')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="c_store_address" class="form-label">Address</label>
                    <input type="text" id="c_store_address" name="c_store_address" value="{{ old('c_store_address') }}"
                        maxlength="255" class="form-control" placeholder="Street, Building, Area...">
                    @error('c_store_address')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Communication -->
            <div class="field-group-title mt-5">
                <i class="ti ti-mail-forward fs-5"></i> Contact & Availability
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <label for="c_store_email" class="form-label">Email</label>
                    <input type="email" id="c_store_email" name="c_store_email" value="{{ old('c_store_email') }}"
                        class="form-control" placeholder="branch@spc.com">
                    @error('c_store_email')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="n_store_phone" class="form-label">Phone</label>
                    <input type="text" id="n_store_phone" name="n_store_phone" value="{{ old('n_store_phone') }}"
                        max-length="10" class="form-control" placeholder="Contact number">
                    @error('n_store_phone')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="c_store_status" class="form-label">Status *</label>
                    <select id="c_store_status" data-message="Please select Status" name="c_store_status"
                        class="form-select mandatory">
                        <option value="">Select Status</option>
                        <option value="Y" {{ old('c_store_status') === 'Y' ? 'selected' : '' }}>Active</option>
                        <option value="N" {{ old('c_store_status') === 'N' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('c_store_status')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Enhanced Action Bar -->
            <div class="pt-4 border-top d-flex gap-3">
                <button type="submit" id="btn_create" class="btn buttonSpc btn-create-action">
                    <i class="ti ti-plus fs-4"></i> Create Franchise
                </button>
                <a href="{{ route('admin.franchises.index') }}" class="btn btn-outline-secondary" style="--bs-btn-padding-y: 15px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

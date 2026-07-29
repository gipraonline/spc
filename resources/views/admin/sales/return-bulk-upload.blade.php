@extends('layouts.app')

@section('content')
<div class="bulk-upload-wrapper">
    <!-- Styled Header Section -->
    <div class="upload-header-card mb-4"
        style="background: #ffffff; padding: 24px 30px; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="header-left">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon-box"
                        style="width: 54px; height: 54px; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; border-radius: 16px;">
                        <i class="ti ti-receipt-refund" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="header-title mb-0"
                            style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.02em;">Bulk Upload Returns
                        </h5>
                        <p class="header-subtitle mb-0" style="color: #64748b; font-size: 0.85rem;">Import and process
                            return data from Excel</p>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div class="d-flex gap-2">
                    @if($hasDrafts)
                    @can('return-drafts.view')
                    <a href="{{ route('admin.returns.drafts') }}" class="btn btn-warning-premium"
                        style="background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.2s;">
                        <i class="ti ti-list-details me-2"></i>View Return Drafts
                    </a>
                    @endcan
                    @endif
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-premium"
                        style="background: transparent; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.2s;">
                        <i class="ti ti-arrow-left me-2"></i>Back to Sales
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="upload-body-container">
        <!-- Notification Area -->
        <div class="notification-stack mb-4">
            @if(session('info'))
            <div class="premium-alert alert-info-premium"
                style="display: flex; align-items: flex-start; gap: 15px; padding: 16px 20px; border-radius: 16px; margin-bottom: 12px; border: 1px solid #bfdbfe; background: #eff6ff; color: #1e40af;">
                <div class="alert-icon" style="font-size: 1.25rem;"><i class="ti ti-info-circle"></i></div>
                <div class="alert-content" style="font-size: 0.9rem; font-weight: 500;">{{ session('info') }}</div>
            </div>
            @endif

            @if(session('success'))
            <div class="premium-alert alert-success-premium"
                style="display: flex; align-items: flex-start; gap: 15px; padding: 16px 20px; border-radius: 16px; margin-bottom: 12px; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534;">
                <div class="alert-icon" style="font-size: 1.25rem;"><i class="ti ti-circle-check"></i></div>
                <div class="alert-content" style="font-size: 0.9rem; font-weight: 500;">{{ session('success') }}</div>
            </div>
            @endif

            @if($pendingCount > 0)
            <div class="premium-alert alert-warning-premium"
                style="display: flex; align-items: flex-start; gap: 15px; padding: 16px 20px; border-radius: 16px; margin-bottom: 12px; border: 1px solid #fed7aa; background: #fff7ed; color: #9a3412;">
                <div class="alert-icon pulse-icon" style="font-size: 1.25rem;"><i class="ti ti-loader"></i></div>
                <div class="alert-content" style="font-size: 0.9rem; font-weight: 500;">
                    <strong>{{ $pendingCount }}</strong> row(s) are still being processed.
                    @can('return-drafts.view')
                    <a href="{{ route('admin.returns.drafts') }}" class="alert-link"
                        style="color: inherit; text-decoration: underline; font-weight: 700;">Refresh the drafts
                        page</a> to check progress.
                    @endcan
                </div>
            </div>
            @endif
        </div>

        <!-- Upload Form Card -->
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="upload-master-card"
                    style="background: #ffffff; border-radius: 30px; border: 1px solid #e2e8f0; box-shadow: 0 20px 50px rgba(0,0,0,0.08); position: relative; overflow: hidden;">
                    <div class="card-glass-effect"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at top right, rgba(239, 68, 68, 0.03), transparent); pointer-events: none;">
                    </div>
                    <div class="upload-card-content p-4 p-md-5" style="position: relative; z-index: 1;">
                        <div class="text-center mb-5">
                            <div class="upload-visual-icon"
                                style="width: 80px; height: 80px; background: #fef2f2; border: 2px solid #fee2e2; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2.5rem; color: #ef4444; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                                <i class="ti ti-file-spreadsheet"></i>
                            </div>
                            <h6 class="upload-form-title" style="font-weight: 800; font-size: 1.5rem; color: #1e293b;">
                                Upload Return Excel File</h6>
                            <div class="column-guidelines mt-3">
                                <p class="text-muted fs-2 mb-3" style="color: #64748b; font-size: 0.85rem;">Required
                                    columns:</p>
                                <div class="tag-cloud d-flex flex-wrap justify-content-center gap-2">
                                    <span class="column-pill"
                                        style="background: #fef2f2; color: #991b1b; padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; border: 1px solid #fecaca;">d_date</span>
                                    <span class="column-pill"
                                        style="background: #fef2f2; color: #991b1b; padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; border: 1px solid #fecaca;">c_store_code</span>
                                    <span class="column-pill"
                                        style="background: #fef2f2; color: #991b1b; padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; border: 1px solid #fecaca;">c_billno</span>
                                    <span class="column-pill"
                                        style="background: #fef2f2; color: #991b1b; padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; border: 1px solid #fecaca;">c_item_code</span>

                                    <span class="column-pill"
                                        style="background: #fef2f2; color: #991b1b; padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; border: 1px solid #fecaca;">n_quantity</span>
                                </div>
                                <p class="warning-note mt-3"
                                    style="font-size: 0.8rem; color: #dc2626; font-weight: 600; background: #fef2f2; padding: 8px 12px; border-radius: 8px; display: inline-block;">
                                    <i class="ti ti-alert-triangle-filled me-1"></i>
                                    A fresh upload will clear any existing unconfirmed return drafts.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.returns.process-bulk-upload') }}"
                            enctype="multipart/form-data" class="upload-form">
                            @csrf
                            <div class="upload-zone mb-4 @error('file') is-invalid-zone @enderror"
                                style="position: relative; width: 100%;">
                                <input type="file" id="file" name="file" accept=".xlsx,.xls" class="file-input-hidden"
                                    style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0;">
                                <label for="file" class="upload-label-zone"
                                    style="display: block; width: 100%; padding: 40px 20px; background: #fef2f2; border: 2px dashed #fca5a5; border-radius: 20px; cursor: pointer; transition: all 0.3s; text-align: center;">
                                    <div class="zone-content">
                                        <i class="ti ti-file-upload zone-icon"
                                            style="font-size: 2.5rem; color: #f87171; transition: all 0.3s;"></i>
                                        <div class="zone-text"
                                            style="display: flex; flex-direction: column; margin-top: 15px;">
                                            <span class="primary-text"
                                                style="font-weight: 800; font-size: 1.1rem; color: #1e293b;">Select
                                                Return Excel File *</span>
                                            <span class="secondary-text" id="file-name-display"
                                                style="font-size: 0.85rem; color: #64748b;">Click to browse or drag &
                                                drop</span>
                                        </div>
                                    </div>
                                </label>
                                @error('file')
                                <div class="error-text-premium mt-2 text-center"
                                    style="color: #ef4444; font-size: 0.85rem; font-weight: 600;">
                                    <i class="ti ti-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>
                            @can('return-upload.upload')
                            <div class="text-center">
                                <button type="submit" class="btn btn-process-upload"
                                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 16px 40px; border-radius: 16px; font-weight: 800; font-size: 1.05rem; border: none; box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2); transition: all 0.3s; display: inline-flex; align-items: center; cursor: pointer;">
                                    <span>Upload & Process Returns</span>
                                    <i class="ti ti-rocket ms-2"></i>
                                </button>
                            </div>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.upload-label-zone:hover {
    background: #fee2e2 !important;
    border-color: #ef4444 !important;
}

.upload-label-zone:hover .zone-icon {
    color: #ef4444 !important;
    transform: translateY(-5px);
}

.btn-process-upload:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 15px 30px rgba(239, 68, 68, 0.3) !important;
}

.btn-warning-premium:hover {
    background: #ffedd5 !important;
    transform: translateY(-2px);
}

.btn-outline-premium:hover {
    background: #f1f5f9 !important;
    color: #1e293b !important;
    border-color: #cbd5e1 !important;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.5;
    }

    100% {
        opacity: 1;
    }
}

.pulse-icon {
    animation: pulse 2s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileNameDisplay = document.getElementById('file-name-display');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = 'Selected: ' + this.files[0].name;
                fileNameDisplay.style.color = '#ef4444';
                fileNameDisplay.style.fontWeight = '700';
            } else {
                fileNameDisplay.textContent = 'Click to browse or drag & drop';
                fileNameDisplay.style.color = '';
                fileNameDisplay.style.fontWeight = '';
            }
        });
    }
});
</script>
@endsection
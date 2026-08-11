@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #F7941E;
    --bg-light: #fbfbfb;
    --text-dark: #2d3436;
    --border-color: #e9ecef;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.form-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header-custom {
    padding: 1.5rem 2rem;
    background: #fff;
    border-bottom: 2px solid var(--bg-light);
    border-top: 4px solid var(--primary-green);
}

.card-title-custom {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.form-body {
    padding: 2rem;
}

.form-section-title {
    font-size: 15px;
    font-weight: 800;
    color: #1b3e86;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control,
.form-select {
    border-radius: 8px;
    border: 1.5px solid #b7bec5;
    padding: 0.75rem 1rem;
    background-color: #fcfcfc;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    box-shadow: 0 0 0 4px rgba(57, 181, 74, 0.1);
    background-color: #fff;
}


#incentive_percentages {
    background-color: #f7fafc;
    border: 1.5px solid #edf2f7;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.incentive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.incentive-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
    background: #fff;
    padding: 1rem;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

/* Percentage input styling */
.input-group-text-custom {
    background: none;
    border: none;
    padding-left: 5px;
    font-weight: 600;
    color: #718096;
}

.btn-create-custom {
    background: var(--primary-green);
    border: none;
    padding: 0.8rem 2.5rem;
    border-radius: 10px;
    font-weight: 700;
    color: #fff;
    transition: all 0.3s ease;
}

.btn-create-custom:hover {
    background: #ce2a2a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(57, 181, 74, 0.3);
}

.btn-cancel-custom {
    border-radius: 10px;
    padding: 0.8rem 2rem;
    font-weight: 600;
}


.checkbox-container {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    background: #fff;
    border: 1px solid #edf2f7;
    border-radius: 10px;
    width: fit-content;
    cursor: pointer;
}

#active_incentive {
    accent-color: var(--primary-green);
    width: 18px;
    height: 18px;
}
</style>

<div class="card form-card mb-4">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="card-title-custom">Add Product</h5>
    </div>

    <div class="form-body">
        <form id="frm_create" method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            <div class="row g-4">
                <!-- General Information Section -->
                <div class="col-12">
                    <div class="form-section-title">
                        <i class="ti ti-info-circle"></i> Basic Information
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="n_category_id" class="form-label">
                        Category <span class="text-danger">*</span>
                    </label>

                    <select id="n_category_id" name="n_category_id" class="form-select mandatory"
                        data-message="Please select Category">
                        <option value="">Select Category</option>

                        @foreach ($categories as $category)

                        {{-- Category with children --}}
                        @if ($category->children->count() > 0)

                        <option disabled>
                            {{ $category->c_category_name }}
                        </option>

                        @foreach ($category->children as $child)
                        <option value="{{ $child->n_category_id }}"
                            {{ old('n_category_id') == $child->n_category_id ? 'selected' : '' }}>
                            &nbsp;&nbsp;&nbsp;— {{ $child->c_category_name }}
                        </option>
                        @endforeach

                        {{-- Category without children --}}
                        @else

                        <option value="{{ $category->n_category_id }}"
                            {{ old('n_category_id') == $category->n_category_id ? 'selected' : '' }}>
                            {{ $category->c_category_name }}
                        </option>

                        @endif

                        @endforeach
                    </select>

                    @error('n_category_id')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="c_product_name" class="form-label">Product Name *</label>
                    <input type="text" id="c_product_name" data-message="Please enter Product Name"
                        name="c_product_name" value="{{ old('c_product_name') }}" class="form-control mandatory"
                        placeholder="Enter product name">

                    @error('c_product_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="c_product_code" class="form-label">Product Code *</label>
                    <input type="text" id="c_product_code" data-message="Please enter Product Code"
                        name="c_product_code" value="{{ old('c_product_code') }}" class="form-control mandatory"
                        placeholder="Enter product code">
                    <div id="code_error" class="text-danger mt-1 fs-2">
                        @error('c_product_code') {{ $message }} @enderror
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="col-12">
                    <div class="form-section-title mt-3">
                        <i class="ti ti-currency-dollar"></i> Financial Details
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="n_mrp" class="form-label">MRP *</label>
                    <input type="text" id="n_mrp" name="n_mrp" data-message="Please enter Maximum Retail Price"
                        value="{{ old('n_mrp') }}" class="form-control mandatory" placeholder="0.00">
                    @error('n_mrp')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="n_purchase_price" class="form-label">Purchase Price *</label>
                    <input type="text" id="n_purchase_price" name="n_purchase_price"
                        data-message="Please enter Purchase Price" value="{{ old('n_purchase_price') }}"
                        class="form-control mandatory" placeholder="0.00">
                    @error('n_purchase_price')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="n_selling_price" class="form-label">Selling Price *</label>
                    <input type="text" id="n_selling_price" name="n_selling_price"
                        data-message="Please enter Selling Price" value="{{ old('n_selling_price') }}"
                        class="form-control mandatory" placeholder="0.00">
                    <div id="selling_error" class="text-danger mt-1 fs-2">
                        @error('n_selling_price') {{ $message }} @enderror
                    </div>
                </div>

                <!-- <div class="col-md-6">
                    <label for="c_status" class="form-label">Status *</label>
                    <select id="c_status" name="c_status" data-message="Please enter Status"
                        class="form-select mandatory">
                        <option value="">Select Status</option>
                        <option value="Y" {{ old('c_status') === 'Y' ? 'selected' : '' }}>Active</option>
                        <option value="N" {{ old('c_status') === 'N' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('c_status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div> -->
                <div class="col-md-6">
                    <label for="c_unit" class="form-label">Unit *</label>
                    <input type="text" id="c_unit" name="c_unit" value="{{ old('c_unit') }}"
                        class="form-control mandatory" data-message="Please enter Unit" placeholder="e.g. 1 ltr">

                    @error('c_unit')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="c_hsn_code" class="form-label">HSN Code *</label>
                    <input type="text" id="c_hsn_code" name="c_hsn_code" value="{{ old('c_hsn_code') }}"
                        class="form-control mandatory" data-message="Please enter HSN Code" placeholder="e.g. 31010099">

                    @error('c_hsn_code')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="n_gst_percentage" class="form-label">GST % *</label>
                    <input type="number" id="n_gst_percentage" name="n_gst_percentage"
                        value="{{ old('n_gst_percentage') }}" class="form-control mandatory"
                        data-message="Please enter GST Percentage" placeholder="e.g. 5" min="0" max="100" step="0.01">

                    @error('n_gst_percentage')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="c_status" class="form-label">Status *</label>
                    <select id="c_status" name="c_status" data-message="Please enter Status"
                        class="form-select mandatory">

                        <option value="">Select Status</option>

                        <option value="Y" {{ old('c_status') === 'Y' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="N" {{ old('c_status') === 'N' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('c_status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{--    <!-- Incentive Splits Toggle -->
                <div class="col-12 mt-4">
                    <label class="checkbox-container">
                        <input type="checkbox" value="1" name="c_active_incentive" id="active_incentive">
                        <span class="form-label mb-0">Enable Incentive Splits</span>
                        <div id="incentive_danger" class="text-danger mt-1 fs-2 ms-2"></div>
                    </label>
                </div>

                <!-- Incentive Reveal Grid -->
                <div id="incentive_percentages" class="col-12" style="display:none;">
                    <div class="incentive-grid">
                        <div class="incentive-item">
                            <label class="form-label">CSA</label>
                            <div class="d-flex align-items-center">
                                <input type="text" id="csa" name="n_customer_service_associate"
                                    class="form-control incentive" placeholder="0"
                                    value="{{ old('n_customer_service_associate', 60) }}" readonly>
                <span class="input-group-text-custom">%</span>
            </div>
    </div>

    <div class="incentive-item">
        <label class="form-label">C&A</label>
        <div class="d-flex align-items-center">
            <input type="text" id="n_cash_accountant" name="n_cash_accountant" class="form-control incentive"
                placeholder="0" value="{{ old('n_cash_accountant', 5) }}" readonly>
            <span class="input-group-text-custom">%</span>
        </div>
    </div>

    <div class="incentive-item">
        <label class="form-label">SM</label>
        <div class="d-flex align-items-center">
            <input type="text" id="n_sales_manager" name="n_sales_manager" class="form-control incentive"
                placeholder="0" value="{{ old('n_sales_manager', 10) }}" readonly>
            <span class="input-group-text-custom">%</span>
        </div>
    </div>

    <div class="incentive-item">
        <label class="form-label">Clustor</label>
        <div class="d-flex align-items-center">
            <input type="text" id="n_clustor_manager" name="n_clustor_manager" class="form-control incentive"
                placeholder="0" value="{{ old('n_clustor_manager', 5) }}" readonly>
            <span class="input-group-text-custom">%</span>
        </div>
    </div>

    <div class="incentive-item">
        <label class="form-label">Operations</label>
        <div class="d-flex align-items-center">
            <input type="text" id="n_operations" name="n_operations" class="form-control incentive" placeholder="0"
                value="{{ old('n_operations', 3) }}" readonly>
            <span class="input-group-text-custom">%</span>
        </div>
    </div>

    <div class="incentive-item">
        <label class="form-label">BM</label>
        <div class="d-flex align-items-center">
            <input type="text" id="n_bm_teams" name="n_bm_teams" class="form-control incentive" placeholder="0"
                placeholder="0" value="{{ old('n_bm_teams', 7) }}" readonly>
            <span class="input-group-text-custom">%</span>
        </div>
    </div>

    <div class="incentive-item">
        <label class="form-label">DC</label>
        <div class="d-flex align-items-center">
            <input type="text" id="n_dc_teams" name="n_dc_teams" class="form-control incentive" placeholder="0"
                value="{{ old('n_dc_teams', 5) }}" readonly>
            <span class="input-group-text-custom">%</span>
        </div>
    </div>

    <div class="incentive-item">
        <label class="form-label">HO</label>
        <div class="d-flex align-items-center">
            <input type="text" id="n_head_office" name="n_head_office" class="form-control incentive" placeholder="0"
                value="{{ old('n_head_office', 5) }}" readonly>
            <span class="input-group-text-custom">%</span>
        </div>
    </div>
</div>
</div> --}}

<div class="col-12 mt-4 pt-3 border-top d-flex gap-3">
    <button type="submit" id="btn_create" class="btn buttonSpc incentive_perc">Create
        Item</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-cancel-custom">Cancel</a>
</div>
</div>
</form>
</div>
</div>
@endsection
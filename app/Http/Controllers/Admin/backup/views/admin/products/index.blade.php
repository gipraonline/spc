@extends('layouts.app')

@section('content')
<style>
/* Filter Card */
.filter-card-wrapper {
    margin-bottom: 1rem;
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
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(93, 135, 255, .1);
    color: #5d87ff;
}

.filter-header-sub span {
    font-size: .9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* Filter Body */
.premium-filter-container {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 0;
}

/* Labels */
.custom-filter-group {
    position: relative;
}

.custom-filter-group label {
    display: block;
    margin-bottom: 12px;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Inputs */
.styled-select,
.styled-textbox {
    height: 54px !important;
    border: 1.5px solid #dfe5ef !important;
    border-radius: 16px !important;
    background: #f8fafc !important;
    padding: 0 18px !important;
    font-size: 14px !important;
}

.styled-select:focus,
.styled-textbox:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, .08) !important;
}

/* Buttons */
.btn-creative-filter {
    height: 54px !important;
    border-radius: 16px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
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
}

.search-label svg {
    flex-shrink: 0;
}

.search-label {
    margin-bottom: 12px !important;
}

/* Responsive */
@media (max-width:768px) {
    .filter-card-wrapper {
        margin: 1rem;
        padding: 1rem;
    }

    .premium-filter-container {
        padding: 15px;
    }
}
</style>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Products</h5>
        @can('products.create')
        <a href="{{ route('admin.products.create') }}" class="btn buttonSpc">
            Add Item
        </a>
        @endcan
    </div>

    <div class="card-body p-4">
        @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
        @endif


        {{-- Search Box --}}

        <form method="POST" action="{{ route('admin.products.search') }}">
            @csrf
            <div class="filter-card-wrapper">
                <div class="filter-header-sub">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </div>
                    <span>Refine Search</span>
                </div>
                <div class="premium-filter-container">

                    <div class="row g-3 align-items-end">

                        <!-- Search -->
                        <div class="col-md-5">
                            <div class="">

                                <label class="search-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" style="color:#1b3e86; margin-right:6px;">
                                        <path
                                            d="M21 8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                        <polyline points="3.29 7 12 12 20.71 7" />
                                        <line x1="12" y1="22" x2="12" y2="12" />
                                    </svg>
                                    Product
                                </label>

                                <input type="text" id="search" name="search" class="form-control styled-textbox"
                                    placeholder="Search by Product ID or Product Name"
                                    value="{{ session('product_search') }}">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-3">
                            <div class="">
                                <label class="search-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" style="color:#1b3e86; margin-right:6px;">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M9 12l2 2 4-4"></path>
                                    </svg>
                                    Status
                                </label>
                                <select name="status" class="form-select styled-select">
                                    <option value="Y" {{ session('product_status') == 'Y' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="N" {{ session('product_status') == 'N' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-4">
                            <div class="d-flex gap-2">

                                <button type="submit" class="btn buttonSpc btn-creative-filter flex-fill">
                                    <i class="ti ti-search"></i>
                                    Filter
                                </button>


                                <a href="{{ route('admin.products.clearSearch') }}" class="btn btn-outline-secondary"
                                    style="width:200px;--bs-btn-padding-y: 15px;">
                                    <i class="ti ti-refresh"></i>
                                    Reset
                                </a>

                                @can('products.export')
                                <a href="{{ route('admin.products.export', request()->query()) }}"
                                    class="btn btn-success btn-creative-filter flex-fill">
                                    <i class="ti ti-file-export"></i>
                                    Export
                                </a>
                                @endcan

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </form>

        <div class="table-responsive" id="productTable">
            <table class="table text-nowrap mb-0 align-middle">
                <!-- <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Sl No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Product ID</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">MRP</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Selling Price</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Purchase Price</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        @canany(['products.edit', 'products.delete'])
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        @endcanany
                    </tr>
                </thead> -->
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Sl No</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Product ID</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Category</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Unit</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">HSN Code</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">GST %</h6>
                        </th>

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">MRP</h6>
                        </th>

                        <!-- <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Selling Price</h6>
                        </th> -->

                        <!-- <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Purchase Price</h6>
                        </th> -->

                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>

                        @canany(['products.edit', 'products.delete'])
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        {{-- SL No --}}
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">
                                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                            </h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $product->c_product_code }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $product->c_product_name }}</h6>
                        </td>

                        <td class="border-bottom-0">
                            <span class="fw-normal">
                                {{ $product->category?->c_category_name ?? '-' }}
                            </span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">
                                {{ $product->c_unit ?? '-' }}
                            </span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">
                                {{ $product->c_hsn_code }}
                            </span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">
                                {{ number_format($product->n_gst_percentage, 2) }}
                            </span>
                        </td>

                        <td class="border-bottom-0">
                            <span class="fw-normal">₹{{ number_format($product->n_mrp, 2) }}</span>
                        </td>
                        <!-- <td class="border-bottom-0">
                            <span class="fw-normal">₹{{ number_format($product->n_selling_price, 2) }}</span>
                        </td> -->
                        <!-- <td class="border-bottom-0">
                            <span class="fw-normal">₹{{ number_format($product->n_purchase_price, 2) }}</span>
                        </td> -->

                        <td class="border-bottom-0">
                            <span
                                class="badge {{ $product->c_status === 'Y' ? 'bg-success' : 'bg-danger' }} rounded-3 fw-semibold">
                                {{ ucfirst(str_replace('_', ' ', $product->c_status)) }}
                            </span>
                        </td>
                        @canany(['products.edit', 'products.delete'])
                        <td class="border-bottom-0">
                            @can('products.edit')
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            @endcan
                            @can('products.delete')
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger ms-2" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                            @endcan
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No products found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
let timer;

document.getElementById('search').addEventListener('keyup', function() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        this.form.submit();
    }, 1500);
});
</script>
@endpush
@endsection
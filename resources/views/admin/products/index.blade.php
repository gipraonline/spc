@extends('layouts.app')

@section('content')

<style>
/* Premium Filter Card Design */
.product-filter-card {
    background: #ffffff;
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    overflow: hidden;
}

/* Creative Gradient Top Border */
.product-filter-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
}

.product-filter-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.12);
}

.filter-header-wrapper {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #e2e8f0;
}

.filter-icon-container {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: #3b82f6;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    transition: all 0.3s ease;
}

.product-filter-card:hover .filter-icon-container {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    box-shadow: 0 8px 20px -5px rgba(37, 99, 235, 0.4);
}

.filter-icon-container i {
    font-size: 22px;
}

.header-title {
    font-size: 18px;
    letter-spacing: 0.5px;
    color: #0f172a;
    font-weight: 700 !important;
}

.premium-label {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 8px;
    font-weight: 600 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.search-input-wrapper {
    position: relative;
}

.input-inner-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 18px;
    pointer-events: none;
    transition: color 0.3s ease;
}

.premium-input-control {
    height: 52px !important;
    border-radius: 14px !important;
    border: 2px solid #f1f5f9 !important;
    background-color: #f8fafc !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #1e293b !important;
    transition: all 0.3s ease !important;
    box-shadow: none !important;
}

.premium-search-input {
    padding-left: 48px !important;
}

.premium-input-control:focus {
    background-color: #ffffff !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
}

.search-input-wrapper:focus-within .input-inner-icon {
    color: #3b82f6;
}

.premium-btn {
    height: 52px;
    border-radius: 14px;
    font-weight: 600;
    font-size: 14px;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
}

.btn-filter {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.3);
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 20px -4px rgba(37, 99, 235, 0.4);
    color: white;
}

.btn-export {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 8px 16px -4px rgba(16, 185, 129, 0.3);
}

.btn-export:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 20px -4px rgba(16, 185, 129, 0.4);
    color: white;
}

.action-buttons-wrapper {
    display: flex;
    gap: 16px;
    align-items: flex-end;
}

@media (max-width: 768px) {
    .action-buttons-wrapper {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Products</h5>
        @can('products.create')
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
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
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="product-filter-card p-4 p-md-5 mb-4">
                <!-- Header Section -->
                <div class="filter-header-wrapper">
                    <div class="filter-icon-container">
                        <i class="ti ti-filter"></i>
                    </div>
                    <h5 class="ms-3 mb-0 header-title text-uppercase">
                        Refine Search
                    </h5>
                </div>

                <!-- Search Input Section -->
                <div class="row g-4">
                    <div class="col-md-5 col-lg-5">
                        <label class="form-label premium-label">
                            Search by Product ID or Product Name
                        </label>
                        <div class="search-input-wrapper">
                            <div class="input-inner-icon">
                                <i class="ti ti-search"></i>
                            </div>
                            <input type="text" name="search" id="search"
                                class="form-control premium-input-control premium-search-input"
                                placeholder="Search by Product ID or Product Name" value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Product Status --}}
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label premium-label">Product Status</label>
                        <select name="status" class="form-select premium-input-control">
                            <option value="">All Status</option>
                            <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>Active</option>
                            <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-md-4 col-lg-4 d-flex align-items-end">
                        <div class="action-buttons-wrapper w-100">
                            <button type="submit" class="btn premium-btn btn-filter flex-grow-1">
                                <i class="ti ti-search me-2 fs-5"></i>
                                Filter Products
                            </button>

                            @can('products.export')
                            <a href="{{ route('admin.products.export', request()->query()) }}"
                                class="btn premium-btn btn-export px-4">
                                <i class="ti ti-file-export me-1 fs-5"></i>
                                Export Excel
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive" id="productTable">
            <table class="table text-nowrap mb-0 align-middle">
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
                            <span class="fw-normal">₹{{ number_format($product->n_mrp, 2) }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">₹{{ number_format($product->n_selling_price, 2) }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">₹{{ number_format($product->n_purchase_price, 2) }}</span>
                        </td>
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
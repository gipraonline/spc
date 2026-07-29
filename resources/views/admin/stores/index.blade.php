@extends('layouts.app')

@section('content')


<style>
.refine-search-card {
    font-family: 'Inter', sans-serif;
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.refine-search-card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
}

.icon-box {
    background: linear-gradient(135deg, #f0f7ff 0%, #e0eeff 100%);
    border: 1px solid #d0e4ff;
    color: #5d87ff;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon-inner {
    position: absolute;
    left: 16px;
    color: #94a3b8;
    pointer-events: none;
}

.custom-input {
    height: 52px !important;
    padding-left: 48px !important;
    padding-right: 110px !important;
    border-radius: 12px !important;
    border: 1.5px solid #e2e8f0 !important;
    background-color: #f8fafc !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #1e293b !important;
    transition: all 0.2s ease-in-out !important;
    box-shadow: none !important;
}

.custom-input:focus {
    background-color: #ffffff !important;
    border-color: #5d87ff !important;
    box-shadow: 0 0 0 4px rgba(93, 135, 255, 0.1) !important;
}

.custom-input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.search-btn {
    position: absolute;
    right: 8px;
    height: 38px;
    padding: 0 20px;
    background: #5d87ff;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.search-btn:hover {
    background: #4a6ee0;
    transform: translateY(-1px);
}

.search-label {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>







<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Stores</h5>
        @can('stores.create')
        <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">Add Store</a>
        @endcan
    </div>
    <div class="card-body p-4">
        @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
        @endif


        <!-- Search Store -->

        <form method="GET" action="{{ route('admin.stores.index') }}">
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <!-- Header Section -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box d-flex align-items-center justify-content-center rounded-3 me-3"
                                style="width:40px; height:40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold" style="font-size:16px; color:#1e293b; letter-spacing:-0.2px;">
                                    Refine Search
                                </h6>
                                <p class="mb-0 text-muted" style="font-size: 12px; font-weight: 400;">Filter your stores
                                    by name or unique code</p>
                            </div>
                        </div>

                        @if(request('search'))
                        <a href="{{ route('admin.stores.index') }}" class="text-decoration-none"
                            style="font-size: 13px; color: #ef4444; font-weight: 600;">
                            Clear Filters
                        </a>
                        @endif
                    </div>
                    <!-- Search Field Section -->
                    <div class="row">
                        <div class="col-md-6 col-lg-5">

                            <label class="search-label" for="storeSearch">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                Store Identity
                            </label>
                            <div class="search-input-group">
                                <div class="search-icon-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>

                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control custom-input" placeholder="Store Code or Name..."
                                    id="storeSearch" autocomplete="off">
                                <button type="submit" class="search-btn">
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>





        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Code</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Email</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Phone</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        @canany(['stores.edit', 'stores.delete'])
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stores as $store)
                    <tr>
                        <td class="border-bottom-0">
                            <span class="fw-normal">{{ $store->c_store_code }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $store->c_store_name }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">{{ $store->c_store_email ?? '-' }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <span class="fw-normal">{{ $store->n_store_phone ?? '-' }}</span>
                        </td>
                        <td class="border-bottom-0">
                            <span
                                class="badge {{ $store->c_store_status === 'Y' ? 'bg-success' : 'bg-danger' }} rounded-3 fw-semibold">
                                {{ ucfirst($store->c_store_status) }}
                            </span>
                        </td>
                        @canany(['stores.edit', 'stores.delete'])
                        <td class="border-bottom-0">
                            @can('stores.edit')
                            <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-sm btn-primary">Edit</a>
                            @endcan
                            @can('stores.delete')
                            <form method="POST" action="{{ route('admin.stores.destroy', $store) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger ms-2"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @endcan
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No stores found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $stores->links() }}
        </div>
    </div>
</div>


@push('scripts')
<script>
let searchTimer;

document.getElementById('storeSearch').addEventListener('keyup', function() {

    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        this.form.submit();
    }, 1200); // waits 800ms after typing stops
});
</script>
@endpush
@endsection
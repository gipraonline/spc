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

.search-btn.position-static {
    position: static;
}

.reset-btn {
    height: 38px;
    border-radius: 8px;
    font-size: 13px;
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
</style>







<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Stores</h5>
        @can('franchises.create')
        <a href="{{ route('admin.franchises.create') }}" class="btn btn-primary">Add Store</a>
        @endcan
    </div>
    <div class="card-body p-4">
        @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
        @endif


        <!-- Search Store -->

        <form method="POST" action="{{ route('admin.franchises.search') }}">
            @csrf
            <div class="card refine-search-card border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <!-- Header Section -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
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

                    </div>
                    <!-- Search Field Section -->
                    <div class="row">
                        <div class="col-md-6 col-lg-5">

                            <label class="search-label" for="storeSearch">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="me-1">
                                    <path d="M3 9l1-5h16l1 5" />
                                    <path d="M5 9v10h14V9" />
                                    <path d="M9 19v-6h6v6" />
                                    <path d="M3 9h18" />
                                </svg>
                                Store Search
                            </label>
                            <div class="search-input-group">
                                <div class="search-icon-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>

                                <input type="text" name="search" value="{{ session('store_search') }}"
                                    class="form-control custom-input" placeholder="Store Code or Name..."
                                    id="storeSearch" autocomplete="off">

                                <div class="position-absolute end-0 me-2 d-flex gap-2">
                                    <button type="submit" class="search-btn position-static">
                                        Search
                                    </button>

                                    @if(session('store_search'))
                                    <a href="{{ route('admin.franchises.clearSearch') }}"
                                        class="btn btn-outline-primary reset-btn">
                                        <i class="ti ti-refresh me-1"></i>
                                        Reset
                                    </a>
                                    @endif
                                </div>
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
                        @canany(['franchises.edit', 'franchises.delete'])
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
                        @canany(['franchises.edit', 'franchises.delete'])
                        <td class="border-bottom-0">
                            @can('franchises.edit')
                            <a href="{{ route('admin.franchises.edit', $store) }}"
                                class="btn btn-sm btn-primary">Edit</a>
                            @endcan
                            @can('franchises.delete')
                            <form method="POST" action="{{ route('admin.franchises.destroy', $store) }}"
                                class="d-inline">
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
                        <td colspan="6" class="text-center">No franchises found</td>
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
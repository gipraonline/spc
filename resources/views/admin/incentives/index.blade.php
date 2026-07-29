@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #1b3e86;
    --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
}


.filter-card {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border-top: 4px solid var(--primary-green);
    margin-bottom: 2rem;
}

.card-title-custom {
    font-weight: 700;
    color: #2c3e50;
    letter-spacing: -0.5px;
}

.form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border-radius: 8px;
    padding: 0.7rem 1rem;
    border: 1.5px solid #eef2f6;
    background-color: #fdfdfe;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(57, 181, 74, 0.08);
}

.btn-calculate {
    background: var(--primary-green);
    border: none;
    padding: 10px 30px;
    border-radius: 8px;
    font-weight: 700;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(57, 181, 74, 0.2);
}

.btn-calculate:hover {
    background: #1b3e86;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(57, 181, 74, 0.3);
}

/* Stats Cards */
.stat-card {
    border: none;
    border-radius: var(--border-radius);
    transition: transform 0.3s ease;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card .card-body {
    padding: 1.5rem;
    z-index: 1;
    position: relative;
}

.stat-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    margin-bottom: 8px;
    opacity: 0.8;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    margin: 0;
}

/* Decorative Background Shapes */
.stat-card::after {
    content: '';
    position: absolute;
    top: -20px;
    right: -20px;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
}

/* Stat Variants */
.bg-indigo {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
}

.bg-emerald {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
}

.bg-sky {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #fff;
}

.bg-amber {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
}
</style>

<div class="card filter-card">
    <div class="card-body p-4">
        <h5 class="card-title-custom mb-4">Calculate Store Incentives</h5>

        <form method="POST" action="{{ route('admin.incentives.calculate') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="store_id" class="form-label">Store *</label>
                    <select id="store_id" name="store_id" required class="form-select">
                        <option value="">Select Store</option>
                        @foreach ($stores as $store)
                        <option value="{{ $store->n_store_id }}">{{ $store->c_store_name }} ({{ $store->c_store_code }})
                        </option>
                        @endforeach
                    </select>
                    @error('store_id')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="" class="form-control">
                    @error('date_from')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="" class="form-control">
                    @error('date_to')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @can('store-incentives.calculate')
            <div class="mt-4 pt-2 border-top">

                <button type="submit" class="btn btn-calculate">
                    Calculate Now
                </button>
            </div>
            @endcan
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card stat-card bg-indigo">
            <div class="card-body text-center">
                <div class="stat-label">Total Employees</div>
                <h4 class="stat-value">-</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-emerald">
            <div class="card-body text-center">
                <div class="stat-label">Total Sales</div>
                <h4 class="stat-value">-</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-sky">
            <div class="card-body text-center">
                <div class="stat-label">Incentive Pool</div>
                <h4 class="stat-value">-</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-amber">
            <div class="card-body text-center">
                <div class="stat-label">Avg Per Employee</div>
                <h4 class="stat-value">-</h4>
            </div>
        </div>
    </div>
</div>
@endsection
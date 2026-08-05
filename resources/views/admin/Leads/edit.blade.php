@extends('layouts.app')

@section('content')
<div class="card w-100 position-relative overflow-hidden mb-4">
  <div class="px-4 py-3 border-bottom d-flex justify-content-between align-products-center">
    <h5 class="card-title fw-semibold mb-0 lh-sm">Edit Sales Entry</h5>
  </div>
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.sales.update', $sale->n_slno) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="n_employee_id" class="form-label">Employee *</label>
                <select id="n_employee_id" name="n_employee_id" required class="form-select">
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->n_employee_id }}" {{ old('n_employee_id', $sale->n_employee_id) == $employee->n_employee_id ? 'selected' : '' }}>
                            {{ $employee->c_employee_name }} ({{ $employee->c_employee_code }})
                        </option>
                    @endforeach
                </select>
                @error('n_employee_id')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="n_product_id" class="form-label">Product *</label>
                <select id="n_product_id" name="n_product_id" required class="form-select">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->n_product_id }}" {{ old('n_product_id', $sale->n_product_id) == $product->n_product_id ? 'selected' : '' }}>
                            {{ $product->c_product_name }} (Price: {{ $product->n_selling_price }})
                        </option>
                    @endforeach
                </select>
                @error('n_product_id')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="n_quantity" class="form-label">Quantity *</label>
                <input type="number" id="n_quantity" name="n_quantity" value="{{ old('n_quantity', $sale->n_quantity) }}" min="1" required
                    class="form-control">
                @error('n_quantity')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="d_date" class="form-label">Date *</label>
                <input type="date" id="d_date" name="d_date" value="{{ old('d_date', $sale->d_date) }}" required
                    class="form-control">
                @error('d_date')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn buttonSpc">Update</button>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
  </div>
</div>
@endsection

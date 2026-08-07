@extends('layouts.app')

@section('content')
<div class="card w-100 position-relative overflow-hidden mb-4">
  <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
    <h5 class="card-title fw-semibold mb-0 lh-sm">Edit Designation</h5>
  </div>
  <div class="card-body p-4">
    <form method="POST" id="frm_create" action="{{ route('admin.designations.update', $designation) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="c_designation" class="form-label">Designation Name *</label>
            <input type="text" id="c_designation" name="c_designation" value="{{ old('c_designation', $designation->c_designation) }}"  
                class="form-control mandatory">
          
                <div class="text-danger mt-1 fs-2"></div>
           
        </div>

        <div class="mb-4">
            <label for="c_status" class="form-label">Status *</label>
            <select id="c_status" name="c_status"  class="form-select mandatory">
                <option value="Y" {{ old('c_status', $designation->c_status) === 'Y' ? 'selected' : '' }}>Active</option>
                <option value="N" {{ old('c_status', $designation->c_status) === 'N' ? 'selected' : '' }}>Inactive</option>
            </select>
           
                <div class="text-danger mt-1 fs-2"></div>
          
        </div>

        <div class="d-flex gap-2">
            <button type="button" id="btn_create" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.designations.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
  </div>
</div>
@endsection
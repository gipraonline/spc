@extends('layouts.app')

@section('content')

<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create Role</h5>

        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card-body">

        @if ($errors->any())
        <div class="alert alert-danger">
            Please fix the errors below.
        </div>
        @endif

        <form method="POST" action="{{ route('admin.roles.store') }}">

            @csrf

            <div class="mb-3">
                <label class="form-label">Role Name <span class="text-danger">*</span></label>

                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                    placeholder="Example: HR Department">

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">
                    Identifier <span class="text-danger">*</span>
                </label>

                <input type="text" name="identifier" class="form-control" value="{{ old('identifier') }}"
                    placeholder="Example: HR_MANAGER">

                @error('identifier')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">HR Portal Access</label>

                <select name="hr_access" class="form-select">
                    <option value="" {{ old('hr_access') ? '' : 'selected' }}>
                        Not set (based on reporting structure)
                    </option>
                    <option value="super_admin" {{ old('hr_access') === 'super_admin' ? 'selected' : '' }}>
                        Super Admin
                    </option>
                    <option value="hr_admin" {{ old('hr_access') === 'hr_admin' ? 'selected' : '' }}>
                        HR Admin
                    </option>
                    <option value="manager" {{ old('hr_access') === 'manager' ? 'selected' : '' }}>
                        Reporting Manager
                    </option>
                    <option value="employee" {{ old('hr_access') === 'employee' ? 'selected' : '' }}>
                        Employee
                    </option>
                </select>

                <small class="text-muted d-block mt-1">
                    The level of access anyone holding this role gets inside the HR module.
                </small>

                @error('hr_access')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn buttonSpc">
                Save Role
            </button>

        </form>

    </div>

</div>

@endsection

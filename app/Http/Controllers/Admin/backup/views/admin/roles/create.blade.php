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

            <button type="submit" class="btn buttonSpc">
                Save Role
            </button>

        </form>

    </div>

</div>

@endsection

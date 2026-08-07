@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-plus text-primary me-2"></i>
                Create User
            </h4>

            <small class="text-muted">
                Create a new user and assign a role.
            </small>
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill">

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>

    <div class="card-body">

        @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Full Name <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>

                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Enter full name" required>

                    </div>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Email Address <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>

                        <input type="email" name="username" class="form-control" value="{{ old('username') }}"
                            placeholder="example@company.com" required>

                    </div>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Assign Role <span class="text-danger">*</span>

                </label>

                <select class="form-select" name="role" required>

                    <option value="">Select Role</option>

                    @foreach($roles as $role)

                    <option value="{{ $role->name }}" {{ old('role')==$role->name ? 'selected' : '' }}>

                        {{ $role->name }}

                    </option>

                    @endforeach

                </select>

                <small class="text-muted">

                    The selected role determines the user's menu access and permissions.

                </small>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2">

                    Cancel

                </a>

                <button type="submit" class="btn buttonSpc px-4">

                    <i class="fas fa-save me-1"></i>

                    Create User

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

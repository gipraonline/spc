@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-edit text-primary me-2"></i>
                Edit User
            </h4>

            <small class="text-muted">
                Update user details and assigned role.
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

        <form action="{{ route('admin.users.update', $user->n_role_id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Full Name <span class="text-danger">*</span>

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>

                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->c_name) }}"
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

                        <input type="email" name="username" class="form-control"
                            value="{{ old('username', $user->c_username) }}" placeholder="example@company.com" required>

                    </div>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Assign Role <span class="text-danger">*</span>

                </label>

                <select class="form-select" name="role" required>

                    @foreach($roles as $role)

                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>

                        {{ $role->name }}

                    </option>

                    @endforeach

                </select>

                <small class="text-muted">
                    Updating the role will automatically update the user's menu access and permissions.
                </small>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2">

                    Cancel

                </a>

                <button type="submit" class="btn btn-primary px-4">

                    <i class="fas fa-save me-1"></i>

                    Update User

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

<style>
.card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 .125rem .5rem rgba(0, 0, 0, .05);
}

.card-header {
    padding: 20px 24px;
}

.card-body {
    padding: 28px;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control,
.form-select {
    height: 46px;
    border-radius: 8px;
    border: 1px solid #d9dee3;
}

.form-control:focus,
.form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 .15rem rgba(105, 108, 255, .15);
}

.input-group-text {
    background: #f8f9fa;
    border: 1px solid #d9dee3;
    border-right: 0;
    border-radius: 8px 0 0 8px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.btn-primary {
    min-width: 160px;
}

.btn-light {
    min-width: 120px;
    border: 1px solid #dee2e6;
}

hr {
    margin: 28px 0;
    opacity: .15;
}

.alert {
    border-radius: 8px;
}
</style>
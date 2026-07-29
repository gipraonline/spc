@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-lock text-primary me-2"></i>
                Edit Permission
            </h4>

            <small class="text-muted">
                Update the permission name.
            </small>
        </div>

        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary rounded-pill">

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

        <form action="{{ route('admin.permissions.update',$permission->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Permission Name <span class="text-danger">*</span>

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="fas fa-lock"></i>

                    </span>

                    <input type="text" name="name" class="form-control" value="{{ old('name',$permission->name) }}"
                        placeholder="Example: users.create" required>

                </div>

                @error('name')

                <small class="text-danger">

                    {{ $message }}

                </small>

                @enderror

                <small class="text-muted mt-2 d-block">

                    Permission format: <strong>module.action</strong>
                    (Example: <code>users.create</code>)

                </small>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="{{ route('admin.permissions.index') }}" class="btn btn-light me-2">

                    Cancel

                </a>

                <button class="btn btn-primary px-4">

                    <i class="fas fa-save me-1"></i>

                    Update Permission

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

.form-control {
    height: 46px;
    border-radius: 8px;
    border: 1px solid #d9dee3;
}

.form-control:focus {
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
    min-width: 180px;
}

.btn-light {
    min-width: 120px;
    border: 1px solid #dee2e6;
}

.alert {
    border-radius: 8px;
}

hr {
    opacity: .15;
    margin: 30px 0;
}
</style>
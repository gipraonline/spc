@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-bars text-primary me-2"></i>
                Create Menu
            </h4>
            <small class="text-muted">
                Add a new menu or submenu to the application.
            </small>
        </div>

        <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary rounded-pill">
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

        <form method="POST" action="{{ route('admin.menus.store') }}">
            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Parent Menu
                    </label>

                    <select name="parent_id" class="form-select">
                        <option value="">-- Main Menu --</option>

                        @foreach($parents as $parent)

                        <option value="{{ $parent->id }}" {{ old('parent_id')==$parent->id ? 'selected' : '' }}>

                            {{ $parent->name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Menu Name <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                        placeholder="Enter menu name">

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Route Name
                    </label>

                    <input type="text" name="route_name" value="{{ old('route_name') }}" class="form-control"
                        placeholder="admin.users.index">

                    <small class="text-muted">
                        Leave empty if the menu is only a parent.
                    </small>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Icon
                    </label>

                    <input type="text" name="icon" value="{{ old('icon') }}" class="form-control"
                        placeholder="ti ti-users">

                    <small class="text-muted">
                        Example: ti ti-users
                    </small>

                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="{{ route('admin.menus.index') }}" class="btn btn-light me-2">

                    Cancel

                </a>

                <button class="btn btn-primary px-4">

                    <i class="fas fa-save me-1"></i>

                    Save Menu

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
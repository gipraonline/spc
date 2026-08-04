@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-user-shield text-primary me-2"></i>
                Edit Role
            </h4>

            <small class="text-muted">
                Update role details, assigned menus and permissions.
            </small>
        </div>

        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary rounded-pill">

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

        <form method="POST" action="{{ route('admin.roles.update',$role->id) }}">

            @csrf
            @method('PUT')

            {{-- Role Name --}}

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Role Name <span class="text-danger">*</span>

                </label>

                <input type="text" name="name" class="form-control" value="{{ old('name',$role->name) }}"
                    placeholder="Enter Role Name">

                @error('name')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror

            </div>

            <hr>

            {{-- MENUS --}}

            @php
            $assignedMenus = $role->menus->pluck('id')->toArray();
            @endphp

            <!-- <h5 class="section-title">

                <i class="fas fa-bars text-primary me-2"></i>

                Assign Menus

            </h5> -->
            @php
            $assignedMenus = $role->menus->pluck('id')->toArray();
            @endphp

            <div class="accordion mb-4" id="menuAccordion">

                <div class="accordion-item role-card">

                    <h2 class="accordion-header">

                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#menusCollapse">

                            <i class="fas fa-bars text-primary me-2"></i>

                            <strong>Assign Menus</strong>

                        </button>

                    </h2>

                    <div id="menusCollapse" class="accordion-collapse collapse show">

                        <div class="accordion-body">

                            @foreach($parents as $parent)

                            <div class="accordion mb-3" id="parentMenu{{ $parent->id }}">

                                <div class="accordion-item">

                                    <h2 class="accordion-header">

                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#menu{{ $parent->id }}">

                                            <i class="fas fa-folder text-primary me-2"></i>

                                            {{ $parent->name }}

                                        </button>

                                    </h2>

                                    <div id="menu{{ $parent->id }}" class="accordion-collapse collapse">

                                        <div class="accordion-body">

                                            <div class="row">

                                                @foreach($parent->children as $menu)

                                                <div class="col-md-4 mb-2">

                                                    <div class="form-check custom-check">

                                                        <input class="form-check-input" type="checkbox" name="menus[]"
                                                            value="{{ $menu->id }}" id="menuCheck{{ $menu->id }}"
                                                            {{ in_array($menu->id,$assignedMenus) ? 'checked' : '' }}>

                                                        <label class="form-check-label" for="menuCheck{{ $menu->id }}">

                                                            {{ $menu->name }}

                                                        </label>

                                                    </div>

                                                </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

            <hr>

            {{-- PERMISSIONS --}}

            @php
            $assignedPermissions = $role->permissions->pluck('name')->toArray();
            @endphp

            @php
            $assignedPermissions = $role->permissions->pluck('name')->toArray();
            @endphp

            <div class="accordion mb-4" id="permissionAccordion">

                <div class="accordion-item role-card">

                    <h2 class="accordion-header">

                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#permissionCollapse">

                            <i class="fas fa-lock text-primary me-2"></i>

                            <strong>Assign Permissions</strong>

                        </button>

                    </h2>

                    <div id="permissionCollapse" class="accordion-collapse collapse">

                        <div class="accordion-body">

                            @foreach($permissions as $module => $modulePermissions)

                            <div class="accordion mb-3">

                                <div class="accordion-item">

                                    <h2 class="accordion-header">

                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#module{{ Str::slug($module) }}">

                                            <i class="fas fa-folder-open text-primary me-2"></i>

                                            {{ ucwords(str_replace(['-','_'],' ',$module)) }}

                                        </button>

                                    </h2>

                                    <div id="module{{ Str::slug($module) }}" class="accordion-collapse collapse">

                                        <div class="accordion-body">

                                            <div class="row">

                                                @foreach($modulePermissions as $permission)

                                                @php
                                                $action = ucfirst(last(explode('.', $permission->name)));
                                                @endphp

                                                <div class="col-md-3 mb-2">

                                                    <div class="form-check custom-check">

                                                        <input class="form-check-input" type="checkbox"
                                                            name="permissions[]" value="{{ $permission->name }}"
                                                            id="permission{{ $permission->id }}"
                                                            {{ in_array($permission->name,$assignedPermissions) ? 'checked' : '' }}>

                                                        <label class="form-check-label"
                                                            for="permission{{ $permission->id }}">

                                                            {{ $action }}

                                                        </label>

                                                    </div>

                                                </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-end">

                <a href="{{ route('admin.roles.index') }}" class="btn btn-light me-2">

                    Cancel

                </a>

                <button class="btn btn-primary px-4">

                    <i class="fas fa-save me-1"></i>

                    Update Role

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

<style>
/* ==========================
   Card
========================== */

.card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 .125rem .5rem rgba(0, 0, 0, .05);
}

.card-header {
    padding: 20px 24px;
    background: #fff;
    border-bottom: 1px solid #e9ecef;
}

.card-body {
    padding: 24px;
}


/* ==========================
   Section Title
========================== */

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2b3c56;
    margin-bottom: 18px;
}


/* ==========================
   Form
========================== */

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


/* ==========================
   Accordion
========================== */

.accordion-item {

    border: 1px solid #e9ecef;
    border-radius: 10px !important;
    overflow: hidden;
    margin-bottom: 16px;
    transition: .25s;
}

.accordion-item:hover {

    box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
}

.accordion-button {

    background: #fff;
    font-weight: 600;
    color: #2b3c56;
    padding: 18px 20px;
}

.accordion-button:not(.collapsed) {

    background: #eef2ff;
    color: #696cff;
}

.accordion-button:focus {

    box-shadow: none;
}

.accordion-body {

    background: #fff;
}


/* ==========================
   Parent Cards
========================== */

.role-card {

    border: 1px solid #eceef1;
    border-radius: 10px;
    overflow: hidden;
}

.role-card-header {

    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
}


/* ==========================
   Checkbox
========================== */

.custom-check {

    padding: 8px 0;
}

.custom-check .form-check-input {

    width: 18px;
    height: 18px;
    cursor: pointer;
}

.custom-check .form-check-input:checked {

    background-color: #696cff;
    border-color: #696cff;
}

.custom-check .form-check-label {

    margin-left: 8px;
    font-weight: 500;
    cursor: pointer;
    color: #495057;
}


/* ==========================
   Buttons
========================== */

.btn {

    border-radius: 8px;
    font-weight: 500;
}

.btn-primary {

    background: #696cff;
    border-color: #696cff;
    min-width: 160px;
}

.btn-primary:hover {

    background: #5f61e6;
    border-color: #5f61e6;
}

.btn-outline-secondary {

    border-radius: 25px;
}

.btn-light {

    border: 1px solid #dee2e6;
    min-width: 120px;
}


/* ==========================
   Badge
========================== */

.badge-count {

    background: #696cff;
    color: #fff;
    border-radius: 20px;
    font-size: 12px;
    padding: 5px 10px;
}


/* ==========================
   Alert
========================== */

.alert {

    border-radius: 10px;
}


/* ==========================
   Divider
========================== */

hr {

    margin: 28px 0;
    opacity: .15;
}


/* ==========================
   Hover Effect
========================== */

.form-check {

    padding: 10px;
    border-radius: 8px;
    transition: .2s;
}

.form-check:hover {

    background: #f8f9fa;
}


/* ==========================
   Scrollable Accordion
========================== */

.permission-scroll {

    max-height: 420px;
    overflow-y: auto;
    padding-right: 8px;
}

.permission-scroll::-webkit-scrollbar {

    width: 7px;
}

.permission-scroll::-webkit-scrollbar-thumb {

    background: #d6d6d6;
    border-radius: 20px;
}


/* ==========================
   Footer
========================== */

.form-footer {

    position: sticky;
    bottom: 0;
    background: #fff;
    padding-top: 18px;
}


/* ==========================
   Responsive
========================== */

@media(max-width:768px) {

    .card-header {

        flex-direction: column;
        align-items: flex-start !important;
    }

    .card-header a {

        margin-top: 15px;
    }

    .btn-primary {

        width: 100%;
    }

    .btn-light {

        width: 100%;
        margin-bottom: 10px;
    }

}
</style>

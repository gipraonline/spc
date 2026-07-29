@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

        <div>
            <h3 class="mb-1 fw-bold">
                <i class="fas fa-lock text-primary me-2"></i>
                Permission Management
            </h3>

            <small class="text-muted">
                Permissions grouped by module
            </small>
        </div>
        @can('permission-management.create')
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-1"></i>
            Create Permission
        </a>
        @endcan

    </div>


    <div class="card-body">

        @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

        @endif


        <div class="accordion" id="permissionAccordion">

            @foreach($permissions as $module => $modulePermissions)

            <div class="accordion-item border rounded mb-3">

                <h2 class="accordion-header" id="heading{{$loop->index}}">


                    <button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->index}}">

                        <div class="d-flex justify-content-between align-items-center w-100">

                            <!-- Left -->
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder-open text-primary me-2"></i>
                                <strong>{{ $module }}</strong>
                            </div>

                            <!-- Right -->
                            <div class="d-flex align-items-center">

                                <span class="badge bg-primary rounded-pill me-3">
                                    {{ $modulePermissions->count() }}
                                </span>
                                @can('permission-management.create')

                                <a href="{{ route('admin.permissions.create', ['module' => strtolower($module)]) }}"
                                    class="btn btn-success btn-sm rounded-circle" title=" Add Permission"
                                    onclick="event.stopPropagation();">

                                    <i class="fas fa-plus"></i>

                                </a>
                                @endcan

                            </div>

                        </div>

                    </button>

                </h2>


                <div id="collapse{{$loop->index}}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                    data-bs-parent="#permissionAccordion">

                    <div class="accordion-body p-0">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">#</th>

                                    <th>Permission</th>
                                    @canany(['permission-management.edit', 'permission-management.delete'])
                                    <th width="180" class="text-center">
                                        Actions
                                    </th>
                                    @endcanany

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($modulePermissions as $permission)

                                @php
                                $action = ucfirst(explode('.', $permission->name)[1] ?? '');
                                @endphp

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>

                                        @switch(strtolower($action))

                                        @case('create')

                                        <span class="permission-badge badge-create">
                                            <i class="fas fa-plus me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break

                                        @case('edit')

                                        <span class="permission-badge badge-edit">
                                            <i class="fas fa-pen me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break

                                        @case('delete')

                                        <span class="permission-badge badge-delete">
                                            <i class="fas fa-trash me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break

                                        @case('view')

                                        <span class="permission-badge badge-view">
                                            <i class="fas fa-eye me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break

                                        @case('approve')

                                        <span class="permission-badge badge-approve">
                                            <i class="fas fa-check me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break

                                        @case('export')

                                        <span class="permission-badge badge-export">
                                            <i class="fas fa-file-export me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break
                                        @case('upload')

                                        <span class="permission-badge badge-upload">
                                            <i class="fas fa-file-upload me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break
                                        @case('calculate')

                                        <span class="permission-badge badge-calculate">
                                            <i class="fas fa-calculator me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break

                                        @case('view-details')

                                        <span class="permission-badge badge-view-details">
                                            <i class="fas fa-circle-info me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @break

                                        @default

                                        <span class="permission-badge badge-default">
                                            <i class="fas fa-circle me-1"></i>
                                            {{ $action }}
                                        </span>

                                        @endswitch

                                    </td>
                                    @canany(['permission-management.edit', 'permission-management.delete'])
                                    <td class="text-center">
                                        @can('permission-management.edit')
                                        <a href="{{ route('admin.permissions.edit',$permission->id) }}"
                                            class="btn btn-warning btn-sm me-2" title="Edit Permission">

                                            <i class="fas fa-edit"></i>

                                        </a>
                                        @endcan

                                        @can('permission-management.delete')
                                        <form action="{{ route('admin.permissions.destroy',$permission->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this permission?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm" title="Delete Permission">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>
                                        @endcan

                                    </td>
                                    @endcanany
                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            @endforeach

        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $permissions->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>

@endsection
<style>
/* ===========================
   Card
=========================== */

.card {
    border-radius: 12px;
}

.card-header {
    padding: 18px 24px;
}

.card-body {
    padding: 20px;
}


/* ===========================
   Accordion
=========================== */

.accordion-item {
    border: 1px solid #e9ecef;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
}

.accordion-button {
    background: #fff;
    font-weight: 600;
    color: #2c3e50;
    padding: 16px 20px;
}

.accordion-button:not(.collapsed) {
    background: #f8f9fa;
    color: #0d6efd;
}

.accordion-button:focus {
    box-shadow: none;
}


/* ===========================
   Table
=========================== */

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #1f4277;
    color: #fff;
    font-weight: 600;
    padding: 14px;
    border: none;
}

.table tbody td {
    vertical-align: middle;
    padding: 14px;
}

.table-hover tbody tr:hover {
    background: #f8fbff;
}


/* ===========================
   Permission Badges
=========================== */

.permission-badge {

    width: 110px;
    height: 36px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 50px;

    font-size: 14px;
    font-weight: 600;

    letter-spacing: .2px;
}


/* View */

.badge-view {
    background: #E8F3FF;
    color: #0D6EFD;
}


/* Create */

.badge-create {
    background: #E8F8EF;
    color: #198754;
}


/* Edit */

.badge-edit {
    background: #FFF4DD;
    color: #C58A00;
}


/* Delete */

.badge-delete {
    background: #FDECEC;
    color: #DC3545;
}


/* Approve */

.badge-approve {
    background: #EEF2FF;
    color: #4F46E5;
}


/* Export */

.badge-export {
    background: #F1F3F5;
    color: #495057;
}


/* Upload */

.badge-upload {
    background: #E8F4FD;
    color: #1565C0;
}


/* Default */

.badge-default {
    background: #ECECEC;
    color: #343A40;
}

/* Calculate */
.badge-calculate {
    background: #FFF8E1;
    color: #F57F17;
}

/* View Details */
.badge-view-details {
    background: #E8F5E9;
    color: #2E7D32;
}


/* ===========================
   Action Buttons
=========================== */

.btn-warning {

    background: #FFC107;
    border: none;
}

.btn-warning:hover {

    background: #E0A800;
}

.btn-danger {

    border: none;
}

.btn-danger:hover {

    background: #BB2D3B;
}


/* ===========================
   Pagination
=========================== */

.pagination {

    margin-bottom: 0;
}

.page-link {

    border-radius: 6px;
    margin: 0 3px;
    color: #1f4277;
}

.page-item.active .page-link {

    background: #1f4277;
    border-color: #1f4277;
}


/* ===========================
   Alerts
=========================== */

.alert {

    border-radius: 8px;
}

.accordion-button::after {
    margin-left: 15px;
}

.accordion-button .btn {
    z-index: 100;
    position: relative;
}
</style>
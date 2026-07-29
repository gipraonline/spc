@extends('layouts.app')

@section('content')

<div class="card w-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Role Management</h5>
        @can('role-management.create')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            + Create Role
        </a>
        @endcan
    </div>

    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Role Name</th>
                    <th width="120">Menus</th>
                    @canany(['role-management.edit', 'role-management.delete'])
                    <th width="180">Action</th>
                    @endcanany
                </tr>
            </thead>

            <tbody>

                @forelse($roles as $role)

                <tr>

                    <td>{{ $roles->firstItem() + $loop->index }}</td>

                    <td>{{ $role->name }}</td>

                    <td>
                        <span class="badge bg-info">
                            {{ $role->menus->count() }}
                        </span>
                    </td>
                    @canany(['role-management.edit', 'role-management.delete'])
                    <td>
                        @can('role-management.edit')
                        <a href="{{ route('admin.roles.edit',$role->id) }}" class="btn btn-sm btn-primary action-btn">
                            Edit
                        </a>
                        @endcan
                        @can('role-management.delete')
                        <form action="{{ route('admin.roles.destroy',$role->id) }}" method="POST" class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger action-btn"
                                onclick="return confirm('Delete this role?')">
                                Delete
                            </button>

                        </form>
                        @endcan

                    </td>
                    @endcanany

                </tr>

                @empty

                <tr>
                    <td colspan="4" class="text-center">
                        No Roles Found.
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $roles->links() }}
        </div>

    </div>
</div>

@endsection
<style>
.action-btn {
    width: 60px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}
</style>
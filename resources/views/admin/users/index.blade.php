@extends('layouts.app')

@section('content')

<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Management</h5>
        @can('user-management.create')
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            + Create User
        </a>
        @endcan
    </div>

    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}

            @if(session('password'))
            <hr class="my-2">
            <strong>Temporary Password:</strong>
            <span class="text-danger">{{ session('password') }}</span>
            <br>
            <small class="text-muted">
                Please share this password securely with the user.
            </small>
            @endif
        </div>
        @endif
        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th width="80">#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    @canany(['user-management.edit', 'user-management.delete'])
                    <th width="180">Action</th>
                    @endcanany
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                <tr>

                    <td>{{ $users->firstItem() + $loop->index }}</td>

                    <td>{{ $user->c_name }}</td>

                    <td>{{ $user->c_username }}</td>

                    <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                    @canany(['user-management.edit', 'user-management.delete'])
                    <td>
                        @can('user-management.edit')
                        <a href="{{ route('admin.users.edit', $user->n_role_id) }}"
                            class="btn btn-primary btn-sm action-btn">
                            Edit
                        </a>
                        @endcan
                        @can('user-management.delete')
                        <form action="{{ route('admin.users.destroy', $user->n_role_id) }}" method="POST"
                            class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm action-btn"
                                onclick="return confirm('Delete this user?')">
                                Delete
                            </button>

                        </form>
                        @endcan

                    </td>
                    @endcanany
                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No Users Found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
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
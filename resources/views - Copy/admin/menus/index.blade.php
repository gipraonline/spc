@extends('layouts.app')

@section('content')

<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Menu Management
        </h5>
        @can('menu-management.create')
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
            + Create Menu
        </a>
        @endcan
    </div>

    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <table class="table table-bordered align-middle">

            <thead>
                <tr>
                    <th width="80">#</th>
                    <th>Menu Name</th>
                    <th>Route</th>
                    <th>Icon</th>
                    @canany(['menu-management.edit', 'menu-management.delete'])
                    <th width="180">Action</th>
                    @endcanany
                </tr>
            </thead>

            <tbody>

                @forelse($menus as $parent)

                {{-- Parent Menu --}}
                <tr class="table-primary">
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <i class="ti ti-folder me-2"></i>
                        {{ $parent->name }}
                    </td>

                    <td>{{ $parent->route_name ?? '-' }}</td>

                    <td>{{ $parent->icon ?? '-' }}</td>
                    @canany(['menu-management.edit', 'menu-management.delete'])
                    <td>
                        @can('menu-management.edit')
                        <a href="{{ route('admin.menus.edit',$parent->id) }}" class="btn btn-sm btn-primary action-btn">
                            Edit
                        </a>
                        @endcan
                        @can('menu-management.delete')
                        <form action="{{ route('admin.menus.destroy',$parent->id) }}" method="POST" class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger action-btn action-btn"
                                onclick="return confirm('Delete this menu?')">
                                Delete
                            </button>

                        </form>
                        @endcan

                    </td>
                    @endcanany
                </tr>

                {{-- Child Menus --}}
                @foreach($parent->children as $child)

                <tr>

                    <td></td>

                    <td style="padding-left:60px;">
                        ├──
                        <i class="ti ti-chevron-right me-1"></i>
                        {{ $child->name }}
                    </td>

                    <td>{{ $child->route_name }}</td>

                    <td>{{ $child->icon }}</td>
                    @canany(['menu-management.edit', 'menu-management.delete'])
                    <td>
                        @can('menu-management.edit')
                        <a href="{{ route('admin.menus.edit',$child->id) }}" class="btn btn-sm btn-primary action-btn">
                            Edit
                        </a>
                        @endcan
                        @can('menu-management.delete')
                        <form action="{{ route('admin.menus.destroy',$child->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-danger action-btn"
                                onclick="return confirm('Delete this menu?')">
                                Delete
                            </button>
                        </form>
                        @endcan
                    </td>
                    @endcanany

                </tr>

                @endforeach

                @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No Menus Found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $menus->links('pagination::bootstrap-5') }}
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
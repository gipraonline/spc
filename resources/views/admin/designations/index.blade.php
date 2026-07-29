@extends('layouts.app')

@section('content')
<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Designations</h5>
        @can('designations.create')
        <a href="{{ route('admin.designations.create') }}" class="btn btn-primary">
             Add Designation
        </a>
        @endcan

    </div>
    <div class="card-body p-4">
        @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
        @endif
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
                        </th>
                        <!-- <th class="border-bottom-0">
              <h6 class="fw-semibold mb-0">Actions</h6>
            </th> -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($designations as $designation)
                    <tr>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $designation->c_designation }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <span
                                class="badge {{ $designation->c_status === 'Y' ? 'bg-success' : 'bg-danger' }} rounded-3 fw-semibold">
                                {{ ucfirst($designation->c_status) }}
                            </span>
                        </td>
                        {{-- <td class="border-bottom-0">
                <a href="{{ route('admin.designations.edit', $designation) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.designations.destroy', $designation) }}"
                            class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger ms-2"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        </td> --}}
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No designations found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

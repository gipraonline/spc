@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success mx-0">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mx-0">{{ session('error') }}</div>
@endif
<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">Designations</h5>
        @can('designations.create')
        <a href="{{ route('admin.designations.create') }}" class="btn buttonSpc">
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
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Reports To</h6>
                        </th>
                        @canany(['designations.edit', 'designations.delete'])
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Actions</h6>
                        </th>
                        @endcanany
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
                        <td class="border-bottom-0">
                            {{ $designation->parent->c_designation ?? '—' }}
                        </td>
                        @canany(['designations.edit', 'designations.delete'])
                        <td class="border-bottom-0">
                            @can('designations.edit')
                            <a href="{{ route('admin.designations.edit', $designation) }}" class="btn btn-sm btn-primary">Edit</a>
                            @endcan
                            @can('designations.delete')
                            <form method="POST" action="{{ route('admin.designations.destroy', $designation) }}"
                                class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger ms-2"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @endcan
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No designations found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

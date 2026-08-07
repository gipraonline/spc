@extends('layouts.app')

@section('content')

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            {{ isset($selectedMenu) ? 'Add Permission' : 'Create Permission' }}
        </h4>

        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf

            {{-- Menu --}}
            <div class="mb-3">

                <label class="form-label">Menu</label>

                @if(!empty($selectedModule))

                <input type="text" class="form-control" value="{{ ucfirst($selectedModule) }}" readonly>

                <input type="hidden" name="module" value="{{ strtolower($selectedModule) }}">

                @else

                <select name="module" class="form-select" required>

                    <option value="">Select Menu</option>

                    @foreach($parents as $parent)

                    <option value="{{ strtolower($parent->name) }}">
                        {{ $parent->name }}
                    </option>

                    @foreach($parent->children as $child)

                    <option value="{{ strtolower($child->name) }}">
                        └── {{ $child->name }}
                    </option>

                    @endforeach

                    @endforeach

                </select>

                @endif

            </div>

            @php
            $actions = [
            // CRUD
            'view',
            'create',
            'edit',
            'delete',

            // Reports
            'export',
            'upload',
            'calculate',
            'approve',
            'reject',
            'view-details',
            'confirm',
            'cancel',
            'process-batch',
            'add-sale',
            'follow-up',
            //Field Log
            'check-in',

            // Dashboard Cards
            'employees-card',
            'stores-card',
            'products-card',
            'sales-card',
            'incentives-card',
            'centreal-sales-card',
            'centreal-incentives-card',
            'vanitham-sales-card',
            'vanitham-incentives-card',

            // Dashboard Data Cards
            'recent-sales-card',
            'top-stores-card',
            'pending-sales-card',
            'top-centreal-performers-card',
            'top-vanitham-performers-card',
            ];
            @endphp
            <div class="mb-3">

                <label class="form-label">Actions</label>

                <div class="row">

                    @foreach($actions as $action)

                    @if(!isset($existingActions) || !in_array($action,$existingActions))

                    <div class="col-md-3 mb-2">

                        <div class="form-check">

                            <input class="form-check-input" type="checkbox" name="actions[]" value="{{ $action }}"
                                id="{{ $action }}">

                            <label class="form-check-label" for="{{ $action }}">
                                {{ ucfirst($action) }}
                            </label>

                        </div>

                    </div>

                    @endif

                    @endforeach

                </div>

            </div>

            @if(isset($existingActions) && count($existingActions))

            <div class="alert alert-info">

                <strong>Already Exists :</strong>

                @foreach($existingActions as $action)
                <span class="badge bg-success">
                    {{ ucfirst($action) }}
                </span>
                @endforeach

            </div>

            @endif

            <button class="btn buttonSpc">
                Save
            </button>

        </form>

    </div>

</div>

@endsection
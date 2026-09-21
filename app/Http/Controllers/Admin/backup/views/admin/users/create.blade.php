@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="fas fa-user-plus text-primary me-2"></i>
                    Create User
                </h4>

                <small class="text-muted">
                    Create a new user and assign a role.
                </small>
            </div>

            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

        <div class="card-body bg-white">

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">
                            Employee <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>

                            <select class="form-select" id="employee" name="employee_id" required>

                                <option value="">Select Employee</option>

                                @foreach($employees as $employee)

                                <option value="{{ $employee->n_employee_id }}"
                                    data-email="{{ $employee->c_employee_email }}"
                                    {{ old('employee_id') == $employee->n_employee_id ? 'selected' : '' }}>
                                    {{ $employee->c_employee_code }}
                                    -
                                    {{ $employee->c_employee_name }}
                                </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">
                            Email Address
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>

                            <input type="email" id="username" class="form-control" readonly
                                placeholder="Employee Email">

                        </div>

                    </div>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Assign Role <span class="text-danger">*</span>
                    </label>

                    <select class="form-select" name="role" required>

                        <option value="">Select Role</option>

                        @foreach($roles as $role)

                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        The selected role determines the user's menu access and permissions.
                    </small>

                </div>

                <hr>

                <div class="d-flex justify-content-end">

                    <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2">
                        Cancel
                    </a>

                    <button type="submit" class="btn buttonSpc px-4">

                        <i class="fas fa-save me-1"></i>

                        Create User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-----------------------------------------------------------
 Create User Form:
    Allows admin users to select an employee, assign a role,
    and submit user creation details with validation handling. 
-------------------------------------------------------------->


<script>
document.addEventListener('DOMContentLoaded', function() {

    const employee = document.getElementById('employee');
    const email = document.getElementById('username');

    function fillEmail() {

        const option = employee.options[employee.selectedIndex];

        email.value = option ? option.dataset.email : '';

    }

    employee.addEventListener('change', fillEmail);

    fillEmail();

});
</script>

@endsection
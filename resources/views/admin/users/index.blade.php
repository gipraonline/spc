@extends('layouts.app')

@section('content')
<style>
.action-btn {
    width: 60px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}
</style>
<div class="card w-100">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">User Management</h5>

        <div class="d-flex gap-2">

            @if($hasActivePasswords)
            <button type="button" id="copyAllLoginDetailsBtn" class="btn buttonSpc"
                data-url="{{ route('admin.users.copy-all-login-details') }}">
                📋 Copy All Login Details
            </button>
            @else
            <button type="button" class="btn buttonSpc" disabled>
                🔒 No Active Passwords
            </button>
            @endif

            @can('user-management.create')
            <a href="{{ route('admin.users.create') }}" class="btn buttonSpc">
                + Create User
            </a>
            @endcan

        </div>

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
                    <th>Password</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                <tr>

                    <td>{{ $users->firstItem() + $loop->index }}</td>

                    <td>{{ $user->c_name }}</td>

                    <td>{{ $user->c_username }}</td>

                    <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                    <td>
                        @if(
                        $user->initial_password &&
                        $user->initial_password_expires_at &&
                        now()->lessThanOrEqualTo($user->initial_password_expires_at)
                        )
                        <button type="button" class="btn btn-sm btn-outline-primary show-password-btn"
                            data-url="{{ route('admin.users.show-password', $user) }}">
                            👁 Show Password
                        </button>
                        @else
                        <span class="text-muted">
                            🔒 Expired
                        </span>
                        @endif
                    </td>
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

<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    User Login Password
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    This password can only be viewed temporarily.
                    Do not share it with unauthorized persons.
                </div>

                <label class="form-label">
                    Password
                </label>

                <div class="input-group">
                    <input type="text" id="displayPassword" class="form-control" readonly>

                    <button type="button" class="btn btn-outline-secondary" id="copyPasswordBtn">
                        Copy
                    </button>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        Available until:
                    </small>

                    <strong id="passwordExpiry"></strong>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const modalElement = document.getElementById('passwordModal');
    const passwordModal = new bootstrap.Modal(modalElement);

    const passwordInput = document.getElementById('displayPassword');
    const expiryElement = document.getElementById('passwordExpiry');
    const copyButton = document.getElementById('copyPasswordBtn');

    // =====================================================
    // SHOW INDIVIDUAL PASSWORD
    // =====================================================

    document.querySelectorAll('.show-password-btn').forEach(function(button) {

        button.addEventListener('click', function() {

            const url = this.dataset.url;

            passwordInput.value = '';
            expiryElement.textContent = '';

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message || 'Unable to retrieve password.'
                        );
                    }

                    return data;
                })
                .then(data => {

                    if (data.success) {

                        passwordInput.value = data.password;
                        expiryElement.textContent = data.expires_at;

                        passwordModal.show();

                    } else {

                        alert(
                            data.message ||
                            'Unable to retrieve password.'
                        );
                    }
                })
                .catch(error => {

                    console.error(error);

                    alert(
                        error.message ||
                        'Something went wrong while retrieving the password.'
                    );
                });
        });
    });


    // =====================================================
    // COPY INDIVIDUAL PASSWORD
    // =====================================================

    copyButton.addEventListener('click', function() {

        const password = passwordInput.value;

        if (!password) {
            return;
        }

        navigator.clipboard.writeText(password)
            .then(function() {

                copyButton.textContent = 'Copied!';

                setTimeout(function() {
                    copyButton.textContent = 'Copy';
                }, 1500);

            })
            .catch(function() {

                alert('Unable to copy password.');

            });
    });


    // =====================================================
    // COPY ALL LOGIN DETAILS
    // =====================================================

    const copyAllButton =
        document.getElementById('copyAllLoginDetailsBtn');

    if (copyAllButton) {

        copyAllButton.addEventListener('click', function() {

            const url = this.dataset.url;

            if (!url) {
                alert('Copy URL is missing.');
                return;
            }

            const originalText = copyAllButton.innerHTML;

            copyAllButton.disabled = true;
            copyAllButton.innerHTML = 'Preparing...';

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message ||
                            'Unable to retrieve login details.'
                        );
                    }

                    return data;
                })
                .then(data => {

                    if (!data.success || !data.details) {
                        throw new Error(
                            data.message ||
                            'No active login details available.'
                        );
                    }

                    return navigator.clipboard.writeText(data.details);
                })
                .then(() => {

                    copyAllButton.innerHTML = '✓ Copied!';

                    setTimeout(() => {

                        copyAllButton.innerHTML = originalText;
                        copyAllButton.disabled = false;

                    }, 2000);

                })
                .catch(error => {

                    console.error(
                        'Copy login details error:',
                        error
                    );

                    alert(
                        error.message ||
                        'Unable to copy login details.'
                    );

                    copyAllButton.innerHTML = originalText;
                    copyAllButton.disabled = false;
                });
        });
    }


    // =====================================================
    // CLEAR PASSWORD WHEN MODAL CLOSES
    // =====================================================

    modalElement.addEventListener('hidden.bs.modal', function() {

        passwordInput.value = '';
        expiryElement.textContent = '';

    });

});
</script>
@endpush
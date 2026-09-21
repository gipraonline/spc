@extends('layouts.app')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Account Setting</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none"
                                href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Account Setting</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <!-- Profile Update -->
        <!-- <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100 position-relative overflow-hidden">
        <div class="card-body p-4">
          <h5 class="card-title fw-semibold">Personal Details</h5>
          <p class="card-subtitle mb-4">Update your account's profile information and email address.</p>

          <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-4">
              <label for="name" class="form-label fw-semibold">Your Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}"
                required autofocus>
              @error('name')
                <div class="text-danger mt-1 fs-2">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="email" class="form-label fw-semibold">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}"
                required>
              @error('email')
                <div class="text-danger mt-1 fs-2">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
              <button type="submit" class="btn btn-primary">Save</button>
              @if (session('status') === 'profile-updated')
                <p class="mb-0 text-success fw-semibold">Saved.</p>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div> -->

        <!-- Password Update -->
        <!-- <div class="col-lg-6 d-flex align-items-stretch"> -->
        <div class="card w-100 position-relative overflow-hidden">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold">Change Password</h5>
                <p class="card-subtitle mb-4">Ensure your account is using a long, random password to stay secure.</p>

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 password-input" id="current_password"
                                name="current_password">

                            <span class="toggle-password">
                                <i class="ti ti-eye"></i>
                            </span>
                        </div>

                        @error('current_password', 'updatePassword')
                        <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 password-input" id="password"
                                name="password">

                            <span class="toggle-password">
                                <i class="ti ti-eye"></i>
                            </span>
                        </div>

                        @error('password', 'updatePassword')
                        <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 password-input" id="password_confirmation"
                                name="password_confirmation">

                            <span class="toggle-password">
                                <i class="ti ti-eye"></i>
                            </span>
                        </div>

                        @error('password_confirmation', 'updatePassword')
                        <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                        @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <strong>Success!</strong> Your password has been updated successfully. Please log out and
                            log in again using your new password.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.toggle-password').forEach(function(toggle) {

        toggle.addEventListener('click', function() {

            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }

        });

    });

});
</script>
<style>
.toggle-password {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    z-index: 10;
}

.toggle-password:hover {
    color: #0d6efd;
}

.password-input {
    padding-right: 45px;
}
</style>
@endsection
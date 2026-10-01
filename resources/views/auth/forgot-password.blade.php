@extends('auth.layouts.recovery', ['step' => 1])

@section('title', 'Forgot Password')

@section('content')
    <div class="badge" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="3"/><polyline points="3 7 12 13 21 7"/></svg>
    </div>

    <h1>Forgot your password?</h1>
    <p class="subtitle">No worries. Enter your registered email and we'll send you a 6-digit verification code.</p>

    <form method="POST" action="{{ route('password.email') }}" id="requestForm" novalidate>
        @csrf

        <div class="form-group">
            <label for="email">Email / Username</label>
            <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="text" inputmode="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="e.g. name@domain.com" autocomplete="username" autocapitalize="off" spellcheck="false"
                    required autofocus>
                <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            @error('email')
                <p class="error-text" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn" id="submitBtn">
            <span class="spinner"></span><span>Send Verification Code</span>
        </button>
    </form>

    <p class="foot">
        <a href="{{ route('login') }}" class="back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to sign in
        </a>
    </p>
@endsection

@push('scripts')
    <script>
        document.getElementById('requestForm').addEventListener('submit', function (e) {
            var btn = document.getElementById('submitBtn');
            if (btn.disabled) { e.preventDefault(); return; }
            btn.disabled = true; btn.classList.add('loading');
            btn.querySelector('span:last-child').textContent = 'Sending…';
        });
    </script>
@endpush

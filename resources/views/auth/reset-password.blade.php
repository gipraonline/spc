@extends('auth.layouts.recovery', ['step' => 3])

@section('title', 'Reset Password')

@push('styles')
    <style>
        .meter { height: 6px; border-radius: 6px; background: #ecefec; overflow: hidden; margin: 10px 0 8px; }
        .meter span { display: block; height: 100%; width: 0; border-radius: 6px; transition: width .3s, background .3s; }
        .rules { list-style: none; display: grid; grid-template-columns: 1fr; gap: 4px; font-size: 12.5px; color: #8a938a; margin-left: 4px; }
        .rules li { display: flex; align-items: center; gap: 8px; transition: color .2s; }
        .rules li::before {
            content: ''; width: 14px; height: 14px; border-radius: 50%; flex: none;
            border: 1.5px solid #c9d1c9; transition: all .2s;
        }
        .rules li.ok { color: var(--green-700); }
        .rules li.ok::before {
            background: var(--green-600) url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'><polyline points='20 6 9 17 4 12'/></svg>") center/9px no-repeat;
            border-color: var(--green-600);
        }
        .match { font-size: 12.5px; margin: 6px 0 0 4px; min-height: 18px; }
        .match.good { color: var(--green-700); } .match.bad { color: var(--danger); }
    </style>
@endpush

@section('content')
    <div class="badge" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="3"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/><circle cx="12" cy="16" r="1.2" fill="currentColor"/></svg>
    </div>

    <h1>Create new password</h1>
    <p class="subtitle">Email verified. Choose a strong password you haven't used before.</p>

    <form method="POST" action="{{ route('password.store') }}" id="resetForm" novalidate>
        @csrf

        <div class="form-group">
            <label for="password">New password</label>
            <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                <input type="password" id="password" name="password" placeholder="Enter new password"
                    autocomplete="new-password" required autofocus>
                <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="3"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                <button type="button" class="toggle" data-target="password" aria-label="Show password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            @error('password')
                <p class="error-text" role="alert">{{ $message }}</p>
            @enderror

            <div class="meter" aria-hidden="true"><span id="meterBar"></span></div>
            <ul class="rules">
                <li data-rule="len">At least 8 characters</li>
                <li data-rule="case">Upper &amp; lower case letters</li>
                <li data-rule="num">At least one number</li>
            </ul>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm password</label>
            <div class="field">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Re-enter new password" autocomplete="new-password" required>
                <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <button type="button" class="toggle" data-target="password_confirmation" aria-label="Show password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            <p class="match" id="matchMsg" aria-live="polite"></p>
        </div>

        <button type="submit" class="btn" id="submitBtn">
            <span class="spinner"></span><span>Reset Password</span>
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
        (function () {
            var pw = document.getElementById('password');
            var cf = document.getElementById('password_confirmation');
            var bar = document.getElementById('meterBar');
            var msg = document.getElementById('matchMsg');
            var rules = {
                len: function (v) { return v.length >= 8; },
                'case': function (v) { return /[a-z]/.test(v) && /[A-Z]/.test(v); },
                num: function (v) { return /\d/.test(v); }
            };

            function strength() {
                var v = pw.value, score = 0;
                Object.keys(rules).forEach(function (k) {
                    var ok = rules[k](v);
                    document.querySelector('[data-rule="' + k + '"]').classList.toggle('ok', ok);
                    if (ok) score++;
                });
                if (/[^A-Za-z0-9]/.test(v)) score++;          // symbols are a bonus
                if (v.length >= 12) score++;                   // length is a bonus
                var pct = Math.min(100, Math.round(score / 5 * 100));
                bar.style.width = (v ? Math.max(pct, 12) : 0) + '%';
                bar.style.background = score <= 2 ? '#e53935' : score === 3 ? '#f9a825' : '#1F5C2E';
            }
            function match() {
                if (!cf.value) { msg.textContent = ''; msg.className = 'match'; return; }
                var same = pw.value === cf.value;
                msg.textContent = same ? 'Passwords match' : 'Passwords do not match yet';
                msg.className = 'match ' + (same ? 'good' : 'bad');
            }
            pw.addEventListener('input', function () { strength(); match(); });
            cf.addEventListener('input', match);

            document.querySelectorAll('.toggle').forEach(function (t) {
                t.addEventListener('click', function () {
                    var input = document.getElementById(t.dataset.target);
                    var show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    t.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                    t.style.color = show ? '#1F5C2E' : '';
                });
            });

            document.getElementById('resetForm').addEventListener('submit', function (e) {
                var btn = document.getElementById('submitBtn');
                if (btn.disabled) { e.preventDefault(); return; }
                btn.disabled = true; btn.classList.add('loading');
                btn.querySelector('span:last-child').textContent = 'Saving…';
            });
        })();
    </script>
@endpush

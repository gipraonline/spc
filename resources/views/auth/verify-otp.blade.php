@extends('auth.layouts.recovery', ['step' => 2])

@section('title', 'Verify Email')

@push('styles')
    <style>
        .otp { display: flex; gap: 10px; justify-content: center; margin: 4px 0 6px; }
        .otp input {
            width: 100%; max-width: 52px; height: 58px; text-align: center;
            font-size: 24px; font-weight: 700; color: var(--green-700);
            border: 1.5px solid var(--line); border-radius: 14px; background: #fdfdfd; outline: none;
            transition: all .2s; caret-color: var(--green-600);
        }
        .otp input:focus { border-color: var(--green-600); background: #fff; box-shadow: 0 0 0 4px rgba(15,125,61,.13); transform: translateY(-1px); }
        .otp input.filled { border-color: var(--green-500); background: var(--green-50); }
        .otp.has-error input { border-color: var(--danger); background: var(--danger-bg); }
        .otp.shake { animation: shake .45s; }
        .otp-msg { text-align: center; min-height: 20px; margin-bottom: 18px; }
        .resend { text-align: center; margin-top: 20px; font-size: 14px; color: var(--muted); }
        .resend form { display: inline; }
        .meta { text-align: center; font-size: 12.5px; color: #8a938a; margin-top: 6px; }
        @media (max-width: 400px) { .otp { gap: 6px; } .otp input { height: 52px; font-size: 20px; } }
    </style>
@endpush

@section('content')
    <div class="badge" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
    </div>

    <h1>Verify your email</h1>
    <p class="subtitle">
        Enter the {{ $length }}-digit code we sent to
        @if ($masked) <strong>{{ $masked }}</strong>@else your registered email @endif.
        <br>It expires in {{ $minutes }} minutes.
    </p>

    <form method="POST" action="{{ route('password.otp.verify') }}" id="otpForm" novalidate>
        @csrf
        <input type="hidden" name="code" id="code">

        <div class="otp {{ $errors->has('code') ? 'has-error shake' : '' }}" id="otpBoxes" role="group" aria-label="Verification code">
            @for ($i = 0; $i < $length; $i++)
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                    aria-label="Digit {{ $i + 1 }}"
                    @if ($i === 0) autocomplete="one-time-code" autofocus @else autocomplete="off" @endif>
            @endfor
        </div>

        <div class="otp-msg">
            @error('code')
                <p class="error-text" role="alert" style="margin:0">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn" id="verifyBtn">
            <span class="spinner"></span><span>Verify &amp; Continue</span>
        </button>
    </form>

    <div class="resend">
        Didn't get the code?
        <form method="POST" action="{{ route('password.otp.resend') }}" id="resendForm">
            @csrf
            <button type="submit" class="linkish" id="resendBtn" disabled>Resend code</button>
        </form>
    </div>
    <p class="meta">Check your spam folder if it doesn't arrive within a minute.</p>

    <p class="foot">
        <a href="{{ route('password.request') }}" class="back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Use a different email
        </a>
    </p>
@endsection

@push('scripts')
    <script>
        (function () {
            var boxes = Array.prototype.slice.call(document.querySelectorAll('#otpBoxes input'));
            var hidden = document.getElementById('code');
            var form = document.getElementById('otpForm');
            var btn = document.getElementById('verifyBtn');

            function sync() {
                hidden.value = boxes.map(function (b) { return b.value; }).join('');
                boxes.forEach(function (b) { b.classList.toggle('filled', b.value !== ''); });
            }
            function clearError() { document.getElementById('otpBoxes').classList.remove('has-error', 'shake'); }

            boxes.forEach(function (box, i) {
                box.addEventListener('input', function () {
                    clearError();
                    var v = box.value.replace(/\D/g, '');
                    // typing/auto-fill of several digits at once (e.g. SMS/OTP autofill)
                    if (v.length > 1) { fill(v, i); return; }
                    box.value = v;
                    if (v && i < boxes.length - 1) boxes[i + 1].focus();
                    sync();
                });
                box.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !box.value && i > 0) { boxes[i - 1].focus(); boxes[i - 1].value = ''; sync(); }
                    if (e.key === 'ArrowLeft' && i > 0) { e.preventDefault(); boxes[i - 1].focus(); }
                    if (e.key === 'ArrowRight' && i < boxes.length - 1) { e.preventDefault(); boxes[i + 1].focus(); }
                });
                box.addEventListener('focus', function () { box.select(); });
                box.addEventListener('paste', function (e) {
                    e.preventDefault();
                    fill((e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, ''), i);
                });
            });

            function fill(digits, start) {
                for (var k = 0; k < digits.length && start + k < boxes.length; k++) boxes[start + k].value = digits[k];
                boxes[Math.min(start + digits.length, boxes.length - 1)].focus();
                sync();
            }

            form.addEventListener('submit', function (e) {
                sync();
                if (hidden.value.length !== boxes.length) {
                    e.preventDefault();
                    var wrap = document.getElementById('otpBoxes');
                    wrap.classList.remove('shake'); void wrap.offsetWidth; wrap.classList.add('has-error', 'shake');
                    var first = boxes.filter(function (b) { return !b.value; })[0]; if (first) first.focus();
                    return;
                }
                if (btn.disabled) { e.preventDefault(); return; }
                btn.disabled = true; btn.classList.add('loading');
                btn.querySelector('span:last-child').textContent = 'Verifying…';
            });

            // ---- resend countdown ----
            var resend = document.getElementById('resendBtn');
            var left = {{ (int) $cooldown }};
            function tick() {
                if (left > 0) {
                    resend.disabled = true;
                    var m = Math.floor(left / 60), s = left % 60;
                    resend.textContent = 'Resend in ' + m + ':' + (s < 10 ? '0' : '') + s;
                    left--; setTimeout(tick, 1000);
                } else {
                    resend.disabled = false; resend.textContent = 'Resend code';
                }
            }
            tick();
        })();
    </script>
@endpush

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Forgot Password') | SPC</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/images/logos/fav.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --green-700: #1F3D14;
            --green-600: #4E7A33;
            --green-500: #5E8D3D;
            --green-100: #E4F3EB;
            --green-50: #F2F9F5;
            --ink: #22352C;
            --muted: #61756B;
            --line: #dde8e1;
            --danger: #C03434;
            --danger-bg: #FBE7E4;
            --ok-bg: #DCF3E4;
            --bg: #F0F5F1;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        h1, h2, h3, h4, .step .label { font-family: 'Kanit', 'Outfit', sans-serif; }

        body {
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 16px 64px;
            position: relative;
            overflow-x: hidden;
        }

        /* soft glowing background, same feel as the sign-in page */
        body::before, body::after {
            content: '';
            position: fixed;
            width: 40vw; height: 40vw;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .38;
            z-index: -1;
            animation: drift 8s infinite alternate;
        }
        body::before { background: radial-gradient(circle, var(--green-600), transparent 70%); top: -10%; left: -10%; }
        body::after  { background: radial-gradient(circle, var(--green-500), transparent 70%); bottom: -10%; right: -10%; animation-delay: -4s; }

        @keyframes drift { from { transform: scale(1) translate(0,0); } to { transform: scale(1.1) translate(2%,2%); } }
        @keyframes rise  { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
        @keyframes shake { 0%,100% { transform: none; } 20%,60% { transform: translateX(-6px); } 40%,80% { transform: translateX(6px); } }

        .card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 24px;
            padding: 38px 40px 34px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 18px 50px rgba(7, 78, 48, .14), 0 2px 8px rgba(0,0,0,.06);
            border: 1px solid rgba(15, 125, 61, .08);
            animation: rise .6s ease both;
        }
        .card::before {
            content: '';
            position: absolute; inset: 0 0 auto 0; height: 5px;
            background: linear-gradient(90deg, var(--green-600), var(--green-500));
        }

        .brand { text-align: center; margin-bottom: 22px; }
        .brand img { width: 150px; height: auto; display: inline-block; }

        /* ---------- progress steps ---------- */
        .steps { display: flex; list-style: none; margin: 0 4px 26px; }
        .step { flex: 1; text-align: center; position: relative; font-size: 12px; color: #9aa39a; font-weight: 500; }
        .step .dot {
            width: 30px; height: 30px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            background: #eef1ee; color: #8a938a; font-size: 13px; font-weight: 700;
            position: relative; z-index: 1; transition: all .3s;
            border: 2px solid #eef1ee;
        }
        .step .label { display: block; margin-top: 6px; }
        .step:not(:last-child)::after {
            content: ''; position: absolute; top: 14px; left: calc(50% + 20px); right: calc(-50% + 20px);
            height: 2px; background: #e3e8e3;
        }
        .step.is-done:not(:last-child)::after { background: var(--green-500); }
        .step.is-active .dot { background: #fff; border-color: var(--green-600); color: var(--green-600); box-shadow: 0 0 0 4px rgba(15,125,61,.12); }
        .step.is-active { color: var(--green-700); }
        .step.is-done .dot { background: var(--green-600); border-color: var(--green-600); color: #fff; }
        .step.is-done { color: var(--green-700); }

        /* ---------- heading ---------- */
        .badge {
            width: 64px; height: 64px; border-radius: 20px; margin: 0 auto 16px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--green-100), #f6fbf3);
            color: var(--green-600);
            box-shadow: inset 0 0 0 1px rgba(15,125,61,.12);
        }
        .badge svg { width: 30px; height: 30px; }
        h1 { font-size: 24px; font-weight: 700; color: var(--ink); text-align: center; margin-bottom: 8px; }
        .subtitle { font-size: 14px; color: var(--muted); text-align: center; line-height: 1.6; margin-bottom: 26px; }
        .subtitle strong { color: var(--green-700); font-weight: 600; }

        /* ---------- alerts ---------- */
        .alert {
            display: flex; gap: 10px; align-items: flex-start;
            padding: 12px 14px; border-radius: 12px; font-size: 13.5px; line-height: 1.5;
            margin-bottom: 20px;
        }
        .alert svg { flex: none; width: 18px; height: 18px; margin-top: 1px; }
        .alert-ok { background: var(--ok-bg); color: #1b5e20; border: 1px solid #cfe8d2; }
        .alert-err { background: var(--danger-bg); color: var(--danger); border: 1px solid #f5c6c6; }

        /* ---------- form ---------- */
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; color: var(--ink); margin: 0 0 8px 4px; }
        .field { position: relative; }
        .field .lead {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; color: #9aa39a; pointer-events: none; transition: color .2s;
        }
        .field input {
            width: 100%; padding: 14px 44px 14px 46px;
            border-radius: 12px; border: 1px solid var(--line); background: #fdfdfd;
            font-size: 15px; color: var(--ink); outline: none; transition: all .25s;
        }
        .field input:focus { border-color: var(--green-600); background: #fff; box-shadow: 0 0 0 4px rgba(15,125,61,.13); }
        .field input:focus ~ .lead, .field:focus-within .lead { color: var(--green-600); }
        .field.has-error input { border-color: var(--danger); }
        .toggle {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            background: none; border: 0; cursor: pointer; padding: 8px; color: #8a938a; border-radius: 8px; display: flex;
        }
        .toggle:hover { color: var(--green-700); background: var(--green-50); }
        .toggle svg { width: 18px; height: 18px; }
        .hint { font-size: 12.5px; color: var(--muted); margin: 6px 0 0 4px; }
        .error-text { font-size: 12.5px; color: var(--danger); margin: 6px 0 0 4px; }

        .btn {
            width: 100%; padding: 15px; border: 0; border-radius: 12px; cursor: pointer;
            background: linear-gradient(135deg, var(--green-500), #1F5C2E);
            color: #fff; font-size: 16px; font-weight: 700; letter-spacing: .2px;
            box-shadow: 0 12px 24px rgba(14,107,75,.26);
            transition: transform .25s cubic-bezier(.175,.885,.32,1.275), box-shadow .25s, opacity .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 16px 30px rgba(14,107,75,.32); }
        .btn:active { transform: none; }
        .btn[disabled] { opacity: .65; cursor: not-allowed; transform: none; }
        .spinner {
            width: 16px; height: 16px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,.45); border-top-color: #fff;
            animation: spin .7s linear infinite; display: none;
        }
        .btn.loading .spinner { display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .foot { text-align: center; margin-top: 24px; font-size: 14px; color: var(--muted); }
        .foot a, .linkish {
            color: var(--green-600); font-weight: 600; text-decoration: none;
            background: none; border: 0; cursor: pointer; font-size: inherit; font-family: inherit; padding: 0;
        }
        .foot a:hover, .linkish:hover:not([disabled]) { color: var(--green-700); text-decoration: underline; }
        .linkish[disabled] { color: #9aa39a; cursor: not-allowed; }
        .back { display: inline-flex; align-items: center; gap: 6px; }
        .back svg { width: 15px; height: 15px; }

        .copy { position: absolute; bottom: 14px; left: 0; right: 0; text-align: center; font-size: 14px; font-weight: 600; color: #000; }

        @media (max-width: 480px) {
            .card { padding: 30px 22px 26px; border-radius: 20px; }
            .copy { position: static; margin-top: 22px; }
            body { justify-content: flex-start; padding-top: 24px; }
        }
        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }
    </style>
    @stack('styles')
</head>

<body>

    <main class="card">
        <div class="brand">
            <a href="{{ route('login') }}" aria-label="SPC – back to sign in">
                <img src="{{ asset('dist/images/logos/spclogo.png') }}" alt="SPC">
            </a>
        </div>

        @php $current = $step ?? 1; @endphp
        <ol class="steps" aria-label="Password recovery progress">
            @foreach ([1 => 'Email', 2 => 'Verify', 3 => 'New password'] as $n => $label)
                <li class="step {{ $n < $current ? 'is-done' : '' }} {{ $n === $current ? 'is-active' : '' }}"
                    @if ($n === $current) aria-current="step" @endif>
                    <span class="dot">
                        @if ($n < $current)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            {{ $n }}
                        @endif
                    </span>
                    <span class="label">{{ $label }}</span>
                </li>
            @endforeach
        </ol>

        @if (session('status'))
            <div class="alert alert-ok" role="status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <p class="copy">Copyright © {{ date('Y') }} SPC All Rights Reserved.</p>

    @stack('scripts')
</body>

</html>

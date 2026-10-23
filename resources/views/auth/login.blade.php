<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign in · SPC Universal</title>

    <!-- PWA: manifest + install/theme metadata (auto "Add to Home Screen") -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#166534">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SPC">

    <!-- PWA: capture the native install prompt as early as possible -->
    <script>
        window.__spcDeferredPrompt = null;
        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            window.__spcDeferredPrompt = e;
            window.dispatchEvent(new Event('spc:install-ready'));
        });
        window.addEventListener('appinstalled', function () {
            window.__spcDeferredPrompt = null;
            try { localStorage.setItem('spc-install-done', '1'); } catch (err) {}
        });
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="SPC" />
    <meta name="keywords" content="spc" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" type="image/png" href="{{asset('dist/images/logos/fav.png')}}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
       :root {
                --primary-blue: #17694a;
                --primary-red: #0e3d26;
                --accent-glow: rgba(14, 61, 38, 0.16);
                --bg-color: #f4faf6;
                --card-bg: #ffffff;
                --text-dark: #08301e;
                --text-grey: #6e7a71;
                --input-border: #dde5de;
                --shadow-soft: 0 20px 40px rgba(8, 46, 29, 0.10);
                --shadow-glow: 0 0 20px rgba(14, 61, 38, 0.18);
            }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1, h2, h3, h4 {
            font-family: 'Plus Jakarta Sans', 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Subtle Light-Up Background Elements */
        body::before,
        body::after {
            content: '';
            position: absolute;
            width: 40vw;
            height: 40vw;
            border-radius: 50%;
            z-index: -1;
            filter: blur(80px);
            opacity: 0.4;
            animation: pulse 8s infinite alternate;
        }

        body::before {
            background: radial-gradient(circle, var(--primary-blue), transparent 70%);
            top: -10%;
            left: -10%;
        }

        body::after {
            background: radial-gradient(circle, var(--primary-red), transparent 70%);
            bottom: -10%;
            right: -10%;
            animation-delay: -4s;
        }

        @keyframes pulse {
            0% {
                transform: scale(1) translate(0, 0);
            }

            100% {
                transform: scale(1.1) translate(2%, 2%);
            }
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            z-index: 10;
        }

        .auth-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(0, 75, 145, 0.05);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }

        .auth-card:hover {
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08), 0 0 30px rgba(0, 75, 145, 0.1);
        }

        /* Top Glow Line */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-red));
        }

        .logo-box {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease;
        }

        .logo-box img {
            width: 200px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .auth-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .auth-header p {
            font-size: 14px;
            color: var(--text-grey);
        }

        .error-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fdecec;
            border: 1px solid #f5c6c6;
            color: #c62828;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 22px;
            animation: shakeX .4s;
        }

        .error-banner svg {
            flex: none;
        }

        @keyframes shakeX {
            0%, 100% { transform: none; }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        .status-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #eaf6ec;
            border: 1px solid #cfe8d2;
            color: #1b5e20;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 22px;
        }

        .status-banner svg {
            flex: none;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            padding-left: 4px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid var(--input-border);
            background: #fdfdfd;
            font-size: 15px;
            color: var(--text-dark);
            transition: all 0.3s ease;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: var(--primary-blue);
            background: #fff;
            box-shadow: 0 0 0 4px var(--accent-glow);
            transform: translateY(-1px);
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 13px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            cursor: pointer;
            color: var(--text-grey);
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: var(--primary-blue);
        }

        .forgot-pass {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-pass:hover {
            color: var(--primary-red);
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #1b6b49, #0e3d26);
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);

            box-shadow: 0 12px 24px rgba(14, 107, 75, 0.3);
            position: relative;
            overflow: hidden;
        }

        .submit-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 32px rgba(14, 107, 75, 0.35);
        }

        .submit-btn:hover::after {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            font-size: 14px;
            color: var(--text-grey);
        }

        .footer-text a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 700;
            margin-left: 5px;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated {
            animation: fadeInUp 0.8s ease backwards;
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 30px 20px;
            }
        }

       .copy {
    text-align: center;
    color: #000;
    font-weight: 600;
    position: absolute;
    bottom: 7px;
    font-size: 15px;
    margin-top: 20px;
    font-family: auto;
}

        @media screen and (max-width:767px) {
            .copy {
                position: inherit;
            }

            body {
                flex-direction: column;
            }

            html {
                overflow-x: hidden;
            }

            .footer-text a {
                display: block;
            }
        }

                .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }

        .password-toggle:hover {
            color: #000;
        }
    </style>
    {{-- Client reference theme — forest green + sage canvas, Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dist/css/spc-theme.css') }}?v=23">
</head>

<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="logo-box">
                <img src="{{asset('dist/images/logos/spclogo.png')}}" alt="SPC Logo">
            </div>

            <div class="auth-header animated">
                <h1>Welcome Back</h1>
                <p>Please enter your details to sign in</p>
            </div>

            @if (session('status'))
                <div class="status-banner" role="status">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-banner" role="alert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $errors->first('email') ?: $errors->first('password') ?: $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf




                <div class="form-group animated delay-1">
                    <label for="exampleInputEmail1">Email / Username</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="exampleInputEmail1" placeholder="e.g. name@domain.com"
                            value="{{ old('email') }}" required>
                    </div>
                </div>


                <div class="form-group animated delay-1">
                    <label for="exampleInputPassword1">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="exampleInputPassword1" placeholder="••••••••"
                             required>
                        <span class="password-toggle" id="togglePassword">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="options-row animated delay-2">
                    <label class="remember-me">
                        <input type="checkbox" id="flexCheckChecked" checked>
                        <span>Remember this device</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-pass">Forgot Password?</a>
                </div>



                <button type="submit" class="submit-btn animated delay-2">
                    Sign In
                </button>

                {{-- <div class="footer-text animated delay-2">
                    New to SPC?
                    <a href="{{route('register')}}"></a>
                </div> --}}
            </form>

        </div>
    </div>

  <p class="copy">Copyright © 2026 SPC All Rights Reserved.</p>

    <!--  Import Js Files -->
    <script src="{{asset('dist/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('dist/libs/simplebar/dist/simplebar.min.js')}}"></script>
    <script src="{{asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <!--  core files -->
    <script src="{{asset('dist/js/app.min.js')}}"></script>
    <script src="{{asset('dist/js/app.init.js')}}"></script>
    <script src="{{asset('dist/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('dist/js/sidebarmenu.js')}}"></script>

    <script src="{{asset('dist/js/custom.js')}}"></script>
    <script>
        $('#togglePassword').click(function(){
            let input = $('#exampleInputPassword1');
            input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        });
    </script>

    <!-- PWA: register the service worker (caching + installability) -->
    <!-- PWA: mobile-only "Install SPC App" bottom sheet — uses the native
         prompt when the browser offers one, platform instructions otherwise. -->
    <style>
        .spc-install-sheet {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            visibility: hidden;
            pointer-events: none;
        }

        .spc-install-sheet.is-open {
            visibility: visible;
            pointer-events: auto;
        }

        .spc-install-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(9, 26, 17, .55);
            -webkit-backdrop-filter: blur(3px);
            backdrop-filter: blur(3px);
            opacity: 0;
            transition: opacity .3s ease;
        }

        .spc-install-sheet.is-open .spc-install-backdrop {
            opacity: 1;
        }

        .spc-install-card {
            position: relative;
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 26px 26px 0 0;
            padding: 26px 22px calc(22px + env(safe-area-inset-bottom, 0px));
            box-shadow: 0 -18px 50px rgba(9, 26, 17, .3);
            transform: translateY(110%);
            transition: transform .38s cubic-bezier(.16, 1, .3, 1);
            text-align: center;
        }

        .spc-install-sheet.is-open .spc-install-card {
            transform: translateY(0);
        }

        .spc-install-x {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: #eef3ef;
            color: #47614f;
            font-size: 17px;
            line-height: 1;
            cursor: pointer;
        }

        .spc-install-badge {
            width: 70px;
            height: 70px;
            margin: 0 auto 14px;
            padding: 4px;
            border-radius: 21px;
            background: radial-gradient(110% 100% at 100% 0%, rgba(255, 255, 255, .16), transparent 60%), linear-gradient(160deg, #12492e, #0b3320);
            box-shadow: 0 12px 30px rgba(18, 73, 46, .38);
        }

        .spc-install-badge img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 17px;
            background: #fff;
        }

        .spc-install-card h3 {
            margin: 0 0 6px;
            font-size: 21px;
            font-weight: 800;
            color: #0d2b1c;
        }

        .spc-install-sub {
            margin: 0 auto 18px;
            max-width: 300px;
            font-size: 14px;
            line-height: 1.5;
            color: #5d6f64;
        }

        .spc-install-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: 16px;
            background: radial-gradient(110% 100% at 100% 0%, rgba(255, 255, 255, .16), transparent 60%), linear-gradient(160deg, #12492e, #0b3320);
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(18, 73, 46, .32);
            transition: transform .12s ease;
        }

        .spc-install-btn:active {
            transform: scale(.98);
        }

        .spc-install-later {
            width: 100%;
            margin-top: 8px;
            min-height: 44px;
            border: 0;
            background: transparent;
            color: #6b7d72;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .spc-install-steps {
            text-align: left;
            background: #f2f7f3;
            border: 1px solid #dde9e0;
            border-radius: 18px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }

        .spc-install-steps ol {
            margin: 0;
            padding: 0;
            list-style: none;
            counter-reset: spc-step;
        }

        .spc-install-steps li {
            counter-increment: spc-step;
            position: relative;
            padding: 9px 0 9px 40px;
            font-size: 14.5px;
            line-height: 1.45;
            color: #31473a;
        }

        .spc-install-steps li + li {
            border-top: 1px dashed #d8e4db;
        }

        .spc-install-steps li::before {
            content: counter(spc-step);
            position: absolute;
            left: 0;
            top: 8px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(160deg, #12492e, #0b3320);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spc-install-steps li i {
            color: #166534;
            margin-right: 6px;
        }

        /* Variant toggles rely on the hidden attribute — author display
           rules would otherwise override it. */
        [hidden] {
            display: none !important;
        }

        /* Phones only — never on desktop */
        @media (min-width: 768px) {
            .spc-install-sheet {
                display: none !important;
            }
        }
    </style>

    <div class="spc-install-sheet" id="spcInstallSheet" role="dialog" aria-modal="true"
        aria-labelledby="spcInstallTitle" aria-hidden="true">
        <div class="spc-install-backdrop" data-install-dismiss></div>
        <div class="spc-install-card">
            <button type="button" class="spc-install-x" data-install-dismiss aria-label="Close">&times;</button>
            <div class="spc-install-badge"><img src="{{ asset('icons/icon-192.png') }}" alt="SPC app icon"></div>
            <h3 id="spcInstallTitle">Install SPC App</h3>
            <p class="spc-install-sub">Get quick access to our website directly from your home screen.</p>

            <div id="spcInstallNative" hidden>
                <button type="button" class="spc-install-btn" id="spcInstallBtn">
                    <i class="fa-solid fa-plus"></i> Add to Home Screen
                </button>
            </div>

            <div class="spc-install-steps" id="spcInstallSteps" hidden>
                <ol id="spcInstallStepsList"></ol>
            </div>

            <button type="button" class="spc-install-btn" id="spcInstallGotIt" hidden>Got it</button>
            <button type="button" class="spc-install-later" data-install-dismiss>Maybe later</button>
        </div>
    </div>

    <script>
        (function () {
            var SHEET = 'spc-install-sheet-shown';
            var DONE = 'spc-install-done';
            var MONTH = 30 * 24 * 60 * 60 * 1000;
            var sheet = document.getElementById('spcInstallSheet');
            if (!sheet) return;

            function store(key, val) { try { localStorage.setItem(key, val); } catch (e) {} }
            function read(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }

            // Phones only; never nag when already installed or recently asked.
            var standalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone === true;
            var mobile = window.matchMedia('(max-width: 767.98px)').matches;
            var lastShown = parseInt(read(SHEET) || '0', 10) || 0;
            if (!mobile || read(DONE) || standalone || Date.now() - lastShown < MONTH) return;

            var ua = navigator.userAgent;
            var isIOS = /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            var isAndroid = /Android/.test(ua);

            var nativeWrap = document.getElementById('spcInstallNative');
            var steps = document.getElementById('spcInstallSteps');
            var stepsList = document.getElementById('spcInstallStepsList');
            var gotIt = document.getElementById('spcInstallGotIt');
            var btn = document.getElementById('spcInstallBtn');

            var STEPS = {
                ios: [
                    ['fa-arrow-up-from-bracket', 'Tap the <strong>Share</strong> button in the Safari toolbar'],
                    ['fa-plus-square', 'Choose <strong>Add to Home Screen</strong> from the list'],
                    ['fa-check', 'Tap <strong>Add</strong> — the SPC icon appears on your home screen']
                ],
                android: [
                    ['fa-ellipsis-vertical', 'Tap the <strong>⋮ menu</strong> in the corner of Chrome'],
                    ['fa-download', 'Choose <strong>Install app</strong> or <strong>Add to Home screen</strong>'],
                    ['fa-check', 'Tap <strong>Install</strong> — done!']
                ],
                generic: [
                    ['fa-ellipsis-vertical', 'Open your browser\'s <strong>menu</strong>'],
                    ['fa-plus', 'Choose <strong>Add to Home screen</strong>'],
                    ['fa-check', 'Confirm — the SPC icon appears on your home screen']
                ]
            };

            function open(variant) {
                store(SHEET, String(Date.now())); // at most once a month
                if (variant === 'native') {
                    nativeWrap.hidden = false;
                    steps.hidden = true;
                    gotIt.hidden = true;
                } else {
                    nativeWrap.hidden = true;
                    var rows = isIOS ? STEPS.ios : (isAndroid ? STEPS.android : STEPS.generic);
                    stepsList.innerHTML = rows.map(function (r) {
                        return '<li><i class="fa-solid ' + r[0] + '"></i>' + r[1] + '</li>';
                    }).join('');
                    steps.hidden = false;
                    gotIt.hidden = false;
                }
                sheet.classList.add('is-open');
                sheet.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function close() {
                sheet.classList.remove('is-open');
                sheet.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            Array.prototype.forEach.call(sheet.querySelectorAll('[data-install-dismiss]'), function (el) {
                el.addEventListener('click', close);
            });
            gotIt.addEventListener('click', close);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sheet.classList.contains('is-open')) close();
            });

            // Native path: hand the captured prompt to Chrome and honour the choice.
            btn.addEventListener('click', function () {
                var promptEvent = window.__spcDeferredPrompt;
                close();
                if (!promptEvent) return;
                window.__spcDeferredPrompt = null;
                promptEvent.prompt();
                promptEvent.userChoice.then(function (choice) {
                    if (choice && choice.outcome === 'accepted') store(DONE, '1');
                });
            });

            var shown = false;
            function show(variant) {
                if (shown) return;
                shown = true;
                open(variant);
            }

            // Give the native prompt a moment to arrive, then fall back to
            // the how-to steps (iPhone Safari never fires the event).
            if (window.__spcDeferredPrompt) {
                show('native');
            } else {
                window.addEventListener('spc:install-ready', function () { show('native'); }, { once: true });
                setTimeout(function () { show(window.__spcDeferredPrompt ? 'native' : 'steps'); }, 3500);
            }
        })();
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('{{ asset('service-worker.js') }}?v=1');
            });
        }
    </script>
</body>

</html>

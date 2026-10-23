<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/images/logos/fav.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
    :root{
      --brand:#17694a; --brand-ink:#08301e; --brand-bright:#1b6b49;
      --brand-soft:#e7efe9; --brand-softer:#f4f7f4;
      --text:#101a14; --text-muted:#6e7a71;
      --font-head:'Plus Jakarta Sans',sans-serif; --font-body:'Plus Jakarta Sans',sans-serif;
    }
    *{box-sizing:border-box;}
    body{
      margin:0;font-family:var(--font-body);color:var(--text);min-height:100vh;
      background:
        radial-gradient(900px 500px at 105% -12%, rgba(14,61,38,0.14), transparent 60%),
        radial-gradient(700px 460px at -12% 110%, rgba(10,84,58,0.10), transparent 58%),
        linear-gradient(160deg,#F4FAF6 0%,#EAF4EE 55%,#F6FBF8 100%);
      -webkit-font-smoothing:antialiased;
      display:flex;flex-direction:column;
    }
    .deco{position:fixed;border-radius:34%;pointer-events:none;z-index:0;}
    .deco-1{width:180px;height:180px;top:-56px;right:14%;background:linear-gradient(135deg,rgba(47,138,99,.2),rgba(47,138,99,.05));transform:rotate(18deg);}
    .deco-2{width:110px;height:110px;bottom:12%;left:-38px;background:linear-gradient(135deg,rgba(14,61,38,.16),transparent);transform:rotate(-12deg);}
    .deco-3{width:64px;height:64px;top:22%;left:38%;border:1.5px solid rgba(14,61,38,.22);border-radius:50%;}
    .deco-4{width:16px;height:16px;top:64%;right:30%;background:#2f8a63;border-radius:50%;opacity:.5;}

    .top-brand{display:flex;align-items:center;gap:12px;padding:26px 42px 0;position:relative;z-index:2;}
    .top-brand img{height:25px;width:auto;max-width:150px;object-fit:contain;filter:brightness(0) invert(1) drop-shadow(0 4px 10px rgba(8,46,29,.35));}
    .top-brand .logo-tile{
      height:46px;padding:0 15px;border-radius:14px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
      background:linear-gradient(135deg,#14543a,#082e1d);box-shadow:0 12px 24px -10px rgba(8,46,29,.55);
    }
    /* Wordmark tile variant for when no image logo is available */
    .top-brand .logo-word{font-family:var(--font-head);font-size:15px;font-weight:700;color:#fff;letter-spacing:.04em;}
    .top-brand .logo-word i{color:#86c4a4;margin-right:6px;}
    .top-brand b{display:block;font-family:var(--font-head);font-size:16px;font-weight:600;color:var(--brand-ink);line-height:1.2;}
    .top-brand span{font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--brand-bright);}

    .login-wrap{flex:1;display:flex;align-items:center;justify-content:center;gap:64px;padding:34px 42px 56px;position:relative;z-index:1;flex-wrap:wrap;}

    .pitch{max-width:520px;}
    .pitch-pill{
      display:inline-flex;align-items:center;gap:8px;font-size:10.5px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;
      color:var(--brand);background:#fff;border:1px solid rgba(14,61,38,.3);border-radius:99px;padding:8px 15px;
      box-shadow:0 8px 20px -12px rgba(14,61,38,.4);margin-bottom:22px;
    }
    .pitch-pill i{font-size:11px;}
    .pitch h1{font-family:var(--font-head);font-weight:600;font-size:38px;line-height:1.16;color:var(--brand-ink);margin:0 0 16px;letter-spacing:.005em;}
    .pitch h1 .accent{color:var(--brand-bright);}
    .pitch p{font-size:14.5px;color:var(--text-muted);line-height:1.7;margin:0 0 26px;max-width:46ch;}
    .feat-row{display:flex;gap:12px;flex-wrap:wrap;}
    .feat{
      background:#fff;border:1px solid rgba(14,61,38,.1);border-radius:14px;padding:13px 15px;min-width:128px;
      box-shadow:0 10px 24px -16px rgba(8,46,29,.35);
    }
    .feat i{
      font-size:13px;color:var(--brand);width:30px;height:30px;border-radius:9px;background:var(--brand-soft);
      display:flex;align-items:center;justify-content:center;margin-bottom:9px;
    }
    .feat b{display:block;font-family:var(--font-head);font-size:12.5px;font-weight:500;color:var(--brand-ink);line-height:1.35;}

    .card{
      width:400px;max-width:100%;background:#fff;border-radius:22px;padding:36px 34px 30px;
      box-shadow:0 34px 70px -30px rgba(8,46,29,.45), 0 6px 18px -10px rgba(8,46,29,.2);
      border:1px solid rgba(255,255,255,.8);position:relative;
    }
    .card-logo{
      width:128px;height:60px;border-radius:18px;margin:0 auto 14px;display:flex;align-items:center;justify-content:center;
      background:linear-gradient(135deg,#14543a,#082e1d);box-shadow:0 16px 30px -12px rgba(8,46,29,.6), inset 0 1.5px 0 rgba(255,255,255,.18);
    }
    .card-logo img{height:30px;width:auto;max-width:98px;object-fit:contain;filter:brightness(0) invert(1);}
    .card-logo .logo-word{font-family:var(--font-head);font-size:17px;font-weight:700;color:#fff;letter-spacing:.04em;}
    .card-logo .logo-word i{color:#86c4a4;margin-right:7px;}
    .card h2{font-family:var(--font-head);font-weight:600;font-size:22px;color:var(--brand-ink);text-align:center;margin:0 0 4px;}
    .card .card-sub{text-align:center;font-size:12.5px;color:var(--text-muted);margin:0 0 24px;}

    .f-label{font-size:12.5px;font-weight:600;color:#3D4F46;margin:0 0 6px;display:block;}
    .input-wrap{position:relative;margin-bottom:15px;}
    .input-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#8FA79B;font-size:13px;}
    .input-wrap input{
      width:100%;font-family:var(--font-body);font-size:13.5px;color:var(--text);
      padding:11.5px 14px 11.5px 40px;border:1px solid rgba(14,61,38,.16);border-radius:12px;background:#fbfdfb;outline:none;
      transition:border-color .15s,background .15s,box-shadow .15s;
    }
    .input-wrap input:focus{border-color:var(--brand-bright);background:#fff;box-shadow:0 0 0 3.5px rgba(14,61,38,.14);}
    .input-wrap input::placeholder{color:#A5B8AD;}

    .row-between{display:flex;align-items:center;justify-content:space-between;margin:2px 0 18px;}
    .remember{display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--text-muted);cursor:pointer;}
    .remember input{width:15px;height:15px;accent-color:var(--brand);cursor:pointer;}
    .forgot{font-size:12.5px;font-weight:600;color:var(--brand);}

    .btn-primary{
      width:100%;display:inline-flex;align-items:center;justify-content:center;gap:10px;
      background:linear-gradient(135deg,#1b6b49,#0e3d26);color:#fff;border:none;border-radius:13px;
      padding:13px 18px;font-family:var(--font-body);font-size:14px;font-weight:600;cursor:pointer;letter-spacing:.01em;
      box-shadow:0 16px 30px -12px rgba(14,61,38,.6), inset 0 1.5px 0 rgba(255,255,255,.25);
      transition:filter .15s,transform .1s;
    }
    .btn-primary:hover{filter:brightness(1.07);}
    .btn-primary:active{transform:translateY(1px);}
    .demo-hint{
      display:flex;align-items:center;justify-content:center;gap:7px;font-size:11.5px;color:var(--brand-ink);
      background:var(--brand-softer);border:1px dashed rgba(14,61,38,.45);border-radius:10px;padding:8px 10px;margin:14px 0 12px;
    }
    .demo-hint i{color:var(--brand-bright);}
    .demo-hint code{font-family:ui-monospace,monospace;font-weight:600;background:#fff;padding:1px 6px;border-radius:6px;border:1px solid rgba(14,61,38,.3);}
    .card-foot{margin-top:18px;text-align:center;font-size:11px;color:#9AB0A5;}
    .card-foot a{color:var(--brand);font-weight:600;text-decoration:none;}

    .flash{max-width:400px;width:100%;margin:0 auto;background:#dff0e6;border:1px solid rgba(21,128,61,.28);color:#14663A;padding:12px 16px;border-radius:12px;font-size:13px;display:flex;align-items:center;gap:10px;}
    .flash::before{content:"\f058";font-family:"Font Awesome 6 Free";font-weight:900;}
    .flash-errors{max-width:400px;width:100%;margin:0 auto;background:#FBE7E4;border:1px solid rgba(192,52,52,.3);color:#8F2323;padding:12px 16px;border-radius:12px;font-size:13px;}
    .flash-errors::before{content:"\f06a";font-family:"Font Awesome 6 Free";font-weight:900;margin-right:8px;}

    .page-foot{padding:0 42px 26px;font-size:11.5px;color:#9AB0A5;display:flex;align-items:center;gap:10px;position:relative;z-index:1;}
    .page-foot .foot-dot{width:7px;height:7px;border-radius:50%;background:var(--brand-bright);opacity:.7;}

    @media (max-width:900px){
      /* Mobile: drop the marketing pitch, show brand + card only */
      .pitch{display:none;}
      .login-wrap{gap:0;padding:18px 18px 30px;flex:1;}
      .top-brand{padding:22px 20px 0;justify-content:center;}
      .page-foot{justify-content:center;padding:0 20px 18px;}
    }
    @media (max-width:520px){
      .top-brand{padding-top:18px;}
      .top-brand b{font-size:14.5px;}
      .top-brand span{font-size:10px;}
      .top-brand .logo-tile{height:40px;padding:0 12px;}
      .top-brand img{height:21px;max-width:120px;}
      .card{padding:26px 20px 22px;width:100%;max-width:400px;}
      .card-logo{width:112px;height:54px;}
      .card-logo img{height:26px;max-width:86px;}
      .login-wrap{padding:14px 14px 24px;}
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
    <div class="deco deco-1"></div>
    <div class="deco deco-2"></div>
    <div class="deco deco-3"></div>
    <div class="deco deco-4"></div>

    <div class="top-brand">
        <div class="logo-tile"><span class="logo-word"><i class="fa-solid fa-layer-group"></i>SPC</span></div>
        <div>
            <b>SPC Universal</b>
            <span>HR Management Suite</span>
        </div>
    </div>

    <div class="login-wrap">
        <div class="pitch">
            <span class="pitch-pill"><i class="fa-solid fa-shield-halved"></i>HR Management Suite</span>
            <h1>One secure gateway to<br><span class="accent">your entire workforce.</span></h1>
            <p>Sign in to manage attendance, leave, payroll, recruitment and performance through one unified platform built for modern teams.</p>
            <div class="feat-row">
                <div class="feat"><i class="fa-solid fa-user-shield"></i><b>Role based access</b></div>
                <div class="feat"><i class="fa-solid fa-chart-column"></i><b>Live insights</b></div>
                <div class="feat"><i class="fa-solid fa-layer-group"></i><b>10+ modules</b></div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:14px;align-items:center;">
            @if(session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="flash-errors">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-logo"><span class="logo-word"><i class="fa-solid fa-layer-group"></i>SPC</span></div>
                <h2>SPC Portal</h2>
                <p class="card-sub">Secure HR Access &middot; Management System</p>
                <form method="POST" action="{{ route('hr.login.submit') }}">
                    @csrf
                    <label class="f-label" for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your work email" required autofocus>
                    </div>
                    <label class="f-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input id="password" type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="row-between">
                        <label class="remember"><input type="checkbox" name="remember"> Remember me</label>
                        <a class="forgot" href="#">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn-primary">Sign in securely <i class="fa-solid fa-arrow-right"></i></button>
                </form>
                <div class="demo-hint"><i class="fa-solid fa-circle-info"></i>Demo access &mdash; any seed email + <code>Password@123</code></div>
                <div class="card-foot">By signing in, you agree to the Portal's <a href="#">Terms</a> &amp; <a href="#">Privacy Policy</a></div>
            </div>
        </div>
    </div>

    <div class="page-foot"><span class="foot-dot"></span>&copy; {{ date('Y') }} SPC Universal &middot; Developed by Gipra Business Solutions Pvt Ltd</div>

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

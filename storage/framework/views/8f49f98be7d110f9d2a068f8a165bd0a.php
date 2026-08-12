<!DOCTYPE html>
<html lang="en">

<head>
    <title>SPC</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="SPC" />
    <meta name="keywords" content="spc" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('dist/images/logos/fav.png')); ?>" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #004b91;
            --primary-red: #ed1c24;
            --accent-glow: rgba(0, 75, 145, 0.15);
            --bg-color: #fcfdfe;
            --card-bg: #ffffff;
            --text-dark: #1a1a1a;
            --text-grey: #666666;
            --input-border: #e0e0e0;
            --shadow-soft: 0 20px 40px rgba(0, 0, 0, 0.05);
            --shadow-glow: 0 0 20px rgba(0, 75, 145, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
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
            background: linear-gradient(135deg, #5A8D3A, #074E30);
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(0, 75, 145, 0.2);
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
            box-shadow: 0 15px 30px rgba(0, 75, 145, 0.3);
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
            margin-top: 20px
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
</head>

<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="logo-box">
                <img src="<?php echo e(asset('dist/images/logos/spclogo.png')); ?>" alt="SPC Logo">
            </div>

            <div class="auth-header animated">
                <h1>Welcome Back</h1>
                <p>Please enter your details to sign in</p>
            </div>

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>




                <div class="form-group animated delay-1">
                    <label for="exampleInputEmail1">Email / Username</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="exampleInputEmail1" placeholder="e.g. name@domain.com"
                            value="<?php echo e(old('email')); ?>" required>
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
                    <a href="<?php echo e(route('password.request')); ?>" class="forgot-pass">Forgot Password?</a>
                </div>



                <button type="submit" class="submit-btn animated delay-2">
                    Sign In
                </button>

                
            </form>
        </div>
    </div>
    <p class="copy">Copyright © 2026 SPC All Rights Reserved.

    </p>


    <!--  Import Js Files -->
    <script src="<?php echo e(asset('dist/libs/jquery/dist/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/libs/simplebar/dist/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <!--  core files -->
    <script src="<?php echo e(asset('dist/js/app.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/js/app.init.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/js/app-style-switcher.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/js/sidebarmenu.js')); ?>"></script>

    <script src="<?php echo e(asset('dist/js/custom.js')); ?>"></script>
    <script>
        $('#togglePassword').click(function(){
            let input = $('#exampleInputPassword1');
            input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\SPC\resources\views/auth/login.blade.php ENDPATH**/ ?>
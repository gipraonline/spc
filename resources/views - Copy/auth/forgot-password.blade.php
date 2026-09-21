<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Centreal Bazaar</title>
        <link rel="shortcut icon" type="image/png" href="{{asset('dist/images/logos/fav.png')}}" />

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

:root {
    --primary-navy: #043e7d;
    --primary-red: #e21e25;
    --input-bg: #edf2f9;
    --text-dark: #1a1a1a;
    --text-light: #6b7280;
    --white: #ffffff;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Outfit', sans-serif;
}

body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8fbff;
    position: relative;
    overflow: hidden;
}

/* Background Gradients (The "Blobs") */
.bg-blobs {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.15;
    animation: move 20s infinite alternate;
}

.blob-1 {
    width: 600px;
    height: 600px;
    background: #4facfe;
    top: -100px;
    left: -100px;
}

.blob-2 {
    width: 500px;
    height: 500px;
    background: var(--primary-red);
    bottom: -100px;
    right: -100px;
    animation-delay: -5s;
}

@keyframes move {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(50px, 100px) scale(1.1); }
}

/* Card Container */
.auth-container {
    width: 100%;
    max-width: 440px;
    padding: 20px;
    z-index: 1;
}

.auth-card {
    background: var(--white);
    border-radius: 24px;
    padding: 50px 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
    text-align: center;
}

/* Top Accent Line */
.auth-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(to right, var(--primary-navy) 0%, var(--primary-navy) 50%, var(--primary-red) 50%, var(--primary-red) 100%);
}

/* Logo Styling */
.logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 30px;
}

.logo-icon {
    width: 120px;
    margin-bottom: 5px;
}

.logo-text {
    font-size: 24px;
    font-weight: 800;
}

.text-navy { color: var(--primary-navy); }
.text-red { color: var(--primary-red); }

.tagline {
    font-size: 8px;
    color: var(--primary-navy);
    letter-spacing: 0.5px;
    margin-top: -5px;
}

/* Typography */
h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 10px;
}

p.subtitle {
    font-size: 14px;
    color: var(--text-light);
    margin-bottom: 40px;
}

/* Form Styling */
.form-group {
    text-align: left;
    margin-bottom: 25px;
}

label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.input-wrapper {
    position: relative;
}

input[type="email"],
input[type="text"] {
    width: 100%;
    padding: 16px 20px;
    background: var(--input-bg);
    border: 2px solid transparent;
    border-radius: 14px;
    font-size: 15px;
    color: var(--text-dark);
    transition: var(--transition);
}

input:focus {
    outline: none;
    background: var(--white);
    border-color: var(--primary-navy);
    box-shadow: 0 0 0 4px rgba(4, 62, 125, 0.05);
}

/* Button Styling */
.btn-primary {
    width: 100%;
    padding: 18px;
    background: var(--primary-navy);
    color: var(--white);
    border: none;
    border-radius: 14px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 10px;
    box-shadow: 0 10px 30px rgba(4, 62, 125, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 40px rgba(4, 62, 125, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
}

/* Footer Links */
.auth-footer {
    margin-top: 30px;
    font-size: 14px;
    color: var(--text-light);
}

.link-red {
    color: var(--primary-red);
    text-decoration: none;
    font-weight: 700;
    transition: var(--transition);
}

.link-red:hover {
    text-decoration: underline;
}

.back-to-login {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    color: var(--primary-navy);
    text-decoration: none;
    font-weight: 600;
}

.back-to-login:hover {
    text-decoration: underline;
}

/* Copyright */
.copyright {
    position: absolute;
    bottom: 20px;
    width: 100%;
    text-align: center;
    font-size: 12px;
    color: var(--text-dark);
    font-weight: 600;
}
</style>



</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            
            <div class="logo">
              <a href="/">
                 <img src="https://centreal-admin.gipra.in/dist/images/logos/centrallogo.png" alt="">
              </a>
            </div>

            <h1>Forgot Password</h1>
            <p class="subtitle">Enter your email address to receive a <br> password reset link.</p>

            <form action="#">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" placeholder="Enter your registered email" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Send Reset Link</button>
            </form>

            <div class="auth-footer">
                <p>Remembered your password? <a href="/" class="link-red">Sign In</a></p>
                <a href="/" class="back-to-login">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <div class="copyright">
        Copyright © 2026 Centreal Bazaar All Rights Reserved.
    </div>

</body>
</html>

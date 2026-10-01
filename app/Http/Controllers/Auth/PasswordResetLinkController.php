<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordResetOtpService as Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Steps 1 & 2 of "Forgot password":
 *   1. person enters their login email / username  -> we email a 6-digit code
 *   2. person types the code                        -> unlocks the reset form
 */
class PasswordResetLinkController extends Controller
{
    private const SESSION_KEY = 'password_reset';
    private const MAX_REQUESTS_PER_HOUR = 5;

    public function __construct(private Otp $otp)
    {
    }

    /** Step 1 — ask for the email / username. */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /** Step 1 — look the account up and send the code. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'max:255'],
        ], [
            'email.required' => 'Please enter your registered email address.',
        ]);

        $identifier = Str::lower(trim($data['email']));

        $limiterKey = 'pw-otp-request:'.sha1($identifier.'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($limiterKey, self::MAX_REQUESTS_PER_HOUR)) {
            $minutes = (int) ceil(RateLimiter::availableIn($limiterKey) / 60);

            return back()->withInput()->withErrors([
                'email' => "Too many requests. Please try again in {$minutes} minute(s).",
            ]);
        }

        RateLimiter::hit($limiterKey, 3600);

        $reveal = (bool) config('auth.password_reset_show_errors', true);

        $admin = $this->otp->findAdmin($identifier, false);
        $email = null;
        $status = 'If that account exists, a 6-digit verification code has been sent to its email address.';

        if (! $admin) {
            Log::warning('Password reset: no account found.', ['identifier' => $identifier]);

            if ($reveal) {
                return back()->withInput()->withErrors([
                    'email' => 'No account was found with this email / username. Please check it and try again.',
                ]);
            }
        } elseif ($admin->c_status !== 'Active') {
            Log::warning('Password reset: account is not active.', ['admin_id' => $admin->getKey()]);

            if ($reveal) {
                return back()->withInput()->withErrors([
                    'email' => 'This account is inactive. Please contact your administrator.',
                ]);
            }

            $admin = null;
        } elseif (! ($email = $this->otp->resolveEmail($admin))) {
            Log::warning('Password reset: account has no valid email address.', ['admin_id' => $admin->getKey()]);

            if ($reveal) {
                return back()->withInput()->withErrors([
                    'email' => 'No valid email address is linked to this account. Please contact your administrator.',
                ]);
            }

            $admin = null;
        } else {
            $wait = $this->otp->secondsUntilResend((int) $admin->getKey());

            if ($wait > 0) {
                // A code was emailed moments ago: don't send another, just continue.
                $status = "A code was already sent a moment ago. Please check your inbox (and spam folder). You can request another in {$wait} second(s).";
            } else {
                try {
                    $this->otp->send($admin, $email, $request->ip());
                    $status = 'A 6-digit verification code has been sent to '.Otp::maskEmail($email).'.';
                } catch (\Throwable $e) {
                    report($e);
                    Log::error('Password reset: email could not be sent.', ['admin_id' => $admin->getKey(), 'error' => $e->getMessage()]);

                    return back()->withInput()->withErrors([
                        'email' => 'We could not send the email. The mail server settings may be incorrect — please contact your administrator.',
                    ]);
                }
            }
        }

        if (! $reveal) {
            $status = 'If that account exists, a 6-digit verification code has been sent to its email address.';
        }

        // With reveal off, the response is identical whether or not the account exists.
        $request->session()->put(self::SESSION_KEY, [
            'admin_id' => ($admin && $email) ? (int) $admin->getKey() : null,
            'masked' => $email ? Otp::maskEmail($email) : (str_contains($identifier, '@') ? Otp::maskEmail($identifier) : null),
        ]);

        return redirect()->route('password.otp')->with('status', $status);
    }

    /** Step 2 — the code entry screen. */
    public function verifyForm(Request $request): View|RedirectResponse
    {
        $flow = $request->session()->get(self::SESSION_KEY);

        if (! $flow) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', [
            'masked' => $flow['masked'] ?? null,
            'cooldown' => $flow['admin_id'] ? $this->otp->secondsUntilResend($flow['admin_id']) : Otp::RESEND_COOLDOWN_SECONDS,
            'minutes' => Otp::OTP_TTL_MINUTES,
            'length' => Otp::OTP_LENGTH,
        ]);
    }

    /** Step 2 — check the code. */
    public function verify(Request $request): RedirectResponse
    {
        $flow = $request->session()->get(self::SESSION_KEY);

        if (! $flow) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'code' => ['required', 'digits:'.Otp::OTP_LENGTH],
        ], [
            'code.required' => 'Please enter the 6-digit code.',
            'code.digits' => 'The code must be exactly 6 digits.',
        ]);

        $adminId = $flow['admin_id'] ?? null;

        // Unknown account: behave exactly like a wrong code.
        if (! $adminId) {
            return back()->withErrors(['code' => 'That code is incorrect or has expired.']);
        }

        $result = $this->otp->verify($adminId, (string) $request->input('code'));

        switch ($result['status']) {
            case 'ok':
                $request->session()->regenerate();
                $request->session()->put(self::SESSION_KEY.'.verified', true);

                return redirect()->route('password.reset');

            case 'locked':
                $request->session()->forget(self::SESSION_KEY);

                return redirect()->route('password.request')->withErrors([
                    'email' => 'Too many incorrect attempts. Please request a new code.',
                ]);

            case 'expired':
                return back()->withErrors([
                    'code' => 'This code has expired. Please request a new one.',
                ]);

            default:
                return back()->withErrors([
                    'code' => "Incorrect code. You have {$result['left']} attempt(s) left.",
                ]);
        }
    }

    /** Step 2 — send a new code. */
    public function resend(Request $request): RedirectResponse
    {
        $flow = $request->session()->get(self::SESSION_KEY);

        if (! $flow) {
            return redirect()->route('password.request');
        }

        $adminId = $flow['admin_id'] ?? null;
        $admin = $adminId ? \App\Models\Admin::find($adminId) : null;
        $email = $admin ? $this->otp->resolveEmail($admin) : null;

        if (! $admin || ! $email) {
            return back()->with('status', 'If that account exists, a new verification code has been sent.');
        }

        $limiterKey = 'pw-otp-request:'.sha1($adminId.'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($limiterKey, self::MAX_REQUESTS_PER_HOUR)) {
            $minutes = (int) ceil(RateLimiter::availableIn($limiterKey) / 60);

            return back()->withErrors(['code' => "Too many requests. Please try again in {$minutes} minute(s)."]);
        }

        $wait = $this->otp->secondsUntilResend($adminId);

        if ($wait > 0) {
            return back()->withErrors(['code' => "Please wait {$wait} second(s) before requesting another code."]);
        }

        RateLimiter::hit($limiterKey, 3600);

        try {
            $this->otp->send($admin, $email, $request->ip());
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['code' => 'We could not send the email right now. Please try again shortly.']);
        }

        return back()->with('status', 'A new verification code has been sent.');
    }
}

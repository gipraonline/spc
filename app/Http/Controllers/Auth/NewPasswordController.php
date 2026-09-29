<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\PasswordResetOtpService as Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Step 3 of "Forgot password": choose a new password.
 * Only reachable after the emailed code has been verified.
 */
class NewPasswordController extends Controller
{
    private const SESSION_KEY = 'password_reset';

    public function __construct(private Otp $otp)
    {
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $this->verifiedAdmin($request)) {
            return $this->startOver();
        }

        return view('auth.reset-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $admin = $this->verifiedAdmin($request);

        if (! $admin) {
            return $this->startOver();
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.required' => 'Please enter a new password.',
            'password.confirmed' => 'The two passwords do not match.',
        ]);

        if (Hash::check($request->input('password'), (string) $admin->c_password)) {
            return back()->withErrors(['password' => 'Your new password must be different from your current one.']);
        }

        $this->otp->complete($admin, $request->input('password'));

        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('login')
            ->with('status', 'Your password has been reset. Please sign in with your new password.');
    }

    /** The admin who has just proved they own the email, or null. */
    private function verifiedAdmin(Request $request): ?Admin
    {
        $flow = $request->session()->get(self::SESSION_KEY);

        if (empty($flow['verified']) || empty($flow['admin_id'])) {
            return null;
        }

        if (! $this->otp->isVerified((int) $flow['admin_id'])) {
            return null;
        }

        return Admin::find($flow['admin_id']);
    }

    private function startOver(): RedirectResponse
    {
        return redirect()->route('password.request')->withErrors([
            'email' => 'Your verification has expired. Please start again.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Hr;


use App\Models\Hr\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function show(Request $request)
    {
        if (session('user_id') && User::find(session('user_id'))) {
            return redirect()->route('hr.dashboard');
        }

        // Single sign-on: already logged into the SPC portal with a
        // matching HR account? Skip this form entirely.
        if ($user = User::findForSpcAdmin(Auth::user())) {
            session(['user_id' => $user->id]);
            $user->update(['last_login_at' => now()]);

            return redirect()->route('hr.dashboard');
        }

        return view('hr.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Those credentials don\'t match any active account.',
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'This account has been suspended. Contact your Super Admin.',
            ]);
        }

        $request->session()->regenerate();
        session(['user_id' => $user->id]);
        $user->update(['last_login_at' => now()]);

        return redirect()->route('hr.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->regenerate();

        return redirect()->route('hr.login.show');
    }
}

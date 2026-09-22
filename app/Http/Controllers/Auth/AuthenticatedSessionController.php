<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Hr\User as HrUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the login view.
     */
    public function create(): View
    {

        return view('auth/login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

            $request->authenticate();

            $request->session()->regenerate();

            // Single sign-on: if this SPC account has a matching HR account
            // (same email), sign it into the HR module too, so the person
            // never has to log in a second time to reach /hr.
            $this->syncHrSession($request);

            return redirect()->intended(route('dashboard', absolute: false));

    }

    /**
     * Establish the HR module's session for the just-authenticated admin,
     * if — and only if — an active HR account shares their email. Admins
     * with no HR account are left exactly as before; nothing is created.
     */
    protected function syncHrSession(Request $request): void
    {
        $hrUser = HrUser::findForSpcAdmin(Auth::user());

        if ($hrUser) {
            session(['user_id' => $hrUser->id]);
            $hrUser->update(['last_login_at' => now()]);
        } else {
            $request->session()->forget('user_id');
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // dd('Logout method called');
        Auth::logout();

        // Logging out of SPC also logs out of the linked HR session, since
        // the two are now a single sign-on.
        $request->session()->forget('user_id');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
   
}
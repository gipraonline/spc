<?php

namespace App\Http\Middleware\Hr;

use App\Models\Hr\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserSelected
{
    /**
     * Every request past this point needs a valid session('user_id') to build
     * the sidebar, KPIs and role gating from. If none is set (or it points at
     * a user that no longer exists), first try the single sign-on bridge: a
     * visitor who is already logged into the SPC portal with a matching HR
     * account (same email) is signed straight in, no second login required.
     * There is no separate HR sign-in page: if the visitor is not logged into
     * SPC they go to the SPC login, and if they are logged in but have no
     * (or a suspended) HR account they are sent back to the dashboard.
     */
    public function handle(Request $request, Closure $next)
    {
        $id = session('user_id');
        $user = $id ? User::find($id) : null;

        if (! $user) {
            $user = User::findForSpcAdmin(Auth::user());

            if ($user) {
                session(['user_id' => $user->id]);
                $user->update(['last_login_at' => now()]);
            }
        }

        if (! $user) {
            return Auth::check()
                ? redirect()->route('dashboard')->with('error', 'No HR account is linked to your login.')
                : redirect()->route('login');
        }

        if (! $user->is_active) {
            $request->session()->forget('user_id');

            return Auth::check()
                ? redirect()->route('dashboard')->with('error', 'This HR account has been suspended. Contact your Super Admin.')
                : redirect()->route('login')->withErrors(['email' => 'This account has been suspended. Contact your Super Admin.']);
        }

        return $next($request);
    }
}

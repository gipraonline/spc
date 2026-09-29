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
     * Only if neither is available do we send them to the HR sign-in page.
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
            return redirect()->route('hr.login.show');
        }

        if (! $user->is_active) {
            $request->session()->forget('user_id');
            $request->session()->regenerate();

            return redirect()->route('hr.login.show')
                ->withErrors(['email' => 'This account has been suspended. Contact your Super Admin.']);
        }

        return $next($request);
    }
}

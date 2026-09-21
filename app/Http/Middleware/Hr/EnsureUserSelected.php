<?php

namespace App\Http\Middleware\Hr;

use App\Models\Hr\User;
use Closure;
use Illuminate\Http\Request;

class EnsureUserSelected
{
    /**
     * This is a demo app with no password auth: EnsureUserSelected makes sure
     * every request past this point has a valid session('user_id') to build
     * the sidebar, KPIs and role gating from. If none is set (or it points at
     * a user that no longer exists), send the visitor to the sign-in chooser.
     */
    public function handle(Request $request, Closure $next)
    {
        $id = session('user_id');
        $user = $id ? User::find($id) : null;

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

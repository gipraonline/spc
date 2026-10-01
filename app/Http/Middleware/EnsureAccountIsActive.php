<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ends the session of any signed-in admin whose account is no longer Active
 * (e.g. HR marked the employee as exited/terminated). Login already refuses
 * inactive accounts; this covers sessions and remember-me cookies that were
 * opened before the account was deactivated.
 */
class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user instanceof Admin && ! $user->isActive()) {
            Auth::logout();

            // Also drops the linked HR session (single sign-on).
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'This account is inactive.'], 403);
            }

            return redirect()->route('login')->withErrors([
                'email' => 'This account is inactive. Please contact your administrator.',
            ]);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware\Hr;

use App\Models\Hr\User;
use Closure;
use Illuminate\Http\Request;

/**
 * Route-level guard for HR-admin-only screens (e.g. Document Verification).
 * Runs after `hr.auth`, so session('user_id') is already resolved. Only the
 * HR Admin and Super Admin portal roles get through; everyone else gets 403.
 */
class EnsureHrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user_id') ? User::find(session('user_id')) : null;

        abort_unless(
            $user && in_array($user->role, ['hr_admin', 'super_admin'], true),
            403,
            'Document verification is restricted to HR Admin and Super Admin.'
        );

        return $next($request);
    }
}

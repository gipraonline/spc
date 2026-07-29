<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // TODO: Implement proper admin role check
        // For now, all authenticated users can access admin area
        // In production, implement role-based access control

        if (! auth()->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

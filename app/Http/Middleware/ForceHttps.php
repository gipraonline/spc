<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PWA needs a secure origin: force HTTPS on every page so the service
 * worker can register and Chrome can offer "Add to Home screen".
 *
 * Runs first in the web group (before cookies/sessions) and issues a 301.
 * Local/LAN dev hosts are exempt so `php artisan serve` and phone testing
 * against the dev machine keep working over plain http.
 */
class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldBeSecure($request)) {
            $url = 'https://'.$request->getHost().$request->getRequestUri();

            return redirect()->to($url, 301);
        }

        return $next($request);
    }

    private function shouldBeSecure(Request $request): bool
    {
        // Already https (server port or TLS-terminating proxy header).
        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https') {
            return false;
        }

        return ! $this->isDevHost($request->getHost());
    }

    /**
     * localhost / loopback / private LAN ranges used for local development.
     */
    private function isDevHost(string $host): bool
    {
        if (in_array(strtolower($host), ['localhost', '127.0.0.1', '[::1]', '::1'], true)) {
            return true;
        }

        return (bool) preg_match(
            '/^(192\.168\.\d{1,3}\.\d{1,3}|10\.\d{1,3}\.\d{1,3}\.\d{1,3}|172\.(1[6-9]|2\d|3[01])\.\d{1,3}\.\d{1,3})$/',
            $host
        );
    }
}

<?php

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtCookieToAuthHeader
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->bearerToken()) {
            $cookieToken = $request->cookie('access_token');
            if (is_string($cookieToken) && $cookieToken !== '') {
                $request->headers->set('Authorization', 'Bearer '.$cookieToken);
            }
        }

        return $next($request);
    }
}

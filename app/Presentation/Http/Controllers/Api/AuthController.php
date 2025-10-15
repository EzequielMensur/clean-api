<?php

namespace App\Presentation\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = JWTAuth::attempt($data)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $minutes = (int) config('jwt.ttl', 15);
        $cookie = $this->makeJwtCookie($token, $minutes);

        return response()->json([
            'access_token' => 'ok',
            'expires_in' => $minutes * 60,
            'token_type' => 'Bearer',
        ], 200)->cookie($cookie);
    }

    public function refresh(Request $request)
    {
        // Tomamos de header o cookie
        $token = $request->bearerToken() ?: $request->cookie('access_token');
        if (! $token) {
            return response()->json(['message' => 'Token no provisto'], 401);
        }

        try {
            $new = JWTAuth::setToken($token)->refresh();
        } catch (TokenExpiredException) {
            return response()->json(['message' => 'Token expirado'], 401);
        } catch (Throwable) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $minutes = (int) config('jwt.ttl');
        $cookie = $this->makeJwtCookie($new, $minutes);

        return response()->json([
            'message' => 'refreshed',
            'expires_in' => $minutes * 60,
            'token_type' => 'Bearer',
        ], 200)->cookie($cookie);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken() ?: $request->cookie('access_token');

        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
            } catch (Throwable) {
                // ignoramos si ya estaba inválido
            }
        }

        $forget = cookie()->forget(
            'access_token',
            '/',
            $this->cookieDomain()
        );

        return response()->json(['message' => 'ok'])->cookie($forget);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return $user
            ? response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
            ])
            : response()->json(['message' => 'No autenticado'], 401);
    }

    private function makeJwtCookie(string $token, int $minutes): \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
    {
        return cookie(
            name: 'access_token',
            value: $token,
            minutes: $minutes,
            path: '/',
            domain: $this->cookieDomain(),
            secure: $this->cookieSecure(),
            httpOnly: true,
            raw: false,
            sameSite: $this->cookieSameSite()
        );
    }

    private function cookieDomain(): ?string
    {
        return config('session.domain');
    }

    private function cookieSecure(): bool
    {
        return (bool) config('session.secure', app()->environment('production'));
    }

    private function cookieSameSite(): string
    {
        return config('session.same_site', 'lax');
    }
}

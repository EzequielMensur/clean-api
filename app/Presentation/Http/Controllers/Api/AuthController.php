<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\Login\DTOs\LoginRequestDto;
use App\Application\Login\UseCases\LoginUser;
use App\Application\Login\UseCases\LogoutUser;
use App\Application\Login\UseCases\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request, LoginUser $login)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $dto = new LoginRequestDto($data['email'], $data['password']);
        $res = $login($dto);

        if (!$res) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $minutes = (int) ceil($res->expiresIn / 60);
        $cookie  = $this->makeJwtCookie($res->token, $minutes);

        return response()->json([
            'access_token' => 'ok',
            'expires_in'   => $res->expiresIn,
            'token_type'   => $res->tokenType,
        ])->cookie($cookie);
    }

    public function refresh(Request $request, RefreshToken $refresh)
    {
        $token = $request->bearerToken() ?: $request->cookie('access_token');
        if (!$token) {
            return response()->json(['message' => 'Token no provisto'], 401);
        }

        try {
            $res = $refresh($token);
        } catch (Throwable) {
            return response()->json(['message' => 'Token inválido o expirado'], 401);
        }

        $minutes = (int) ceil($res->expiresIn / 60);
        $cookie  = $this->makeJwtCookie($res->token, $minutes);

        return response()->json([
            'message'    => $res->message,
            'expires_in' => $res->expiresIn,
            'token_type' => $res->tokenType,
        ])->cookie($cookie);
    }

    public function logout(Request $request, LogoutUser $logout)
    {
        $token = $request->bearerToken() ?: $request->cookie('access_token');

        if ($token) {
            try { $logout($token); } catch (Throwable) { /* ya inválido, ignorar */ }
        }

        $forget = cookie()->forget('access_token', '/', $this->cookieDomain());

        return response()->json(['message' => 'ok'])->cookie($forget);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return $user
            ? response()->json([
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
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

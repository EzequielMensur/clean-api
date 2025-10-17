<?php
namespace App\Infrastructure\Auth;

use App\Application\Auth\Ports\TokenService;
use Tymon\JWTAuth\JWTAuth;

final class JwtTokenService implements TokenService
{
    public function __construct(private JWTAuth $jwt) {}

    public function attempt(string $email, string $password): ?string
    {
        return $this->jwt->attempt(['email' => $email, 'password' => $password]) ?: null;
    }

    public function refresh(string $token): string
    {
        return $this->jwt->setToken($token)->refresh();
    }

    public function invalidate(string $token): void
    {
        $this->jwt->setToken($token)->invalidate();
    }

    public function userId(string $token): ?int
    {
        try {
            return (int) $this->jwt->setToken($token)->getPayload()->get('sub');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function tokenType(): string   { return 'Bearer'; }
    public function ttlSeconds(): int     { return (int) config('jwt.ttl', 15) * 60; }
}

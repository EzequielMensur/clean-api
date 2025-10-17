<?php
namespace App\Application\Auth\Ports;

interface TokenService
{
    public function attempt(string $email, string $password): ?string;

    public function refresh(string $token): string;
    public function invalidate(string $token): void;

    public function userId(string $token): ?int;
    public function tokenType(): string;
    public function ttlSeconds(): int;
}

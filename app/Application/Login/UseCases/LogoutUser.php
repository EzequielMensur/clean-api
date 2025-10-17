<?php
namespace App\Application\Login\UseCases;

use App\Application\Auth\Ports\TokenService;

final class LogoutUser
{
    public function __construct(private TokenService $tokens) {}

    public function __invoke(string $token): void
    {
        $this->tokens->invalidate($token);
    }
}

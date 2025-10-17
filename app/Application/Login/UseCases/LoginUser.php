<?php
namespace App\Application\Login\UseCases;

use App\Application\Auth\Ports\TokenService;
use App\Application\Login\DTOs\LoginRequestDto;
use App\Application\Login\DTOs\LoginResponseDto;

final class LoginUser
{
    public function __construct(private TokenService $tokens) {}

    public function __invoke(LoginRequestDto $req): ?LoginResponseDto
    {
        $token = $this->tokens->attempt($req->email, $req->password);
        if (!$token) {
            return null;
        }

        return new LoginResponseDto(
            tokenType: $this->tokens->tokenType(),
            expiresIn: $this->tokens->ttlSeconds(),
            token: $token
        );
    }
}

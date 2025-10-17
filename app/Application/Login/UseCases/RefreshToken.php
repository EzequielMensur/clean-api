<?php
namespace App\Application\Login\UseCases;

use App\Application\Auth\Ports\TokenService;
use App\Application\Login\DTOs\RefreshResponseDto;

final class RefreshToken
{
    public function __construct(private TokenService $tokens) {}

    public function __invoke(string $token): RefreshResponseDto
    {
        $new = $this->tokens->refresh($token);

        return new RefreshResponseDto(
            message: 'refreshed',
            tokenType: $this->tokens->tokenType(),
            expiresIn: $this->tokens->ttlSeconds(),
            token: $new
        );
    }
}

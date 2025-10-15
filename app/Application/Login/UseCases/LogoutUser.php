<?php

namespace App\Application\Login\UseCases;

use App\Application\Login\DTOs\RefreshResponseDto;
use App\Domain\Services\TokenService;

final class LogoutUser
{
    public function __construct(
        private readonly TokenService $tokens
    ) {}

    public function __invoke(): RefreshResponseDto
    {
        $newToken = $this->tokens->refresh();

        return new RefreshResponseDto(
            token: $newToken,
            tokenType: 'Bearer',
            expiresIn: config('jwt.ttl') * 60
        );
    }
}

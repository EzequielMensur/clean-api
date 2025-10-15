<?php

namespace App\Application\Login\UseCases;

use App\Domain\Services\TokenService;

final class RefreshToken
{
    public function __construct(
        private readonly TokenService $tokens
    ) {}

    public function __invoke(): void
    {
        $this->tokens->invalidate();
    }
}

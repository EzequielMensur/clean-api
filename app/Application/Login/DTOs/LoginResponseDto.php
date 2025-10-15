<?php

namespace App\Application\Login\DTOs;

final readonly class LoginResponseDto
{
    public function __construct(
        public string $token,
        public string $tokenType,
        public int $expiresIn,
    ) {}

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}

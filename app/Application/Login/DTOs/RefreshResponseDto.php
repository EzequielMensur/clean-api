<?php

namespace App\Application\Login\DTOs;

final readonly class RefreshResponseDto
{
    public function __construct(
        public string $message,
        public string $token,
        public string $tokenType,
        public int $expiresIn,
    ) {}

    public function toArray(): array
    {
        return [
            "message"=> $this->message,
            'token' => $this->token,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}

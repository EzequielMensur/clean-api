<?php

namespace App\Application\Login\DTOs;

final readonly class LoginRequestDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}

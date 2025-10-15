<?php

namespace App\Application\User\DTOs;

final readonly class CreateUserInput
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $username,
        public string $password,
    ) {}
}

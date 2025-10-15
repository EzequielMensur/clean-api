<?php

namespace App\Application\User\DTOs;

final class UserInput
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $username
    ) {}
}

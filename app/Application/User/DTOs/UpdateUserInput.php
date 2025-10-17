<?php

namespace App\Application\User\DTOs;

final readonly class UpdateUserInput
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $username = null,
        public ?string $password = null,
    ) {}
}

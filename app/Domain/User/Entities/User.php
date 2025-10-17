<?php

namespace App\Domain\User\Entities;

final class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $username,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $deletedAt = null,
    ) {}
}

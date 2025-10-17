<?php

namespace App\Domain\Post\Entities;

use DateTimeImmutable;

final class Post
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public string $title,
        public string $body,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
        public readonly ?DateTimeImmutable $deletedAt = null,
    ) {}
}

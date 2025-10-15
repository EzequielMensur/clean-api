<?php

namespace App\Application\Post\DTOs;

final class CreatePostInput
{
    public function __construct(
        public int $userId,
        public string $title,
        public string $body,
    ) {}
}

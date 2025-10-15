<?php

namespace App\Application\Post\DTOs;

final class UpdatePostInput
{
    public function __construct(
        public int $id,
        public ?string $title = null,
        public ?string $body = null,
    ) {}
}

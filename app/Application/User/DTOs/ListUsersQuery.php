<?php

namespace App\Application\User\DTOs;

final readonly class ListUsersQuery
{
    public function __construct(
        public ?string $q = null,
        public int $page = 1,
        public int $perPage = 10,
        public ?string $sort = null,
    ) {}
}

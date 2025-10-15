<?php

namespace App\Application\Post\DTOs;

final class ListPostsQuery
{
    public function __construct(
        public ?string $q = null,
        public ?int $userId = null,
        public int $page = 1,
        public int $perPage = 10,
        public ?string $sort = '-id',
        public bool $withTrashed = false,
        public bool $onlyTrashed = false,
    ) {}
}

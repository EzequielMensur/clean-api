<?php

namespace App\Domain\Post\Repositories;

use App\Domain\Post\Entities\Post;

interface PostRepository
{
    public function paginate(
        ?string $q = null,
        ?int $userId = null,
        int $page = 1,
        int $perPage = 10,
        ?string $sort = '-id',
        bool $withTrashed = false,
        bool $onlyTrashed = false,
    ): array;

    public function find(int $id, bool $withTrashed = false): Post;

    public function create(int $userId, string $title, string $body): Post;

    public function update(int $id, array $data): Post;

    public function delete(int $id): void;

    public function restore(int $id): void;
}

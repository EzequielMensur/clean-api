<?php

namespace App\Application\Post\UseCases;

use App\Domain\Post\Repositories\PostRepository;

final class DeletePost
{
    public function __construct(private readonly PostRepository $repo) {}

    public function __invoke(int $id): void
    {
        $this->repo->delete($id);
    }
}

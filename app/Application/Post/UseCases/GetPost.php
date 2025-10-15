<?php

namespace App\Application\Post\UseCases;

use App\Application\Post\DTOs\PostOutput;
use App\Domain\Post\Repositories\PostRepository;

final class GetPost
{
    public function __construct(private readonly PostRepository $repo) {}

    public function __invoke(int $id, bool $withTrashed = false): PostOutput
    {
        $post = $this->repo->find($id, $withTrashed);

        return PostOutput::fromDomain($post);
    }
}

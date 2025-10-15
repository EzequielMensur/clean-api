<?php

namespace App\Application\Post\UseCases;

use App\Application\Post\DTOs\CreatePostInput;
use App\Application\Post\DTOs\PostOutput;
use App\Domain\Post\Repositories\PostRepository;

final class CreatePost
{
    public function __construct(private readonly PostRepository $repo) {}

    public function __invoke(CreatePostInput $in): PostOutput
    {
        $post = $this->repo->create(
            userId: $in->userId,
            title: $in->title,
            body: $in->body,
        );

        return PostOutput::fromDomain($post);
    }
}

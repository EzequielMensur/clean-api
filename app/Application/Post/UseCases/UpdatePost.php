<?php

namespace App\Application\Post\UseCases;

use App\Application\Post\DTOs\PostOutput;
use App\Application\Post\DTOs\UpdatePostInput;
use App\Domain\Post\Repositories\PostRepository;

final class UpdatePost
{
    public function __construct(private readonly PostRepository $repo) {}

    public function __invoke(UpdatePostInput $in): PostOutput
    {
        $data = array_filter([
            'title' => $in->title,
            'body' => $in->body,
        ], fn (?string $v): bool => ! is_null($v));

        $post = $this->repo->update($in->id, $data);

        return PostOutput::fromDomain($post);
    }
}
